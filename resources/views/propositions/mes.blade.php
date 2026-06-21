@extends('layouts.app')

@section('title', 'Mes propositions de thème')
@section('page-title', 'Mes propositions de thème')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h6 class="mb-0 text-muted fw-normal">
            <i class="fas fa-file-alt me-2"></i>
            Mes propositions de thème
        </h6>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('propositions.create') }}" class="btn btn-sm" style="background-color: #0b1f4d; border-color: #0b1f4d; color: #ffffff;">
            <i class="fas fa-plus me-1"></i>
            Nouvelle proposition
        </a>
    </div>
</div>

@if($propositions->count() > 0)
    <div class="row">
        @foreach($propositions as $proposition)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0 text-truncate" title="{{ $proposition->titre }}">
                                {{ Str::limit($proposition->titre, 30) }}
                            </h6>
                            <span class="badge
                                @if($proposition->statut === 'en_attente') bg-warning
                                @elseif($proposition->statut === 'valide') bg-success
                                @else bg-danger
                                @endif">
                                @if($proposition->statut === 'en_attente') En attente
                                @elseif($proposition->statut === 'valide') Validée
                                @else Refusée
                                @endif
                            </span>
                        </div>

                        <p class="card-text text-muted small mb-2">
                            {{ Str::limit($proposition->description, 80) }}
                        </p>

                        <div class="small text-muted mb-2">
                            <i class="fas fa-calendar me-1"></i>
                            Soumise le {{ $proposition->date_soumission->format('d/m/Y') }}
                        </div>

                        <div class="small text-muted mb-3">
                            <i class="fas fa-user-tie me-1"></i>
                            Directeur: {{ $proposition->directeurMemoire ? $proposition->directeurMemoire->name : 'Non assigné' }}
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('propositions.show', $proposition) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>
                                Voir
                            </a>

                            @if($proposition->statut === 'en_attente')
                                <a href="{{ route('propositions.edit', $proposition) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit me-1"></i>
                                    Modifier
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $propositions->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Aucune proposition de thème</h5>
            <p class="text-muted mb-4">Vous n'avez pas encore soumis de proposition de thème.</p>
            <a href="{{ route('propositions.create') }}" class="btn" style="background-color: #0b1f4d; border-color: #0b1f4d; color: #ffffff;">
                <i class="fas fa-plus me-1"></i>
                Créer ma première proposition
            </a>
        </div>
    </div>
@endif
@endsection