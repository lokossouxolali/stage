@extends('layouts.app')

@section('title', 'Propositions de thème reçues')
@section('page-title', 'Propositions de thème reçues')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h6 class="mb-0 text-muted fw-normal">
            <i class="fas fa-inbox me-2"></i>
            Propositions de thème reçues
        </h6>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group" role="group">
            <a href="{{ route('propositions.encadrees') }}" class="btn btn-sm {{ !request('filtre') ? 'active' : '' }}" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;">
                Toutes
            </a>
            <a href="{{ route('propositions.encadrees', ['filtre' => 'en_attente']) }}" class="btn btn-sm {{ request('filtre') === 'en_attente' ? 'active' : '' }}" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;">
                En attente
            </a>
            <a href="{{ route('propositions.encadrees', ['filtre' => 'valide']) }}" class="btn btn-sm {{ request('filtre') === 'valide' ? 'active' : '' }}" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;">
                Validées
            </a>
            <a href="{{ route('propositions.encadrees', ['filtre' => 'refuse']) }}" class="btn btn-sm {{ request('filtre') === 'refuse' ? 'active' : '' }}" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;">
                Refusées
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-3">
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
                                    <i class="fas fa-user me-1"></i>
                                    Étudiant: <strong>{{ $proposition->etudiant->name }}</strong>
                                </div>

                                <div class="small text-muted mb-2">
                                    <i class="fas fa-calendar me-1"></i>
                                    Soumise le {{ $proposition->date_soumission->format('d/m/Y') }}
                                </div>

                                @if($proposition->objectifs)
                                    <div class="small text-muted mb-2">
                                        <i class="fas fa-bullseye me-1"></i>
                                        Objectifs définis
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('propositions.show', $proposition) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>
                                        Voir
                                    </a>

                                    @if($proposition->statut === 'en_attente')
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('propositions.valider-enseignant', $proposition) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" title="Valider">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-sm btn-danger" title="Refuser"
                                                    data-bs-toggle="modal" data-bs-target="#refuserModal{{ $proposition->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de refus pour chaque proposition -->
                    @if($proposition->statut === 'en_attente')
                    <div class="modal fade" id="refuserModal{{ $proposition->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Refuser la proposition</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('propositions.rejeter-enseignant', $proposition) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="commentaires_enseignant{{ $proposition->id }}" class="form-label">Motif du refus</label>
                                            <textarea class="form-control" id="commentaires_enseignant{{ $proposition->id }}"
                                                      name="commentaires_enseignant" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-danger">Refuser</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $propositions->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucune proposition reçue</h5>
                <p class="text-muted">Vous n'avez pas de propositions de thème à examiner pour le moment.</p>
            </div>
        @endif
    </div>
</div>
@endsection