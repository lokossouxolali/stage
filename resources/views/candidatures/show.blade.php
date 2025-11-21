@extends('layouts.app')

@section('title', 'Détails de la candidature')
@section('page-title', 'Détails de la candidature')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Candidature pour {{ $candidature->offre->titre }}
                </h5>
                <div>
                    @if(auth()->user()->isEtudiant() && $candidature->statut === 'en_attente')
                        <a href="{{ route('candidatures.edit', $candidature) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Modifier
                        </a>
                    @endif
                    <a href="{{ route('candidatures.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Informations sur l'offre -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations sur l'offre</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Titre du stage</label>
                            <p class="form-control-plaintext">{{ $candidature->offre->titre }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Entreprise</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('entreprises.show', $candidature->offre->entreprise) }}" class="text-decoration-none">
                                    {{ $candidature->offre->entreprise->nom }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de stage</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ $candidature->offre->type_stage }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Durée</label>
                            <p class="form-control-plaintext">{{ $candidature->offre->duree }} mois</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations candidat</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Étudiant</label>
                            <p class="form-control-plaintext">{{ $candidature->etudiant->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <a href="mailto:{{ $candidature->etudiant->email }}" class="text-decoration-none">
                                    {{ $candidature->etudiant->email }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Niveau d'étude</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-secondary">{{ $candidature->etudiant->niveau_etude }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Filière</label>
                            <p class="form-control-plaintext">{{ $candidature->etudiant->filiere ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Statut de la candidature -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Statut de la candidature</h6>
                        <div class="d-flex align-items-center">
                            @if($candidature->statut === 'en_attente')
                                <span class="badge bg-warning fs-6 me-3">
                                    <i class="fas fa-clock me-1"></i>
                                    En attente
                                </span>
                                <small class="text-muted">Votre candidature est en cours d'évaluation</small>
                            @elseif($candidature->statut === 'acceptee')
                                <span class="badge bg-success fs-6 me-3">
                                    <i class="fas fa-check me-1"></i>
                                    Acceptée
                                </span>
                                <small class="text-success">Félicitations ! Votre candidature a été acceptée</small>
                            @elseif($candidature->statut === 'refusee')
                                <span class="badge bg-danger fs-6 me-3">
                                    <i class="fas fa-times me-1"></i>
                                    Refusée
                                </span>
                                <small class="text-danger">Votre candidature n'a pas été retenue</small>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Lettre de motivation -->
                <div class="mb-4">
                    <h6 class="text-muted mb-3">Lettre de motivation</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            <p class="mb-0">{{ $candidature->lettre_motivation }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Documents joints -->
                @if($candidature->cv_path || $candidature->lettre_recommandation || $candidature->portfolio)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Documents joints</h6>
                        <div class="row">
                            @if($candidature->cv_path)
                                <div class="col-md-4 mb-2">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-pdf fa-2x mb-2" style="color: #2d3748;"></i>
                                            <div class="fw-bold">CV</div>
                                            <a href="{{ Storage::url($candidature->cv_path) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-1"></i>
                                                Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($candidature->lettre_recommandation)
                                <div class="col-md-4 mb-2">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-pdf fa-2x mb-2" style="color: #2d3748;"></i>
                                            <div class="fw-bold">Lettre de recommandation</div>
                                            <a href="{{ Storage::url($candidature->lettre_recommandation) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-1"></i>
                                                Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($candidature->portfolio)
                                <div class="col-md-4 mb-2">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="fas fa-link fa-2x mb-2" style="color: #2d3748;"></i>
                                            <div class="fw-bold">Portfolio</div>
                                            <a href="{{ $candidature->portfolio }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt me-1"></i>
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Informations supplémentaires -->
                @if($candidature->disponibilite || $candidature->commentaires)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Informations supplémentaires</h6>
                        
                        @if($candidature->disponibilite)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Disponibilité</label>
                                <p class="form-control-plaintext">{{ $candidature->disponibilite }}</p>
                            </div>
                        @endif
                        
                        @if($candidature->commentaires)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Commentaires</label>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $candidature->commentaires }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Actions pour l'entreprise -->
        @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Actions entreprise
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($candidature->statut === 'en_attente')
                            <form method="POST" action="{{ route('candidatures.update', $candidature) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="statut" value="acceptee">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check me-2"></i>
                                    Accepter la candidature
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('candidatures.update', $candidature) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="statut" value="refusee">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-times me-2"></i>
                                    Refuser la candidature
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Cette candidature a déjà été traitée
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Informations de contact -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    Contact candidat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $candidature->etudiant->email }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-2"></i>
                        Envoyer un email
                    </a>
                    @if($candidature->etudiant->telephone)
                        <a href="tel:{{ $candidature->etudiant->telephone }}" class="btn btn-outline-success">
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
