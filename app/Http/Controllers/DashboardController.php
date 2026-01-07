<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\User;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Statistiques globales (admin seulement)
        $stats = [];
        
        // Notifications récentes pour l'utilisateur
        $notificationsRecentes = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        if ($user->isAdmin()) {
            $stats = [
                'entreprises' => Entreprise::count(),
                'offres' => Offre::count(),
                'candidatures' => Candidature::count(),
                'utilisateurs' => User::count(),
                'inscriptions_en_attente' => User::where('statut_inscription', 'en_attente')->count(),
                'notifications_non_lues' => Notification::where('user_id', $user->id)->where('lu', false)->count(),
            ];
        } elseif ($user->isEntreprise()) {
            $stats = [
                'mes_offres' => Offre::where('entreprise_id', $user->entreprise_id)->count(),
                'candidatures_recues' => Candidature::whereHas('offre', function($query) use ($user) {
                    $query->where('entreprise_id', $user->entreprise_id);
                })->count(),
            ];
        } elseif ($user->isEtudiant()) {
            $stats = [
                'mes_candidatures' => Candidature::where('etudiant_id', $user->id)->count(),
                'offres_disponibles' => Offre::where('statut', 'active')->count(),
            ];
        } elseif ($user->isEnseignant()) {
            $stats = [
                'etudiants_encadres' => User::where('directeur_memoire_id', $user->id)->count(),
            ];
        }

        return view('dashboard', compact('stats', 'notificationsRecentes'));
    }

    public function statistiques()
    {
        // Statistiques détaillées pour l'admin
        $stats = [
            'total_entreprises' => Entreprise::count(),
            'total_offres' => Offre::count(),
            'offres_actives' => Offre::where('statut', 'active')->count(),
            'total_candidatures' => Candidature::count(),
            'candidatures_en_attente' => Candidature::where('statut', 'en_attente')->count(),
            'candidatures_acceptees' => Candidature::where('statut', 'acceptee')->count(),
            'total_utilisateurs' => User::count(),
            'inscriptions_en_attente' => User::where('statut_inscription', 'en_attente')->count(),
            'utilisateurs_actifs' => User::where('est_actif', true)->count(),
        ];
        
        return view('statistiques', compact('stats'));
    }
}
