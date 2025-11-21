@extends('layouts.app')

@section('title', 'Demandes d\'encadrement')
@section('page-title', 'Demandes d\'encadrement')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-graduate me-2"></i>
                    Demandes d'encadrement de mémoire
                </h5>
            </div>
            <div class="card-body">
                @if($demandes->count() > 0)
                    <div class="row g-4">
                        @foreach($demandes as $etudiant)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <!-- Photo de profil -->
                                        <div class="text-center mb-3">
                                            @if($etudiant->photo_path && $etudiant->photo_url)
                                                <img src="{{ $etudiant->photo_url }}" 
                                                     alt="{{ $etudiant->name }}" 
                                                     class="rounded-circle mx-auto d-block"
                                                     style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #2d3748; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="mx-auto" style="display: none; width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; border: 3px solid #2d3748;">
                                                    {{ strtoupper(substr($etudiant->name, 0, 1)) }}
                                                </div>
                                            @else
                                                <div class="mx-auto" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; border: 3px solid #2d3748;">
                                                    {{ strtoupper(substr($etudiant->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Nom -->
                                        <h6 class="card-title text-center mb-2 fw-bold" style="color: #2d3748;">
                                            {{ $etudiant->name }}
                                        </h6>

                                        <!-- Email -->
                                        <p class="text-muted text-center small mb-2">
                                            <i class="fas fa-envelope me-1" style="color: #2d3748;"></i>
                                            {{ $etudiant->email }}
                                        </p>

                                        <!-- Informations académiques -->
                                        @if($etudiant->niveau_etude || $etudiant->filiere)
                                            <div class="mb-3">
                                                @if($etudiant->niveau_etude)
                                                    <p class="text-muted small mb-1">
                                                        <i class="fas fa-graduation-cap me-1" style="color: #2d3748;"></i>
                                                        Niveau : {{ $etudiant->niveau_etude }}
                                                    </p>
                                                @endif
                                                @if($etudiant->filiere)
                                                    <p class="text-muted small mb-0">
                                                        <i class="fas fa-book me-1" style="color: #2d3748;"></i>
                                                        Filière : {{ $etudiant->filiere }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Téléphone si disponible -->
                                        @if($etudiant->telephone)
                                            <p class="text-muted text-center small mb-3">
                                                <i class="fas fa-phone me-1" style="color: #2d3748;"></i>
                                                {{ $etudiant->telephone }}
                                            </p>
                                        @endif

                                        <!-- Badge statut -->
                                        <div class="text-center mb-3">
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                En attente
                                            </span>
                                        </div>

                                        <!-- Actions -->
                                        <div class="d-grid gap-2">
                                            <form method="POST" action="{{ route('demandes-encadrement.accepter', $etudiant) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm w-100" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;">
                                                    <i class="fas fa-check me-2"></i>
                                                    Accepter
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-sm btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#refuserModal{{ $etudiant->id }}">
                                                <i class="fas fa-times me-2"></i>
                                                Refuser
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de refus -->
                            <div class="modal fade" id="refuserModal{{ $etudiant->id }}" tabindex="-1" aria-labelledby="refuserModalLabel{{ $etudiant->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #2d3748; color: #ffffff;">
                                            <h5 class="modal-title" id="refuserModalLabel{{ $etudiant->id }}">
                                                <i class="fas fa-times-circle me-2"></i>Refuser la demande
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST" action="{{ route('demandes-encadrement.refuser', $etudiant) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Vous êtes sur le point de refuser la demande d'encadrement de <strong>{{ $etudiant->name }}</strong>.</p>
                                                <div class="mb-3">
                                                    <label for="raison{{ $etudiant->id }}" class="form-label">Raison du refus *</label>
                                                    <textarea class="form-control" id="raison{{ $etudiant->id }}" name="raison" rows="4" required placeholder="Veuillez indiquer la raison du refus..."></textarea>
                                                    <small class="text-muted">Cette raison sera communiquée à l'étudiant par email et notification.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times me-2"></i>Confirmer le refus
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune demande en attente</h5>
                        <p class="text-muted">Vous n'avez actuellement aucune demande d'encadrement en attente de traitement.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

