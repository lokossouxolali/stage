<?php

namespace App\Http\Controllers;

use App\Mail\InscriptionEnAttente;
use App\Mail\OtpCodeMail;
use App\Models\Entreprise;
use App\Models\Notification;
use App\Models\PendingRegistration;
use App\Models\RegistrationToken;
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

            if ($user->statut_inscription !== 'valide' || !$user->est_actif) {
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
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validateRegistration($request);

        $token = RegistrationToken::where('token_hash', RegistrationToken::hashToken($request->registration_token))
            ->where('role', $request->role)
            ->whereNull('used_at')
            ->first();

        if (!$token) {
            return back()
                ->withErrors(['registration_token' => 'Token invalide, deja utilise ou non compatible avec ce type de compte.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $otp = (string) random_int(100000, 999999);
        $payload = $request->except(['_token', 'password_confirmation', 'registration_token']);
        $payload['password'] = Hash::make($request->password);

        PendingRegistration::where('email', $request->email)->whereNull('consumed_at')->delete();

        $pending = PendingRegistration::create([
            'email' => $request->email,
            'payload' => $payload,
            'registration_token_id' => $token->id,
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        session(['pending_registration_id' => $pending->id]);

        try {
            Mail::to($request->email)->send(new OtpCodeMail($otp, 'votre inscription'));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du OTP d\'inscription : ' . $e->getMessage());

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

        if (!$pending || $pending->consumed_at) {
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

        $pending = PendingRegistration::with('registrationToken')->find(session('pending_registration_id'));

        if (!$pending || $pending->consumed_at) {
            return redirect()->route('register')->with('error', 'Cette demande d\'inscription n\'est plus valide.');
        }

        if ($pending->otp_expires_at->isPast()) {
            return back()->withErrors(['otp_code' => 'Le code OTP a expire. Veuillez relancer l\'inscription.']);
        }

        if (!Hash::check($request->otp_code, $pending->otp_hash)) {
            return back()->withErrors(['otp_code' => 'Code OTP incorrect.']);
        }

        if ($pending->registrationToken->isUsed()) {
            return redirect()->route('register')->with('error', 'Ce token a deja ete utilise.');
        }

        $user = $this->createPendingUser($pending->payload);

        $pending->registrationToken->update([
            'used_by' => $user->id,
            'used_at' => now(),
        ]);

        $pending->update(['consumed_at' => now()]);
        session()->forget('pending_registration_id');

        $this->notifyRegistrationPending($user);

        return redirect('/login')
            ->with('success', 'Email verifie. Votre inscription est maintenant en attente de validation par un responsable pedagogique.');
    }

    public function resendRegistrationOtp()
    {
        $pending = PendingRegistration::find(session('pending_registration_id'));

        if (!$pending || $pending->consumed_at) {
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
            \Log::error('Erreur lors du renvoi du OTP d\'inscription : ' . $e->getMessage());

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

    private function validateRegistration(Request $request): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:etudiant,entreprise,enseignant',
            'registration_token' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
        ];

        if ($request->role === 'etudiant') {
            $rules['niveau_etude'] = 'required|in:L1,L2,L3,M1,M2';
            $rules['filiere'] = 'required|string|max:100';
        } elseif ($request->role === 'entreprise') {
            $rules['nom_entreprise'] = 'required|string|max:255';
            $rules['secteur_activite'] = 'nullable|string|max:255';
            $rules['adresse_entreprise'] = 'nullable|string|max:500';
        } elseif ($request->role === 'enseignant') {
            $rules['specialite'] = 'nullable|string|max:255';
        }

        return $request->validate($rules);
    }

    private function createPendingUser(array $payload): User
    {
        $entrepriseId = null;

        if (($payload['role'] ?? null) === 'entreprise' && !empty($payload['nom_entreprise'])) {
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
            $userData['filiere'] = $payload['filiere'] ?? null;
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
            \Log::error('Erreur lors de l\'envoi de l\'email d\'inscription en attente : ' . $e->getMessage());
        }

        $admins = User::whereIn('role', User::administrativeRoles())->where('est_actif', true)->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'nouvelle_inscription',
                'titre' => 'Nouvelle inscription en attente',
                'message' => $user->name . ' (' . $user->email . ') a soumis une demande d\'inscription en tant que ' . ucfirst($user->role) . '.',
                'lien' => route('users.index'),
            ]);
        }
    }
}
