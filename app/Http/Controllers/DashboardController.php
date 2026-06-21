<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Entreprise;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\PropositionTheme;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        $notificationsRecentes = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $usersByRole = collect();
        $propositionsByStatut = collect();

        if ($user->isAdmin()) {
            $offresByStatut = Offre::selectRaw('statut, COUNT(*) as total')->groupBy('statut')->pluck('total', 'statut');
            $usersByRole = User::selectRaw('role, COUNT(*) as total')->groupBy('role')->pluck('total', 'role');
            $usersByInscription = User::selectRaw('statut_inscription, COUNT(*) as total')->groupBy('statut_inscription')->pluck('total', 'statut_inscription');
            $propositionsByStatut = PropositionTheme::selectRaw('statut, COUNT(*) as total')->groupBy('statut')->pluck('total', 'statut');

            $stats = [
                'entreprises' => Entreprise::count(),
                'offres' => $offresByStatut->sum(),
                'offres_actives' => (int) ($offresByStatut['active'] ?? 0),
                'candidatures' => Candidature::count(),
                'utilisateurs' => $usersByRole->sum(),
                'inscriptions_en_attente' => (int) ($usersByInscription['en_attente'] ?? 0),
                'notifications_non_lues' => Notification::where('user_id', $user->id)->where('lu', false)->count(),
            ];
        } elseif ($user->isEntreprise()) {
            $stats = [
                'mes_offres' => Offre::where('entreprise_id', $user->entreprise_id)->count(),
                'candidatures_recues' => Candidature::whereHas('offre', function ($query) use ($user) {
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

        $charts = [
            'roles' => [
                'labels' => ['Super admins', 'Resp. pedagogiques', 'Etudiants', 'Entreprises', 'Enseignants'],
                'data' => [
                    (int) ($usersByRole[User::ROLE_SUPER_ADMIN] ?? 0),
                    (int) (($usersByRole[User::ROLE_ADMIN] ?? 0) + ($usersByRole[User::ROLE_LEGACY_ADMIN] ?? 0)),
                    (int) ($usersByRole[User::ROLE_ETUDIANT] ?? 0),
                    (int) ($usersByRole[User::ROLE_ENTREPRISE] ?? 0),
                    (int) ($usersByRole[User::ROLE_ENSEIGNANT] ?? 0),
                ],
            ],
            'propositions' => [
                'labels' => ['Themes soumis', 'Themes valides', 'Themes refuses'],
                'data' => [
                    (int) ($propositionsByStatut['en_attente'] ?? 0),
                    (int) ($propositionsByStatut['valide'] ?? 0),
                    (int) ($propositionsByStatut['refuse'] ?? 0),
                ],
            ],
        ];

        return view('dashboard', compact('stats', 'notificationsRecentes', 'charts'));
    }

    public function statistiques()
    {
        $candidaturesByStatut = Candidature::selectRaw('statut, COUNT(*) as total')->groupBy('statut')->pluck('total', 'statut');
        $offresByStatut = Offre::selectRaw('statut, COUNT(*) as total')->groupBy('statut')->pluck('total', 'statut');
        $usersByInscription = User::selectRaw('statut_inscription, COUNT(*) as total')->groupBy('statut_inscription')->pluck('total', 'statut_inscription');

        $stats = [
            'total_entreprises' => Entreprise::count(),
            'total_offres' => $offresByStatut->sum(),
            'offres_actives' => (int) ($offresByStatut['active'] ?? 0),
            'total_candidatures' => $candidaturesByStatut->sum(),
            'candidatures_en_attente' => (int) ($candidaturesByStatut['en_attente'] ?? 0),
            'candidatures_acceptees' => (int) ($candidaturesByStatut['acceptee'] ?? 0),
            'total_utilisateurs' => User::count(),
            'inscriptions_en_attente' => (int) ($usersByInscription['en_attente'] ?? 0),
            'utilisateurs_actifs' => User::where('est_actif', true)->count(),
        ];

        return view('statistiques', compact('stats'));
    }
}
