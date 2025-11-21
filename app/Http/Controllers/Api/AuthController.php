<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Mail\InscriptionEnAttente;

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
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,responsable_stages,enseignant,etudiant,entreprise,jury',
            'telephone' => 'nullable|string|max:20',
            'niveau_etude' => 'nullable|string|max:50',
            'filiere' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
            'niveau_etude' => $request->niveau_etude,
            'filiere' => $request->filiere,
            'statut_inscription' => 'en_attente',
            'est_actif' => false,
        ]);

        // Envoyer un email à l'utilisateur pour l'informer que son inscription est en attente
        try {
            Mail::to($user->email)->send(new InscriptionEnAttente($user));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email d\'inscription en attente (API) : ' . $e->getMessage());
        }

        // Créer une notification pour tous les administrateurs
        $admins = User::where('role', 'admin')->where('est_actif', true)->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'nouvelle_inscription',
                'titre' => 'Nouvelle inscription en attente',
                'message' => $user->name . ' (' . $user->email . ') a soumis une demande d\'inscription en tant que ' . ucfirst($user->role) . '.',
                'lien' => route('users.index'),
            ]);
        }

        // Note: Pour l'API, on ne crée pas de token car l'utilisateur doit attendre la validation
        return response()->json([
            'success' => true,
            'message' => 'Inscription soumise avec succès. Votre compte est en attente de validation par un administrateur. Vous recevrez un email de confirmation une fois votre compte validé.',
            'user' => $user,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()->load(['entreprise'])
        ]);
    }
}
