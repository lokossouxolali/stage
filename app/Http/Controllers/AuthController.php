<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Notification;
use App\Models\Entreprise;
use App\Mail\InscriptionEnAttente;

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
            
            // Vérifier que le compte est validé
            if ($user->statut_inscription !== 'valide' || !$user->est_actif) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte n\'a pas encore été validé par l\'administrateur.',
                ])->onlyInput('email');
            }
            
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validation de base
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:etudiant,entreprise,enseignant',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
        ];

        // Validation conditionnelle selon le rôle
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

        $validated = $request->validate($rules);

        // Créer l'entreprise si c'est un utilisateur entreprise
        $entrepriseId = null;
        if ($request->role === 'entreprise' && $request->nom_entreprise) {
            $entreprise = Entreprise::create([
                'nom' => $request->nom_entreprise,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse_entreprise,
                'secteur_activite' => $request->secteur_activite,
                'est_verifiee' => false,
            ]);
            $entrepriseId = $entreprise->id;
        }

        // Créer l'utilisateur
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'statut_inscription' => 'en_attente',
            'est_actif' => false,
        ];

        // Ajouter les champs spécifiques selon le rôle
        if ($request->role === 'etudiant') {
            $userData['niveau_etude'] = $request->niveau_etude;
            $userData['filiere'] = $request->filiere;
        }

        if ($entrepriseId) {
            $userData['entreprise_id'] = $entrepriseId;
        }

        $user = User::create($userData);

        // Envoyer un email à l'utilisateur pour l'informer que son inscription est en attente
        try {
            Mail::to($user->email)->send(new InscriptionEnAttente($user));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email d\'inscription en attente : ' . $e->getMessage());
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

        // Ne pas connecter automatiquement, attendre la validation par l'admin
        return redirect('/login')
            ->with('success', 'Votre inscription a été soumise. Un email de confirmation vous a été envoyé. Vous pourrez vous connecter une fois votre compte validé par l\'administrateur.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
