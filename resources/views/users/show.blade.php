@extends('layouts.app')

@section('title', 'Détails de l\'Utilisateur')
@section('page-title', 'Détails de l\'Utilisateur')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        {{ $user->name }}
                    </h5>
                    <div>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        @if($user->photo_path && $user->photo_url)
                            <img src="{{ $user->photo_url }}" alt="Photo de profil" 
                                 class="rounded-circle mx-auto mb-3 d-block" 
                                 style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="display: none;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @else
                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <h5>{{ $user->name }}</h5>
                        @switch($user->role)
                            @case('admin')
                                <span class="badge bg-danger fs-6">Administrateur</span>
                                @break
                            @case('etudiant')
                                <span class="badge bg-primary fs-6">Étudiant</span>
                                @break
                            @case('entreprise')
                                <span class="badge bg-success fs-6">Entreprise</span>
                                @break
                            @case('enseignant')
                                <span class="badge bg-warning fs-6">Enseignant</span>
                                @break
                            @default
                                <span class="badge bg-secondary fs-6">{{ ucfirst($user->role) }}</span>
                        @endswitch
                    </div>
                    
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="text-muted">{{ $user->email }}</p>
                            </div>
                            
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Rôle</label>
                                <p class="text-muted">{{ ucfirst($user->role) }}</p>
                            </div>
                            
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Créé le</label>
                                <p class="text-muted">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Dernière modification</label>
                                <p class="text-muted">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($user->role == 'etudiant' && $user->candidatures)
                    <hr>
                    <h6 class="mb-3">
                        <i class="fas fa-file-alt me-2"></i>
                        Candidatures ({{ $user->candidatures->count() }})
                    </h6>
                    @if($user->candidatures->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Offre</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->candidatures as $candidature)
                                        <tr>
                                            <td>{{ $candidature->offre->titre ?? 'N/A' }}</td>
                                            <td>
                                                @switch($candidature->statut)
                                                    @case('en_attente')
                                                        <span class="badge bg-warning">En attente</span>
                                                        @break
                                                    @case('acceptee')
                                                        <span class="badge bg-success">Acceptée</span>
                                                        @break
                                                    @case('refusee')
                                                        <span class="badge bg-danger">Refusée</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($candidature->statut) }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucune candidature</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-lg {
        width: 80px;
        height: 80px;
        font-size: 2rem;
        font-weight: 600;
    }
</style>
@endpush
