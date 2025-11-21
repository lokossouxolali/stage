@extends('layouts.app')

@section('title', 'Détails de l\'offre')
@section('page-title', 'Détails de l\'offre')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-briefcase me-2"></i>
                    {{ $offre->titre }}
                </h5>
                <div>
                    @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
                        <a href="{{ route('offres.edit', $offre) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Modifier
                        </a>
                    @endif
                    <a href="{{ route('offres.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Informations principales -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations générales</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Entreprise</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-building me-2 text-muted"></i>
                                @if($offre->entreprise)
                                    <a href="{{ route('entreprises.show', $offre->entreprise) }}" class="text-decoration-none">
                                        {{ $offre->entreprise->nom }}
                                    </a>
                                @else
                                    <span class="text-muted">Non spécifiée</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de stage</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info fs-6">{{ $offre->type_stage }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Durée</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                {{ $offre->duree }} mois
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Niveau requis</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-secondary fs-6">{{ $offre->niveau_etude }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Détails pratiques</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <p class="form-control-plaintext">
                                @if($offre->statut === 'active')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check me-1"></i>
                                        Active
                                    </span>
                                @elseif($offre->statut === 'inactive')
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-pause me-1"></i>
                                        Inactive
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>
                                        En attente
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        @if($offre->date_debut)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de début</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-calendar me-2 text-muted"></i>
                                    {{ $offre->date_debut->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif
                        
                        @if($offre->date_fin)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de fin</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-calendar me-2 text-muted"></i>
                                    {{ $offre->date_fin->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif
                        
                        @if($offre->lieu)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Lieu de travail</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                    {{ $offre->lieu }}
                                </p>
                            </div>
                        @endif
                        
                        @if($offre->remuneration)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rémunération</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-money-bill-wave me-2 text-muted"></i>
                                    <span class="text-success fw-bold">{{ $offre->remuneration }}€/mois</span>
                                </p>
                            </div>
                        @endif
                        
                        @if($offre->date_limite_candidature)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date limite candidature</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-clock me-2 text-muted"></i>
                                    {{ $offre->date_limite_candidature->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mb-4">
                    <h6 class="text-muted mb-3">Description du poste</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            <p class="mb-0">{{ $offre->description }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Missions -->
                @if($offre->missions)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Missions principales</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $offre->missions }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Compétences requises -->
                @if($offre->competences_requises)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Compétences requises</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $offre->competences_requises }}</p>
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
                    @if(auth()->user()->isEtudiant() && $offre->statut === 'active')
                        <a href="{{ route('candidatures.create', $offre) }}" 
                           class="btn btn-success">
                            <i class="fas fa-paper-plane me-2"></i>
                            Postuler à cette offre
                        </a>
                    @endif
                    
                    @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
                        <a href="{{ route('candidatures.index', ['offre_id' => $offre->id]) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>
                            Voir les candidatures
                        </a>
                    @endif
                    
                    @if($offre->entreprise)
                        <a href="{{ route('entreprises.show', $offre->entreprise) }}" 
                           class="btn btn-outline-info">
                            <i class="fas fa-building me-2"></i>
                            Voir l'entreprise
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="card">
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
                            <h4 class="text-primary">{{ $offre->candidatures->count() }}</h4>
                            <small class="text-muted">Candidatures</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $offre->nombre_places }}</h4>
                        <small class="text-muted">Places disponibles</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Candidatures pour cette offre -->
@if($offre->candidatures->count() > 0 && (auth()->user()->isEntreprise() || auth()->user()->isAdmin()))
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    Candidatures reçues ({{ $offre->candidatures->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Email</th>
                                <th>Niveau</th>
                                <th>Date candidature</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offre->candidatures as $candidature)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($candidature->etudiant->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $candidature->etudiant->name }}</div>
                                                @if($candidature->etudiant->filiere)
                                                    <small class="text-muted">{{ $candidature->etudiant->filiere }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $candidature->etudiant->email }}" class="text-decoration-none">
                                            {{ $candidature->etudiant->email }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $candidature->etudiant->niveau_etude }}</span>
                                    </td>
                                    <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($candidature->statut === 'en_attente')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($candidature->statut === 'acceptee')
                                            <span class="badge bg-success">Acceptée</span>
                                        @elseif($candidature->statut === 'refusee')
                                            <span class="badge bg-danger">Refusée</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('candidatures.show', $candidature) }}" 
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
