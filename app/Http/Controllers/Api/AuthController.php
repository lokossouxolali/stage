<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\InscriptionEnAttente;
use App\Mail\OtpCodeMail;
use App\Models\Notification;
use App\Models\PendingRegistration;
use App\Models\RegistrationToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects',
            ], 401);
        }

        $user = Auth::user();

        if ($user->statut_inscription !== 'valide' || !$user->est_actif) {
            Auth::logout();

            return response()->json([
                'success' => false,
                'message' => 'Votre compte n\'a pas encore ete valide par un responsable pedagogique.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion reussie',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:enseignant,etudiant,entreprise',
            'registration_token' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'niveau_etude' => 'nullable|string|max:50',
            'filiere' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        $registrationToken = RegistrationToken::where('token_hash', RegistrationToken::hashToken($request->registration_token))
            ->where('role', $request->role)
            ->whereNull('used_at')
            ->first();

        if (!$registrationToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide, deja utilise ou non compatible avec ce type de compte.',
            ], 422);
        }

        $otp = (string) random_int(100000, 999999);
        $payload = $request->except(['password_confirmation', 'registration_token']);
        $payload['password'] = Hash::make($request->password);

        PendingRegistration::where('email', $request->email)->whereNull('consumed_at')->delete();

        $pending = PendingRegistration::create([
            'email' => $request->email,
            'payload' => $payload,
            'registration_token_id' => $registrationToken->id,
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        try {
            Mail::to($request->email)->send(new OtpCodeMail($otp, 'votre inscription'));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du OTP d\'inscription (API) : ' . $e->getMessage());

            $pending->delete();

            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'envoyer le code OTP. Verifiez la configuration email puis reessayez.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code OTP envoye. Il expire dans 5 minutes.',
            'pending_registration_id' => $pending->id,
        ], 202);
    }

    public function verifyRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pending_registration_id' => 'required|exists:pending_registrations,id',
            'otp_code' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        $pending = PendingRegistration::with('registrationToken')->find($request->pending_registration_id);

        if (!$pending || $pending->consumed_at || $pending->otp_expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Demande expiree ou deja utilisee.',
            ], 422);
        }

        if (!Hash::check($request->otp_code, $pending->otp_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Code OTP incorrect.',
            ], 422);
        }

        if ($pending->registrationToken->isUsed()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce token a deja ete utilise.',
            ], 422);
        }

        $payload = $pending->payload;
        $user = User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
            'role' => $payload['role'],
            'telephone' => $payload['telephone'] ?? null,
            'niveau_etude' => $payload['niveau_etude'] ?? null,
            'filiere' => $payload['filiere'] ?? null,
            'statut_inscription' => 'en_attente',
            'est_actif' => false,
        ]);

        $pending->registrationToken->update([
            'used_by' => $user->id,
            'used_at' => now(),
        ]);

        $pending->update(['consumed_at' => now()]);

        try {
            Mail::to($user->email)->send(new InscriptionEnAttente($user));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email d\'inscription en attente (API) : ' . $e->getMessage());
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

        return response()->json([
            'success' => true,
            'message' => 'Email verifie. Votre compte est en attente de validation par un responsable pedagogique.',
            'user' => $user,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deconnexion reussie',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()->load(['entreprise']),
        ]);
    }
}
