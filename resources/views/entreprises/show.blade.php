@extends('layouts.app')

@section('title', 'Détails de l\'entreprise')
@section('page-title', 'Détails de l\'entreprise')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building me-2"></i>
                    {{ $entreprise->nom }}
                </h5>
                <div>
                    <a href="{{ route('entreprises.edit', $entreprise) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>
                        Modifier
                    </a>
                    <a href="{{ route('entreprises.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations générales</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom de l'entreprise</label>
                            <p class="form-control-plaintext">{{ $entreprise->nom }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-envelope me-2 text-muted"></i>
                                <a href="mailto:{{ $entreprise->email }}" class="text-decoration-none">
                                    {{ $entreprise->email }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Téléphone</label>
                            <p class="form-control-plaintext">
                                @if($entreprise->telephone)
                                    <i class="fas fa-phone me-2 text-muted"></i>
                                    <a href="tel:{{ $entreprise->telephone }}" class="text-decoration-none">
                                        {{ $entreprise->telephone }}
                                    </a>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Site web</label>
                            <p class="form-control-plaintext">
                                @if($entreprise->site_web)
                                    <i class="fas fa-globe me-2 text-muted"></i>
                                    <a href="{{ $entreprise->site_web }}" target="_blank" class="text-decoration-none">
                                        {{ $entreprise->site_web }}
                                    </a>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Détails</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Secteur d'activité</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info fs-6">{{ $entreprise->secteur_activite }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut de vérification</label>
                            <p class="form-control-plaintext">
                                @if($entreprise->est_verifiee)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check me-1"></i>
                                        Vérifiée
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>
                                        En attente de vérification
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Adresse</label>
                            <p class="form-control-plaintext">
                                @if($entreprise->adresse)
                                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                    {{ $entreprise->adresse }}
                                @else
                                    <span class="text-muted">Non renseignée</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($entreprise->description)
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Description</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $entreprise->description }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
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
                            <h4 class="text-primary">{{ $entreprise->users->count() }}</h4>
                            <small class="text-muted">Utilisateurs</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $entreprise->offres->count() }}</h4>
                        <small class="text-muted">Offres</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Actions rapides
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('offres.create', ['entreprise_id' => $entreprise->id]) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Créer une offre
                    </a>
                    <a href="{{ route('users.create', ['entreprise_id' => $entreprise->id]) }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Ajouter un utilisateur
                    </a>
                    <a href="mailto:{{ $entreprise->email }}" 
                       class="btn btn-outline-success">
                        <i class="fas fa-envelope me-2"></i>
                        Envoyer un email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offres de l'entreprise -->
@if($entreprise->offres->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-briefcase me-2"></i>
                    Offres de stage ({{ $entreprise->offres->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Durée</th>
                                <th>Date début</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entreprise->offres as $offre)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $offre->titre }}</div>
                                        <small class="text-muted">{{ Str::limit($offre->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $offre->type_stage }}</span>
                                    </td>
                                    <td>{{ $offre->duree }} mois</td>
                                    <td>{{ $offre->date_debut ? $offre->date_debut->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($offre->statut === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($offre->statut === 'inactive')
                                            <span class="badge bg-secondary">Inactive</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('offres.show', $offre) }}" 
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

<!-- Utilisateurs de l'entreprise -->
@if($entreprise->users->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>
                    Utilisateurs ({{ $entreprise->users->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($entreprise->users as $user)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                            <div>
                                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
