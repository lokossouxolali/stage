@extends('layouts.app')

@section('title', 'Statistiques')
@section('page-title', 'Statistiques')

@section('content')
<div class="row g-3">
    @foreach([
        'total_entreprises' => ['Entreprises', 'fa-building'],
        'total_offres' => ['Offres', 'fa-briefcase'],
        'offres_actives' => ['Offres actives', 'fa-check-circle'],
        'total_candidatures' => ['Candidatures', 'fa-file-alt'],
        'candidatures_en_attente' => ['En attente', 'fa-clock'],
        'candidatures_acceptees' => ['Acceptees', 'fa-check'],
        'total_utilisateurs' => ['Utilisateurs', 'fa-users'],
        'inscriptions_en_attente' => ['Inscriptions en attente', 'fa-user-clock'],
        'utilisateurs_actifs' => ['Utilisateurs actifs', 'fa-user-check'],
    ] as $key => [$label, $icon])
        <div class="col-md-4 col-xl-3">
            <div class="card stats-card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-uppercase mb-2">{{ $label }}</div>
                        <div class="stats-number">{{ $stats[$key] ?? 0 }}</div>
                    </div>
                    <i class="fas {{ $icon }} fa-2x"></i>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
