@extends('layouts.app')

@section('title', 'Détails de la proposition')
@section('page-title', 'Détails de la proposition de thème')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    {{ $proposition->titre }}
                </h5>
                <div>
                    @can('update', $proposition)
                        <a href="{{ route('propositions.edit', $proposition) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Modifier
                        </a>
                    @endcan
                    <a href="{{ route('propositions.mes') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Statut -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center">
                            <span class="badge
                                @if($proposition->statut === 'en_attente') bg-warning
                                @elseif($proposition->statut === 'valide') bg-success
                                @else bg-danger
                                @endif me-2">
                                @if($proposition->statut === 'en_attente') En attente
                                @elseif($proposition->statut === 'valide') Validée
                                @else Refusée
                                @endif
                            </span>
                            <small class="text-muted">
                                Soumise le {{ $proposition->date_soumission->format('d/m/Y à H:i') }}
                                @if($proposition->date_validation)
                                    - Validée le {{ $proposition->date_validation->format('d/m/Y') }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Informations générales -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations générales</h6>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Étudiant</label>
                            <p class="form-control-plaintext">{{ $proposition->etudiant->name }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Directeur de mémoire</label>
                            <p class="form-control-plaintext">
                                {{ $proposition->directeurMemoire ? $proposition->directeurMemoire->name : 'Non assigné' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Destinataires</label>
                            <p class="form-control-plaintext">
                                @if($proposition->envoye_au_directeur && $proposition->envoye_a_l_admin)
                                    Directeur de mémoire + Administrateur
                                @elseif($proposition->envoye_au_directeur)
                                    Directeur de mémoire uniquement
                                @elseif($proposition->envoye_a_l_admin)
                                    Administrateur uniquement
                                @else
                                    Aucun destinataire
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Contenu</h6>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <p class="form-control-plaintext">{{ $proposition->description }}</p>
                        </div>

                        @if($proposition->objectifs)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Objectifs</label>
                                <p class="form-control-plaintext">{{ $proposition->objectifs }}</p>
                            </div>
                        @endif

                        @if($proposition->methodologie)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Méthodologie</label>
                                <p class="form-control-plaintext">{{ $proposition->methodologie }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Documents joints -->
                @if($proposition->fiche_stage_path || $proposition->proposition_theme_path)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">Documents joints</h6>
                            <div class="row">
                                @if($proposition->fiche_stage_path)
                                    <div class="col-md-6 mb-2">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file-pdf fa-2x mb-2" style="color: #2d3748;"></i>
                                                <div class="fw-bold">Fiche de stage</div>
                                                <a href="{{ route('propositions.download.fiche', $proposition) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i>
                                                    Télécharger
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($proposition->proposition_theme_path)
                                    <div class="col-md-6 mb-2">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file-pdf fa-2x mb-2" style="color: #2d3748;"></i>
                                                <div class="fw-bold">Proposition de thème</div>
                                                <a href="{{ route('propositions.download.theme', $proposition) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i>
                                                    Télécharger
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Commentaires -->
                @if($proposition->commentaires_admin || $proposition->commentaires_enseignant)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">Commentaires</h6>

                            @if($proposition->commentaires_admin)
                                <div class="alert alert-info">
                                    <strong>Administrateur :</strong> {{ $proposition->commentaires_admin }}
                                </div>
                            @endif

                            @if($proposition->commentaires_enseignant)
                                <div class="alert alert-warning">
                                    <strong>Directeur de mémoire :</strong> {{ $proposition->commentaires_enseignant }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions pour admin/enseignant -->
                @can('valider', $proposition)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title text-warning">
                                        <i class="fas fa-gavel me-2"></i>
                                        Actions de validation
                                    </h6>

                                    @if(auth()->user()->isAdmin() && $proposition->envoye_a_l_admin)
                                        <form action="{{ route('propositions.valider', $proposition) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm me-2">
                                                <i class="fas fa-check me-1"></i>
                                                Valider (Admin)
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#refuserModal">
                                            <i class="fas fa-times me-1"></i>
                                            Refuser (Admin)
                                        </button>
                                    @endif

                                    @if(auth()->user()->isEnseignant() && $proposition->envoye_au_directeur && auth()->id() === $proposition->directeur_memoire_id)
                                        <form action="{{ route('propositions.valider-enseignant', $proposition) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm me-2">
                                                <i class="fas fa-check me-1"></i>
                                                Valider (Enseignant)
                                            </button>
                                        </form>

                                        <form action="{{ route('propositions.rejeter-enseignant', $proposition) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group input-group-sm d-inline-flex w-auto">
                                                <input type="text" class="form-control" name="commentaires_enseignant" placeholder="Motif du refus" required>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times me-1"></i>
                                                    Refuser
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>

<!-- Modal de refus admin -->
@if(auth()->user()->isAdmin())
<div class="modal fade" id="refuserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refuser la proposition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('propositions.refuser', $proposition) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="commentaires_admin" class="form-label">Motif du refus</label>
                        <textarea class="form-control" id="commentaires_admin" name="commentaires_admin" rows="3" required></textarea>
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
@endsection