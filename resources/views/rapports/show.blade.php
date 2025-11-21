@extends('layouts.app')

@section('title', 'Détails du rapport')
@section('page-title', 'Détails du rapport')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-pdf me-2"></i>
                    {{ $rapport->titre }}
                </h5>
                <div>
                    @if(auth()->user()->isEtudiant() && $rapport->statut === 'rejete')
                        <a href="{{ route('rapports.edit', $rapport) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Modifier
                        </a>
                    @endif
                    <a href="{{ route('rapports.index') }}" class="btn btn-secondary btn-sm">
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
                            <label class="form-label fw-bold">Destinataire</label>
                            <p class="form-control-plaintext">
                                @switch($rapport->destinataire)
                                    @case('admin')
                                        <span class="badge bg-danger">Administrateur</span>
                                        @break
                                    @case('directeur_memoire')
                                        <span class="badge bg-primary">Directeur de mémoire</span>
                                        @break
                                    @case('les_deux')
                                        <span class="badge bg-info">Admin + Directeur</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Date de soumission</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                {{ $rapport->date_soumission->format('d/m/Y') }}
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <p class="form-control-plaintext">
                                @if($rapport->statut === 'soumis')
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>
                                        Soumis
                                    </span>
                                @elseif($rapport->statut === 'valide')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check me-1"></i>
                                        Validé
                                    </span>
                                @elseif($rapport->statut === 'rejete')
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-times me-1"></i>
                                        Rejeté
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations sur l'étudiant</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Étudiant</label>
                            <p class="form-control-plaintext">{{ $rapport->etudiant->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                @if($rapport->etudiant)
                                    <a href="mailto:{{ $rapport->etudiant->email }}" class="text-decoration-none">
                                        {{ $rapport->etudiant->email }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de rapport</label>
                            <p class="form-control-plaintext">
                                @if($rapport->type_rapport === 'memoire')
                                    <span class="badge" style="background-color: #2d3748; color: #ffffff;">Mémoire</span>
                                @elseif($rapport->type_rapport === 'proposition_theme')
                                    <span class="badge" style="background-color: #2d3748; color: #ffffff;">Proposition de thème</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </p>
                        </div>
                        
                        @if($rapport->stage && $rapport->stage->candidature)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Stage</label>
                                <p class="form-control-plaintext">
                                    <span class="text-decoration-none">
                                        {{ $rapport->stage->candidature->offre->titre ?? 'N/A' }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Entreprise</label>
                                <p class="form-control-plaintext">
                                    @if($rapport->stage->candidature->offre->entreprise)
                                        <a href="{{ route('entreprises.show', $rapport->stage->candidature->offre->entreprise) }}" class="text-decoration-none">
                                            {{ $rapport->stage->candidature->offre->entreprise->nom }}
                                        </a>
                                    @else
                                        <span class="text-muted">Non spécifiée</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($rapport->commentaires)
                    <!-- Commentaires -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Commentaires supplémentaires</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $rapport->commentaires }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Commentaires des encadreurs -->
                @if($rapport->commentaires_encadreur_academique || $rapport->commentaires_encadreur_entreprise)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Commentaires des encadreurs</h6>
                        
                        @if($rapport->commentaires_encadreur_academique)
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Commentaire de l'encadreur académique
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $rapport->commentaires_encadreur_academique }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($rapport->commentaires_encadreur_entreprise)
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-building me-2"></i>
                                    Commentaire de l'encadreur entreprise
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $rapport->commentaires_encadreur_entreprise }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Formulaire pour ajouter un commentaire -->
                @if((auth()->user()->isEnseignant() || auth()->user()->isEntreprise()) && in_array($rapport->statut, ['soumis', 'en_revision', 'valide']))
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Ajouter un commentaire</h6>
                        <form method="POST" action="{{ route('rapports.commentaire', $rapport) }}">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control" 
                                          name="commentaire" 
                                          rows="4" 
                                          placeholder="Ajoutez vos commentaires sur ce rapport...">{{ old('commentaire') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-comment me-2"></i>
                                Enregistrer le commentaire
                            </button>
                        </form>
                    </div>
                @endif
                
                <!-- Mots-clés et technologies -->
                @if($rapport->mots_cles || $rapport->technologies_utilisees)
                    <div class="row mb-4">
                        @if($rapport->mots_cles)
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Mots-clés</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach(explode(',', $rapport->mots_cles) as $mot)
                                        <span class="badge bg-secondary">{{ trim($mot) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        @if($rapport->technologies_utilisees)
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Technologies utilisées</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach(explode(',', $rapport->technologies_utilisees) as $tech)
                                        <span class="badge bg-primary">{{ trim($tech) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Difficultés rencontrées -->
                @if($rapport->difficultes_rencontrees)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Difficultés rencontrées</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $rapport->difficultes_rencontrees }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Compétences acquises -->
                @if($rapport->acquis_competences)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Compétences acquises</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $rapport->acquis_competences }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Commentaires -->
                @if($rapport->commentaires)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Commentaires</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $rapport->commentaires }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Fichier du rapport -->
                @if($rapport->fichier_path)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Fichier du rapport</h6>
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-3x mb-3" style="color: #2d3748;"></i>
                                <div class="fw-bold mb-2">Rapport PDF</div>
                                <a href="{{ route('rapports.download', $rapport) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-download me-2"></i>
                                    Télécharger le rapport
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Actions pour les encadreurs -->
        @if(auth()->user()->isAdmin() || auth()->user()->isEnseignant() || auth()->user()->isEntreprise())
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Actions encadrement
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(in_array($rapport->statut, ['soumis', 'en_revision']))
                            @if(auth()->user()->isAdmin() || auth()->user()->isEnseignant())
                                <form method="POST" action="{{ route('rapports.valider', $rapport) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check me-2"></i>
                                        Valider le rapport
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('rapports.rejeter', $rapport) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter ce rapport ?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-times me-2"></i>
                                        Rejeter le rapport
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Ce rapport a déjà été traité
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
                    Contact étudiant
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($rapport->etudiant)
                        <a href="mailto:{{ $rapport->etudiant->email }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>
                            Envoyer un email
                        </a>
                        @if($rapport->etudiant->telephone)
                            <a href="tel:{{ $rapport->etudiant->telephone }}" class="btn btn-outline-success">
                                <i class="fas fa-phone me-2"></i>
                                Appeler
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
