@extends('layouts.app')

@section('title', 'Détails de l\'évaluation')
@section('page-title', 'Détails de l\'évaluation')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2"></i>
                    Évaluation de {{ $evaluation->stage->candidature->etudiant->name }}
                </h5>
                <div>
                    @if(auth()->user()->isAdmin() || $evaluation->evaluateur_id === auth()->id())
                        <a href="{{ route('evaluations.edit', $evaluation) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Modifier
                        </a>
                    @endif
                    <a href="{{ route('evaluations.index') }}" class="btn btn-secondary btn-sm">
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
                            <label class="form-label fw-bold">Type d'évaluation</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ $evaluation->type }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Date d'évaluation</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                {{ $evaluation->date_evaluation->format('d/m/Y') }}
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <p class="form-control-plaintext">
                                @if($evaluation->statut === 'en_cours')
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>
                                        En cours
                                    </span>
                                @elseif($evaluation->statut === 'terminee')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check me-1"></i>
                                        Terminée
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informations sur l'étudiant</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Étudiant</label>
                            <p class="form-control-plaintext">{{ $evaluation->stage->candidature->etudiant->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <a href="mailto:{{ $evaluation->stage->candidature->etudiant->email }}" class="text-decoration-none">
                                    {{ $evaluation->stage->candidature->etudiant->email }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Stage</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('stages.show', $evaluation->stage) }}" class="text-decoration-none">
                                    {{ $evaluation->stage->candidature->offre->titre }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Entreprise</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('entreprises.show', $evaluation->stage->candidature->offre->entreprise) }}" class="text-decoration-none">
                                    {{ $evaluation->stage->candidature->offre->entreprise->nom }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Note globale -->
                @if($evaluation->note)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted mb-3">Note globale</h6>
                            <div class="text-center">
                                <div class="display-4 fw-bold text-{{ $evaluation->note >= 16 ? 'success' : ($evaluation->note >= 12 ? 'warning' : 'danger') }}">
                                    {{ $evaluation->note }}/20
                                </div>
                                <div class="progress mt-3" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $evaluation->note >= 16 ? 'success' : ($evaluation->note >= 12 ? 'warning' : 'danger') }}" 
                                         role="progressbar" 
                                         style="width: {{ ($evaluation->note / 20) * 100 }}%">
                                        {{ $evaluation->note }}/20
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Critères d'évaluation -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Critères d'évaluation</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-code me-2" style="color: #2d3748;"></i>
                                                <strong>Compétence technique</strong>
                                            </div>
                                            <div class="text-end">
                                                @if($evaluation->competence_technique)
                                                    <div class="fw-bold">{{ $evaluation->competence_technique }}/5</div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-primary" 
                                                             style="width: {{ ($evaluation->competence_technique / 5) * 100 }}%"></div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Non évalué</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-user-check me-2" style="color: #2d3748;"></i>
                                                <strong>Autonomie</strong>
                                            </div>
                                            <div class="text-end">
                                                @if($evaluation->autonomie)
                                                    <div class="fw-bold">{{ $evaluation->autonomie }}/5</div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" 
                                                             style="width: {{ ($evaluation->autonomie / 5) * 100 }}%"></div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Non évalué</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-users me-2" style="color: #2d3748;"></i>
                                                <strong>Travail en équipe</strong>
                                            </div>
                                            <div class="text-end">
                                                @if($evaluation->travail_equipe)
                                                    <div class="fw-bold">{{ $evaluation->travail_equipe }}/5</div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-info" 
                                                             style="width: {{ ($evaluation->travail_equipe / 5) * 100 }}%"></div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Non évalué</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-comments me-2" style="color: #2d3748;"></i>
                                                <strong>Communication</strong>
                                            </div>
                                            <div class="text-end">
                                                @if($evaluation->communication)
                                                    <div class="fw-bold">{{ $evaluation->communication }}/5</div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" 
                                                             style="width: {{ ($evaluation->communication / 5) * 100 }}%"></div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Non évalué</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Commentaires -->
                @if($evaluation->commentaires)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Commentaires généraux</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $evaluation->commentaires }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Recommandations -->
                @if($evaluation->recommandations)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Recommandations</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $evaluation->recommandations }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Informations sur l'évaluateur -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    Évaluateur
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        {{ substr($evaluation->evaluateur->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ $evaluation->evaluateur->name }}</div>
                        <small class="text-muted">{{ ucfirst($evaluation->evaluateur->role) }}</small>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $evaluation->evaluateur->email }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-2"></i>
                        Envoyer un email
                    </a>
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
                            <h4 class="text-primary">{{ $evaluation->stage->rapports->count() }}</h4>
                            <small class="text-muted">Rapports</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $evaluation->stage->evaluations->count() }}</h4>
                        <small class="text-muted">Évaluations</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
