@extends('layouts.app')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 text-muted fw-normal">
                    <i class="fas fa-user me-2"></i>
                    Mon Profil
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-start">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        @if(auth()->user()->photo_path && auth()->user()->photo_url)
                            <img src="{{ auth()->user()->photo_url }}" alt="Photo de profil" 
                                 class="rounded-circle mx-auto mb-3 d-block" 
                                 style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="profile-avatar mx-auto mb-3" style="display: none;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @else
                            <div class="profile-avatar mx-auto mb-3">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <h6 class="mb-2 fw-semibold" style="font-size: 0.9rem;">{{ auth()->user()->name }}</h6>
                        @switch(auth()->user()->role)
                            @case('admin')
                                <span class="role-badge role-admin">ADMINISTRATEUR</span>
                                @break
                            @case('etudiant')
                                <span class="role-badge role-etudiant">ÉTUDIANT</span>
                                @break
                            @case('entreprise')
                                <span class="role-badge role-entreprise">ENTREPRISE</span>
                                @break
                            @case('enseignant')
                                <span class="role-badge role-enseignant">ENSEIGNANT</span>
                                @break
                            @case('responsable_stages')
                                <span class="role-badge role-responsable">RESPONSABLE</span>
                                @break
                            @default
                                <span class="role-badge role-default">{{ strtoupper(auth()->user()->role) }}</span>
                        @endswitch
                    </div>
                    
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Nom complet</label>
                                <p class="mb-0" style="font-size: 0.85rem; color: #1f2937;">{{ auth()->user()->name }}</p>
                            </div>
                            
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Email</label>
                                <p class="mb-0" style="font-size: 0.85rem; color: #1f2937;">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Rôle</label>
                                <p class="mb-0" style="font-size: 0.85rem; color: #1f2937;">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                            
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Membre depuis</label>
                                <p class="mb-0" style="font-size: 0.85rem; color: #1f2937;">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                            </div>

                            @if(auth()->user()->telephone)
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Téléphone</label>
                                <p class="mb-0" style="font-size: 0.85rem; color: #1f2937;">{{ auth()->user()->telephone }}</p>
                            </div>
                            @endif

                            @if(auth()->user()->isEtudiant())
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Niveau d'étude</label>
                                    <p class="mb-0" style="font-size: 0.85rem; color: #1f2937;">{{ auth()->user()->niveau_etude ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Filière</label>
                                    <p class="mb-0" style="font-size: 0.85rem; color: #1f2937;">{{ auth()->user()->filiere ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Directeur de mémoire</label>
                                    @if(auth()->user()->directeur_memoire_id)
                                        <p class="mb-0" style="font-size: 0.85rem; color: #1f2937;">
                                            <i class="fas fa-user-tie me-2" style="color: #6c757d;"></i>
                                            {{ auth()->user()->directeurMemoire->name ?? 'Non défini' }}
                                            @if(auth()->user()->statut_demande_dm === 'en_attente')
                                                <span class="badge bg-warning ms-2" style="font-size: 0.7rem;">
                                                    <i class="fas fa-clock me-1"></i>En attente
                                                </span>
                                            @elseif(auth()->user()->statut_demande_dm === 'accepte')
                                                <span class="badge bg-success ms-2" style="font-size: 0.7rem;">
                                                    <i class="fas fa-check me-1"></i>Accepté
                                                </span>
                                            @elseif(auth()->user()->statut_demande_dm === 'refuse')
                                                <span class="badge bg-danger ms-2" style="font-size: 0.7rem;">
                                                    <i class="fas fa-times me-1"></i>Refusé
                                                </span>
                                                @if(auth()->user()->raison_refus_dm)
                                                    <div class="mt-2 p-2" style="background-color: #fee2e2; border-left: 3px solid #dc2626; border-radius: 4px;">
                                                        <small style="font-size: 0.75rem; color: #991b1b;">
                                                            <strong>Raison :</strong> {{ auth()->user()->raison_refus_dm }}
                                                        </small>
                                                    </div>
                                                @endif
                                            @endif
                                        </p>
                                    @else
                                        <p class="mb-0" style="font-size: 0.85rem; color: #9ca3af;">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            Aucun directeur de mémoire sélectionné
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('profile.edit') }}" class="btn btn-dark btn-sm">
                                <i class="fas fa-edit me-1"></i>
                                Modifier le profil
                            </a>
                            <a href="{{ route('profile.password') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-key me-1"></i>
                                Changer le mot de passe
                            </a>
                            @if(auth()->user()->isEtudiant())
                                <a href="{{ route('users.choisir-directeur-memoire') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-user-tie me-1"></i>
                                    Choisir directeur de mémoire
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .role-badge {
        display: inline-block;
        padding: 0.3rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .role-admin {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .role-etudiant {
        background-color: #1f2937;
        color: #ffffff;
    }

    .role-entreprise {
        background-color: #d1fae5;
        color: #059669;
    }

    .role-enseignant {
        background-color: #fed7aa;
        color: #ea580c;
    }

    .role-responsable {
        background-color: #e5e7eb;
        color: #374151;
    }

    .role-default {
        background-color: #f3f4f6;
        color: #6b7280;
    }

    .card {
        border: 1px solid #e5e7eb;
    }

    .btn-dark {
        background-color: #1f2937;
        border-color: #1f2937;
        color: #ffffff;
    }

    .btn-dark:hover {
        background-color: #111827;
        border-color: #111827;
        color: #ffffff;
    }

    .btn-outline-secondary {
        border-color: #d1d5db;
        color: #6b7280;
    }

    .btn-outline-secondary:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
        color: #374151;
    }
</style>
@endpush
