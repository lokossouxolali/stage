@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
@php
    $metricCards = [];

    if (auth()->user()->isAdmin()) {
        $metricCards = [
            ['label' => 'Utilisateurs', 'value' => $stats['utilisateurs'] ?? 0, 'icon' => 'fa-users', 'color' => 'primary'],
            ['label' => 'Entreprises', 'value' => $stats['entreprises'] ?? 0, 'icon' => 'fa-building', 'color' => 'info'],
            ['label' => 'Offres actives', 'value' => $stats['offres_actives'] ?? 0, 'icon' => 'fa-briefcase', 'color' => 'success'],
            ['label' => 'Candidatures', 'value' => $stats['candidatures'] ?? 0, 'icon' => 'fa-file-signature', 'color' => 'warning'],
        ];
    } elseif (auth()->user()->isEntreprise()) {
        $metricCards = [
            ['label' => 'Mes offres', 'value' => $stats['mes_offres'] ?? 0, 'icon' => 'fa-briefcase', 'color' => 'primary'],
            ['label' => 'Candidatures recues', 'value' => $stats['candidatures_recues'] ?? 0, 'icon' => 'fa-inbox', 'color' => 'info'],
        ];
    } elseif (auth()->user()->isEtudiant()) {
        $metricCards = [
            ['label' => 'Mes candidatures', 'value' => $stats['mes_candidatures'] ?? 0, 'icon' => 'fa-file-alt', 'color' => 'primary'],
            ['label' => 'Offres disponibles', 'value' => $stats['offres_disponibles'] ?? 0, 'icon' => 'fa-search', 'color' => 'success'],
        ];
    } elseif (auth()->user()->isEnseignant()) {
        $metricCards = [
            ['label' => 'Etudiants encadres', 'value' => $stats['etudiants_encadres'] ?? 0, 'icon' => 'fa-user-graduate', 'color' => 'primary'],
        ];
    }
@endphp

@if(auth()->user()->isAdmin() && ($stats['inscriptions_en_attente'] ?? 0) > 0)
    <div class="alert alert-warning d-flex align-items-center justify-content-between gap-3" role="alert">
        <div>
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>{{ $stats['inscriptions_en_attente'] }}</strong> inscription(s) attendent une validation.
        </div>
        <a href="{{ route('users.index', ['statut_inscription' => 'en_attente']) }}" class="btn btn-sm btn-outline-dark">Traiter</a>
    </div>
@endif

<div class="row g-3 mb-4">
    @foreach($metricCards as $card)
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <div class="metric-label">{{ $card['label'] }}</div>
                        <div class="metric-value mt-2">{{ $card['value'] }}</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas {{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-chart-line me-2 text-primary"></i>Vue analytique</span>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('statistiques') }}" class="btn btn-sm btn-outline-primary">Details</a>
                @endif
            </div>
            <div class="card-body">
                @if(auth()->user()->isAdmin())
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="chart-box">
                                <canvas id="rolesChart"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="chart-box">
                                <canvas id="propositionsChart"></canvas>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-chart-simple fa-3x text-primary mb-3"></i>
                        <h5 class="mb-2">Votre espace est pret</h5>
                        <p class="text-muted mb-0">Utilisez les actions rapides pour continuer votre workflow.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-bell me-2 text-primary"></i>Notifications recentes</span>
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($notificationsRecentes as $notification)
                    <a href="{{ $notification->lien ?: route('notifications.index') }}" class="list-group-item list-group-item-action py-3">
                        <div class="d-flex gap-3">
                            <span class="badge-soft {{ $notification->lu ? 'badge-soft-secondary' : 'badge-soft-primary' }}">
                                <i class="fas {{ $notification->lu ? 'fa-check' : 'fa-circle' }}"></i>
                            </span>
                            <div class="min-w-0">
                                <div class="fw-semibold">{{ $notification->titre }}</div>
                                <div class="text-muted small">{{ Str::limit($notification->message, 90) }}</div>
                                <div class="text-muted small mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Aucune notification recente.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div> --}}
</div>

<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-bolt me-2 text-primary"></i>Actions rapides
    </div>
    <div class="card-body">
        <div class="row g-3">
            @if(auth()->user()->isAdmin())
                <div class="col-md-4"><a href="{{ route('users.create') }}" class="btn btn-primary w-100"><i class="fas fa-user-plus me-2"></i>Utilisateur</a></div>
                <div class="col-md-4"><a href="{{ route('entreprises.create') }}" class="btn btn-outline-primary w-100"><i class="fas fa-building me-2"></i>Entreprise</a></div>
                <div class="col-md-4"><a href="{{ route('users.index') }}" class="btn btn-outline-primary w-100"><i class="fas fa-table me-2"></i>Annuaire</a></div>
            @elseif(auth()->user()->isEntreprise())
                <div class="col-md-4"><a href="{{ route('offres.create') }}" class="btn btn-primary w-100"><i class="fas fa-plus me-2"></i>Publier une offre</a></div>
                <div class="col-md-4"><a href="{{ route('offres.mes') }}" class="btn btn-outline-primary w-100"><i class="fas fa-list me-2"></i>Mes offres</a></div>
                <div class="col-md-4"><a href="{{ route('candidatures.recues') }}" class="btn btn-outline-primary w-100"><i class="fas fa-inbox me-2"></i>Candidatures</a></div>
            @elseif(auth()->user()->isEtudiant())
                <div class="col-md-4"><a href="{{ route('offres.disponibles') }}" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i>Voir les offres</a></div>
                <div class="col-md-4"><a href="{{ route('candidatures.mes') }}" class="btn btn-outline-primary w-100"><i class="fas fa-file-alt me-2"></i>Mes candidatures</a></div>
                <div class="col-md-4"><a href="{{ route('propositions.create') }}" class="btn btn-outline-primary w-100"><i class="fas fa-plus-circle me-2"></i>Proposition</a></div>
            @elseif(auth()->user()->isEnseignant())
                <div class="col-md-6"><a href="{{ route('demandes-encadrement.index') }}" class="btn btn-primary w-100"><i class="fas fa-user-graduate me-2"></i>Demandes</a></div>
                <div class="col-md-6"><a href="{{ route('propositions.encadrees') }}" class="btn btn-outline-primary w-100"><i class="fas fa-inbox me-2"></i>Propositions</a></div>
            @endif
        </div>
    </div>
</div>
@endsection

@if(auth()->user()->isAdmin())
@push('scripts')
<script>
    window.dashboardCharts = @json($charts);
</script>
@vite('resources/js/dashboard-charts.js')
@endpush
@endif
