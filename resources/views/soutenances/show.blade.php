@extends('layouts.app')

@section('title', 'Détails de la soutenance')
@section('page-title', 'Détails de la soutenance')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-microphone me-2"></i>
                    Soutenance de {{ $soutenance->stage->candidature->etudiant->name }}
                </h5>
                <div>
                    @if(auth()->user()->isAdmin() || auth()->user()->isEnseignant())
                        <a href="{{ route('soutenances.edit', $soutenance) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Modifier
                        </a>
                    @endif
                    <a href="{{ route('soutenances.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Informations générales -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations générales</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de soutenance</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ $soutenance->type }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Date de soutenance</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                @if($soutenance->date_soutenance)
                                    {{ $soutenance->date_soutenance->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Heure</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-clock me-2 text-muted"></i>
                                @if($soutenance->heure_debut)
                                    {{ $soutenance->heure_debut }}
                                    @if($soutenance->heure_fin)
                                        - {{ $soutenance->heure_fin }}
                                    @endif
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lieu</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                @if($soutenance->lieu)
                                    {{ $soutenance->lieu }}
                                @else
                                    <span class="text-muted">Non défini</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations sur l'étudiant</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Étudiant</label>
                            <p class="form-control-plaintext">{{ $soutenance->stage->candidature->etudiant->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <a href="mailto:{{ $soutenance->stage->candidature->etudiant->email }}" class="text-decoration-none">
                                    {{ $soutenance->stage->candidature->etudiant->email }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Stage</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('stages.show', $soutenance->stage) }}" class="text-decoration-none">
                                    {{ $soutenance->stage->candidature->offre->titre }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Entreprise</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('entreprises.show', $soutenance->stage->candidature->offre->entreprise) }}" class="text-decoration-none">
                                    {{ $soutenance->stage->candidature->offre->entreprise->nom }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Statut -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Statut de la soutenance</h6>
                        <div class="d-flex align-items-center">
                            @if($soutenance->statut === 'planifiee')
                                <span class="badge bg-warning fs-6 me-3">
                                    <i class="fas fa-calendar me-1"></i>
                                    Planifiée
                                </span>
                                <small class="text-muted">La soutenance est planifiée et en attente</small>
                            @elseif($soutenance->statut === 'en_cours')
                                <span class="badge bg-info fs-6 me-3">
                                    <i class="fas fa-play me-1"></i>
                                    En cours
                                </span>
                                <small class="text-info">La soutenance est actuellement en cours</small>
                            @elseif($soutenance->statut === 'terminee')
                                <span class="badge bg-success fs-6 me-3">
                                    <i class="fas fa-check me-1"></i>
                                    Terminée
                                </span>
                                <small class="text-success">La soutenance s'est bien déroulée</small>
                            @elseif($soutenance->statut === 'annulee')
                                <span class="badge bg-danger fs-6 me-3">
                                    <i class="fas fa-times me-1"></i>
                                    Annulée
                                </span>
                                <small class="text-danger">La soutenance a été annulée</small>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Jury -->
                @if($soutenance->jury_president || $soutenance->jury_membres)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Composition du jury</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                @if($soutenance->jury_president)
                                    <div class="mb-2">
                                        <strong>Président :</strong> {{ $soutenance->jury_president }}
                                    </div>
                                @endif
                                @if($soutenance->jury_membres)
                                    <div>
                                        <strong>Membres :</strong> {{ $soutenance->jury_membres }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Objectifs -->
                @if($soutenance->objectifs)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Objectifs de la soutenance</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $soutenance->objectifs }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Consignes -->
                @if($soutenance->consignes)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Consignes pour l'étudiant</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $soutenance->consignes }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Résultats -->
                @if($soutenance->note || $soutenance->commentaires_jury)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Résultats de la soutenance</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                @if($soutenance->note)
                                    <div class="mb-3">
                                        <strong>Note :</strong> 
                                        <span class="badge bg-{{ $soutenance->note >= 16 ? 'success' : ($soutenance->note >= 12 ? 'warning' : 'danger') }} fs-6">
                                            {{ $soutenance->note }}/20
                                        </span>
                                    </div>
                                @endif
                                @if($soutenance->commentaires_jury)
                                    <div>
                                        <strong>Commentaires du jury :</strong>
                                        <p class="mb-0 mt-2">{{ $soutenance->commentaires_jury }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($soutenance->statut === 'planifiee')
                        <form method="POST" action="{{ route('soutenances.update', $soutenance) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="statut" value="en_cours">
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-play me-2"></i>
                                Démarrer la soutenance
                            </button>
                        </form>
                    @endif
                    
                    @if($soutenance->statut === 'en_cours')
                        <form method="POST" action="{{ route('soutenances.update', $soutenance) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="statut" value="terminee">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>
                                Terminer la soutenance
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('stages.show', $soutenance->stage) }}" class="btn btn-outline-primary">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Voir le stage
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Informations de contact -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    Contact étudiant
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $soutenance->stage->candidature->etudiant->email }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-2"></i>
                        Envoyer un email
                    </a>
                    @if($soutenance->stage->candidature->etudiant->telephone)
                        <a href="tel:{{ $soutenance->stage->candidature->etudiant->telephone }}" class="btn btn-outline-success">
                            <i class="fas fa-phone me-2"></i>
                            Appeler
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
