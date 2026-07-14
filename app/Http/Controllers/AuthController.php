<?php

namespace App\Http\Controllers;

use App\Mail\InscriptionEnAttente;
use App\Mail\OtpCodeMail;
use App\Models\Entreprise;
use App\Models\Filiere;
use App\Models\Notification;
use App\Models\PasswordChangeOtp;
use App\Models\PendingRegistration;
use App\Models\Specialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->statut_inscription !== 'valide' || ! $user->est_actif) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Votre compte n\'a pas encore ete valide par un responsable pedagogique.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas a nos enregistrements.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        $filieres = Filiere::orderBy('nom')->get(['id', 'nom', 'code']);
        $specialites = Specialite::orderBy('nom')->get(['id', 'nom', 'code']);

        return view('auth.register', compact('filieres', 'specialites'));
    }

    public function register(Request $request)
    {
        $payload = $this->validateRegistration($request);

        $otp = (string) random_int(100000, 999999);
        $payload['password'] = Hash::make($request->password);

        PendingRegistration::where('email', $request->email)->whereNull('consumed_at')->delete();

        $pending = PendingRegistration::create([
            'email' => $request->email,
            'payload' => $payload,
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        session(['pending_registration_id' => $pending->id]);

        try {
            Mail::to($request->email)->send(new OtpCodeMail($otp, 'votre inscription'));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du OTP d\'inscription : '.$e->getMessage());

            $pending->delete();
            session()->forget('pending_registration_id');

            return back()
                ->withErrors(['email' => 'Impossible d\'envoyer le code OTP. Verifiez la configuration email puis reessayez.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        return redirect()->route('register.verify')
            ->with('success', 'Un code OTP a ete envoye a votre adresse email. Il expire dans 5 minutes.');
    }

    public function showRegisterOtpForm()
    {
        $pending = PendingRegistration::find(session('pending_registration_id'));

        if (! $pending || $pending->consumed_at) {
            return redirect()->route('register')
                ->with('error', 'Aucune inscription en attente. Veuillez remplir le formulaire.');
        }

        return view('auth.verify-registration', compact('pending'));
    }

    public function verifyRegistrationOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $pending = PendingRegistration::find(session('pending_registration_id'));

        if (! $pending || $pending->consumed_at) {
            return redirect()->route('register')->with('error', 'Cette demande d\'inscription n\'est plus valide.');
        }

        if ($pending->otp_expires_at->isPast()) {
            return back()->withErrors(['otp_code' => 'Le code OTP a expire. Veuillez relancer l\'inscription.']);
        }

        if (! Hash::check($request->otp_code, $pending->otp_hash)) {
            return back()->withErrors(['otp_code' => 'Code OTP incorrect.']);
        }

        $user = $this->createPendingUser($pending->payload);

        $pending->update(['consumed_at' => now()]);
        session()->forget('pending_registration_id');

        $this->notifyRegistrationPending($user);

        return redirect('/login')
            ->with('success', 'Email verifie. Votre inscription est maintenant en attente de validation par un responsable pedagogique.');
    }

    public function resendRegistrationOtp()
    {
        $pending = PendingRegistration::find(session('pending_registration_id'));

        if (! $pending || $pending->consumed_at) {
            return redirect()->route('register')
                ->with('error', 'Aucune inscription en attente. Veuillez remplir le formulaire.');
        }

        if ($pending->created_at && $pending->updated_at && $pending->updated_at->gt(now()->subMinute())) {
            return back()->with('error', 'Veuillez patienter une minute avant de demander un nouveau code.');
        }

        $otp = (string) random_int(100000, 999999);
        $pending->update([
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        try {
            Mail::to($pending->email)->send(new OtpCodeMail($otp, 'votre inscription'));
        } catch (\Exception $e) {
            \Log::error('Erreur lors du renvoi du OTP d\'inscription : '.$e->getMessage());

            return back()->with('error', 'Impossible de renvoyer le code OTP. Verifiez la configuration email puis reessayez.');
        }

        return back()->with('success', 'Un nouveau code OTP a ete envoye. Il expire dans 5 minutes.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Aucun compte n\'est associé à cet email.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user->est_actif || $user->statut_inscription !== 'valide') {
            return back()->withErrors(['email' => 'Ce compte n\'est pas actif. Contactez l\'administration.']);
        }

        PasswordChangeOtp::where('user_id', $user->id)->whereNull('used_at')->delete();

        $otp = (string) random_int(100000, 999999);

        PasswordChangeOtp::create([
            'user_id' => $user->id,
            'otp_hash' => Hash::make($otp),
            'password_hash' => null,
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new OtpCodeMail($otp, 'la réinitialisation de votre mot de passe'));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi OTP reset password : '.$e->getMessage());

            return back()->withErrors(['email' => 'Impossible d\'envoyer le code. Vérifiez la configuration email.']);
        }

        session(['reset_password_user_id' => $user->id]);

        return redirect()->route('password.reset.form')
            ->with('success', 'Un code OTP a été envoyé à '.$user->email.'. Il expire dans 10 minutes.');
    }

    public function showResetPasswordForm()
    {
        if (! session('reset_password_user_id')) {
            return redirect()->route('password.request')
                ->with('error', 'Veuillez d\'abord saisir votre adresse email.');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $userId = session('reset_password_user_id');

        if (! $userId) {
            return redirect()->route('password.request')
                ->with('error', 'Session expirée. Recommencez la procédure.');
        }

        $request->validate([
            'otp_code' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otp = PasswordChangeOtp::where('user_id', $userId)
            ->whereNull('used_at')
            ->whereNull('password_hash')
            ->latest()
            ->first();

        if (! $otp) {
            return back()->withErrors(['otp_code' => 'Aucune demande de réinitialisation en attente.']);
        }

        if ($otp->expires_at->isPast()) {
            return back()->withErrors(['otp_code' => 'Le code OTP a expiré. Recommencez la procédure.']);
        }

        if (! Hash::check($request->otp_code, $otp->otp_hash)) {
            return back()->withErrors(['otp_code' => 'Code OTP incorrect.']);
        }

        $user = User::findOrFail($userId);
        $user->update(['password' => Hash::make($request->password)]);

        $otp->update(['used_at' => now()]);
        session()->forget('reset_password_user_id');

        return redirect()->route('login')
            ->with('success', 'Mot de passe réinitialisé avec succès. Vous pouvez vous connecter.');
    }

    private function validateRegistration(Request $request): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:etudiant,entreprise,enseignant',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
        ];

        if ($request->role === 'etudiant') {
            $rules['niveau_etude'] = 'required|in:L1,L2,L3,M1,M2';
            $rules['filiere_id'] = 'required|integer|exists:filieres,id';
        } elseif ($request->role === 'entreprise') {
            $rules['nom_entreprise'] = 'required|string|max:255';
            $rules['secteur_activite'] = 'nullable|string|max:255';
            $rules['adresse_entreprise'] = 'nullable|string|max:500';
        } elseif ($request->role === 'enseignant') {
            $rules['specialite_id'] = 'required|integer|exists:specialites,id';
        }

        return $request->validate($rules);
    }

    private function createPendingUser(array $payload): User
    {
        $entrepriseId = null;

        if (($payload['role'] ?? null) === 'entreprise' && ! empty($payload['nom_entreprise'])) {
            $entreprise = Entreprise::create([
                'nom' => $payload['nom_entreprise'],
                'email' => $payload['email'],
                'telephone' => $payload['telephone'] ?? null,
                'adresse' => $payload['adresse_entreprise'] ?? null,
                'secteur_activite' => $payload['secteur_activite'] ?? null,
                'est_verifiee' => false,
            ]);

            $entrepriseId = $entreprise->id;
        }

        $userData = [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
            'role' => $payload['role'],
            'telephone' => $payload['telephone'] ?? null,
            'date_naissance' => $payload['date_naissance'] ?? null,
            'statut_inscription' => 'en_attente',
            'est_actif' => false,
        ];

        if (($payload['role'] ?? null) === 'etudiant') {
            $userData['niveau_etude'] = $payload['niveau_etude'] ?? null;
            $userData['filiere_id'] = $payload['filiere_id'] ?? null;
        }

        if (($payload['role'] ?? null) === 'enseignant') {
            $userData['specialite_id'] = $payload['specialite_id'] ?? null;
        }

        if ($entrepriseId) {
            $userData['entreprise_id'] = $entrepriseId;
        }

        return User::create($userData);
    }

    private function notifyRegistrationPending(User $user): void
    {
        try {
            Mail::to($user->email)->send(new InscriptionEnAttente($user));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email d\'inscription en attente : '.$e->getMessage());
        }

        $admins = User::whereIn('role', User::administrativeRoles())->where('est_actif', true)->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'nouvelle_inscription',
                'titre' => 'Nouvelle inscription en attente',
                'message' => $user->name.' ('.$user->email.') a soumis une demande d\'inscription en tant que '.ucfirst($user->role).'.',
                'lien' => route('users.index'),
            ]);
        }
    }
}
