@extends('layouts.app')

@section('title', 'Détails de l\'offre')
@section('page-title', 'Détails de l\'offre')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-briefcase me-2"></i>
                    {{ $offre->titre }}
                </h5>
                @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
                    <a href="{{ route('offres.edit', $offre) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Modifier
                    </a>
                @endif
            </div>
            <div class="card-body">
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
                            <p class="form-control-plaintext">{{ $offre->type_stage }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Domaine / Filière concernée</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-graduation-cap me-2 text-muted"></i>
                                {{ $offre->filiere?->nom ?? 'Non renseignée' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Durée</label>
                            <p class="form-control-plaintext">{{ $offre->duree }} mois</p>
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
                                {{ $offre->statut === 'active' ? 'Active' : ($offre->statut === 'inactive' ? 'Inactive' : 'En attente') }}
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

                <!-- Bouton Postuler en bas -->
                @if(auth()->user()->isEtudiant() && $offre->statut === 'active')
                    <div class="d-flex justify-content-end pt-3 border-top">
                        @if($candidatureExistante)
                            <a href="{{ route('candidatures.show', $candidatureExistante) }}" class="btn btn-outline-success">
                                <i class="fas fa-check me-2"></i>Candidature déjà envoyée
                            </a>
                        @else
                            <a href="{{ route('candidatures.create', $offre) }}" class="btn" style="background-color:#0b1f4d;color:#fff;">
                                <i class="fas fa-paper-plane me-2"></i>Postuler à cette offre
                            </a>
                        @endif
                    </div>
                @endif

                @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
                    <div class="d-flex justify-content-end pt-3 border-top">
                        <a href="{{ route('candidatures.index', ['offre_id' => $offre->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>Voir les candidatures
                        </a>
                    </div>
                @endif
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
                                                    <small class="text-muted">{{ $candidature->etudiant->filiere->nom }}</small>
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
