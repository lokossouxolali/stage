@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
@if(auth()->user()->isAdmin() && isset($stats['inscriptions_en_attente']) && $stats['inscriptions_en_attente'] > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Attention !</strong> Il y a <strong>{{ $stats['inscriptions_en_attente'] }}</strong> inscription(s) en attente de validation.
        <a href="{{ route('users.index') }}" class="alert-link ms-2">Voir les utilisateurs</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Statistiques générales -->
    <div class="col-12">
        <div class="row mb-4">
            @if(auth()->user()->isAdmin())
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Utilisateurs</div>
                                    <div class="stats-number">{{ $stats['total_users'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Entreprises</div>
                                    <div class="stats-number">{{ $stats['total_entreprises'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-building fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Offres Actives</div>
                                    <div class="stats-number">{{ $stats['offres_actives'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-briefcase fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Rapports</div>
                                    <div class="stats-number">{{ $stats['rapports'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-pdf fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(auth()->user()->isEtudiant())
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Mes Candidatures</div>
                                    <div class="stats-number">{{ $stats['mes_candidatures'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Offres Disponibles</div>
                                    <div class="stats-number">{{ $stats['offres_disponibles'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-briefcase fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Mes Rapports</div>
                                    <div class="stats-number">{{ $stats['mes_rapports'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-pdf fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(auth()->user()->isEntreprise())
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Mes Offres</div>
                                    <div class="stats-number">{{ $stats['mes_offres'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-briefcase fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Candidatures Reçues</div>
                                    <div class="stats-number">{{ $stats['candidatures_recues'] ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-inbox fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>
</div>

<div class="row">
    <!-- Actions rapides -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Actions rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(auth()->user()->isAdmin())
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('entreprises.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-building me-2"></i>
                                Ajouter une entreprise
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('users.create') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-user-plus me-2"></i>
                                Créer un utilisateur
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('offres.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus me-2"></i>
                                Publier une offre
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->isEtudiant())
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('offres.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search me-2"></i>
                                Rechercher des offres
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('candidatures.create') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-paper-plane me-2"></i>
                                Nouvelle candidature
                            </a>
                        </div>
                    @endif

                    <div class="col-md-6 mb-3">
                        <a href="{{ route('rapports.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-file-pdf me-2"></i>
                            Voir les rapports
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('rapports.index') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-file-pdf me-2"></i>
                            Gérer les rapports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications récentes -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0" style="font-size: 0.9rem;">
                    <i class="fas fa-bell me-2"></i>
                    Notifications récentes
                </h5>
                @if(isset($notificationsRecentes) && $notificationsRecentes->where('lu', false)->count() > 0)
                    <span class="badge bg-danger">{{ $notificationsRecentes->where('lu', false)->count() }}</span>
                @endif
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                @if(isset($notificationsRecentes) && $notificationsRecentes->count() > 0)
                    @foreach($notificationsRecentes as $notification)
                        <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0 me-2">
                                @if(!$notification->lu)
                                    <span class="badge bg-primary rounded-circle" style="width: 8px; height: 8px; padding: 0;"></span>
                                @else
                                    <i class="fas fa-circle text-muted" style="font-size: 0.4rem;"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1" style="font-size: 0.8rem;">{{ $notification->titre }}</div>
                                <p class="text-muted mb-1" style="font-size: 0.75rem;">{{ Str::limit($notification->message, 60) }}</p>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                @if($notification->lien)
                                    <div class="mt-1">
                                        <a href="{{ $notification->lien }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">
                                            Voir
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-2">
                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size: 0.75rem;">
                            Voir toutes les notifications
                        </a>
                    </div>
                @else
                    <p class="text-muted text-center" style="font-size: 0.8rem;">Aucune notification</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Graphiques et statistiques avancées -->
@if(auth()->user()->isAdmin())
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques avancées
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Répartition des utilisateurs par rôle</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar" role="progressbar" style="width: {{ $stats['pourcentage_etudiants'] ?? 0 }}%">
                                Étudiants: {{ $stats['pourcentage_etudiants'] ?? 0 }}%
                            </div>
                        </div>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['pourcentage_entreprises'] ?? 0 }}%">
                                Entreprises: {{ $stats['pourcentage_entreprises'] ?? 0 }}%
                            </div>
                        </div>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['pourcentage_enseignants'] ?? 0 }}%">
                                Enseignants: {{ $stats['pourcentage_enseignants'] ?? 0 }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Rapports par statut</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['pourcentage_rapports_soumis'] ?? 0 }}%">
                                Soumis: {{ $stats['pourcentage_rapports_soumis'] ?? 0 }}%
                            </div>
                        </div>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['pourcentage_rapports_valides'] ?? 0 }}%">
                                Validés: {{ $stats['pourcentage_rapports_valides'] ?? 0 }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
