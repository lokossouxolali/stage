@extends('layouts.app')

@section('title', 'Détails du stage')
@section('page-title', 'Détails du stage')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Stage de {{ $stage->candidature->etudiant->name }}
                </h5>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Informations générales -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations sur l'étudiant</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom</label>
                            <p class="form-control-plaintext">{{ $stage->candidature->etudiant->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <a href="mailto:{{ $stage->candidature->etudiant->email }}" class="text-decoration-none">
                                    {{ $stage->candidature->etudiant->email }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Niveau d'étude</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-secondary">{{ $stage->candidature->etudiant->niveau_etude }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Filière</label>
                            <p class="form-control-plaintext">{{ $stage->candidature->etudiant->filiere ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations sur l'entreprise</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Entreprise</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('entreprises.show', $stage->candidature->offre->entreprise) }}" class="text-decoration-none">
                                    {{ $stage->candidature->offre->entreprise->nom }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Secteur d'activité</label>
                            <p class="form-control-plaintext">{{ $stage->candidature->offre->entreprise->secteur_activite }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de stage</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ $stage->candidature->offre->type_stage }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Titre du stage</label>
                            <p class="form-control-plaintext">{{ $stage->candidature->offre->titre }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Période du stage -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Période du stage</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Date de début</label>
                            <p class="form-control-plaintext">
                                @if($stage->date_debut)
                                    <i class="fas fa-calendar me-2 text-muted"></i>
                                    {{ $stage->date_debut->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Date de fin</label>
                            <p class="form-control-plaintext">
                                @if($stage->date_fin)
                                    <i class="fas fa-calendar me-2 text-muted"></i>
                                    {{ $stage->date_fin->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Statut et encadrement</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <p class="form-control-plaintext">
                                @if($stage->statut === 'en_cours')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-play me-1"></i>
                                        En cours
                                    </span>
                                @elseif($stage->statut === 'termine')
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-check me-1"></i>
                                        Terminé
                                    </span>
                                @elseif($stage->statut === 'annule')
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-times me-1"></i>
                                        Annulé
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        @if($stage->encadreurEntreprise)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Encadreur entreprise</label>
                                <p class="form-control-plaintext">{{ $stage->encadreurEntreprise->name }}</p>
                            </div>
                        @endif
                        
                        @if($stage->encadreurAcademique)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Encadreur académique</label>
                                <p class="form-control-plaintext">{{ $stage->encadreurAcademique->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Objectifs -->
                @if($stage->objectifs)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Objectifs du stage</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $stage->objectifs }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Planning -->
                @if($stage->planning)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Planning</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $stage->planning }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Commentaires -->
                <div class="row mb-4">
                    @if($stage->commentaires_encadreur_entreprise)
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Commentaires encadreur entreprise</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $stage->commentaires_encadreur_entreprise }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($stage->commentaires_encadreur_academique)
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Commentaires encadreur académique</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $stage->commentaires_encadreur_academique }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($stage->commentaires_etudiant)
                        <div class="col-12">
                            <h6 class="text-muted mb-3">Commentaires de l'étudiant</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $stage->commentaires_etudiant }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Actions rapides -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Actions rapides
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('rapports.index', ['stage_id' => $stage->id]) }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-file-pdf me-2"></i>
                        Voir les rapports
                    </a>
                    <a href="{{ route('evaluations.index', ['stage_id' => $stage->id]) }}" 
                       class="btn btn-outline-success">
                        <i class="fas fa-star me-2"></i>
                        Voir les évaluations
                    </a>
                    <a href="{{ route('soutenances.index', ['stage_id' => $stage->id]) }}" 
                       class="btn btn-outline-info">
                        <i class="fas fa-microphone me-2"></i>
                        Voir les soutenances
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary">{{ $stage->rapports->count() }}</h4>
                            <small class="text-muted">Rapports</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $stage->evaluations->count() }}</h4>
                        <small class="text-muted">Évaluations</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    Contact étudiant
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $stage->candidature->etudiant->email }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-2"></i>
                        Envoyer un email
                    </a>
                    @if($stage->candidature->etudiant->telephone)
                        <a href="tel:{{ $stage->candidature->etudiant->telephone }}" class="btn btn-outline-success">
                            <i class="fas fa-phone me-2"></i>
                            Appeler
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rapports du stage -->
@if($stage->rapports->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-pdf me-2"></i>
                    Rapports du stage ({{ $stage->rapports->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Date de soumission</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stage->rapports as $rapport)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $rapport->titre }}</div>
                                        <small class="text-muted">{{ Str::limit($rapport->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $rapport->type }}</span>
                                    </td>
                                    <td>{{ $rapport->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($rapport->statut === 'soumis')
                                            <span class="badge bg-warning">Soumis</span>
                                        @elseif($rapport->statut === 'valide')
                                            <span class="badge bg-success">Validé</span>
                                        @elseif($rapport->statut === 'rejete')
                                            <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('rapports.show', $rapport) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
