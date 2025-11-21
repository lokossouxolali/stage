@extends('layouts.app')

@section('title', 'Choisir un directeur de mémoire')
@section('page-title', 'Choisir un directeur de mémoire')

@section('content')
<div class="row">
    <div class="col-12">
        @if(auth()->user()->directeur_memoire_id)
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Vous avez déjà sélectionné un directeur de mémoire : 
                <strong>{{ auth()->user()->directeurMemoire->name }}</strong>
                <span class="text-muted">({{ auth()->user()->directeurMemoire->email }})</span>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-user-tie me-2"></i>
                Sélectionner votre directeur de mémoire
            </h5>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-2"></i>
                Retour
            </a>
        </div>

        @if($enseignants->count() > 0)
            <div class="row g-4">
                @foreach($enseignants as $enseignant)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm teacher-card {{ auth()->user()->directeur_memoire_id == $enseignant->id ? 'border-primary' : '' }}">
                            <div class="card-body text-center p-4">
                                <!-- Photo de profil -->
                                <div class="mb-3">
                                    @if($enseignant->photo_path && $enseignant->photo_url)
                                        <img src="{{ $enseignant->photo_url }}" 
                                             alt="{{ $enseignant->name }}" 
                                             class="rounded-circle mx-auto d-block teacher-avatar"
                                             style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #2d3748; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="teacher-avatar-fallback mx-auto" style="display: none;">
                                            {{ strtoupper(substr($enseignant->name, 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="teacher-avatar-fallback mx-auto">
                                            {{ strtoupper(substr($enseignant->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Nom -->
                                <h6 class="card-title mb-2 fw-bold" style="color: #2d3748;">
                                    {{ $enseignant->name }}
                                </h6>

                                <!-- Email -->
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-envelope me-1" style="color: #2d3748;"></i>
                                    {{ $enseignant->email }}
                                </p>

                                <!-- Téléphone si disponible -->
                                @if($enseignant->telephone)
                                    <p class="text-muted small mb-3">
                                        <i class="fas fa-phone me-1" style="color: #2d3748;"></i>
                                        {{ $enseignant->telephone }}
                                    </p>
                                @endif

                                <!-- Badge si déjà sélectionné -->
                                @if(auth()->user()->directeur_memoire_id == $enseignant->id)
                                    <div class="mb-3">
                                        <span class="badge" style="background-color: #2d3748; color: #ffffff;">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Votre directeur actuel
                                        </span>
                                    </div>
                                @endif

                                <!-- Bouton Choisir -->
                                @if(auth()->user()->directeur_memoire_id != $enseignant->id)
                                    <form method="POST" action="{{ route('users.choisir-directeur-memoire.store') }}" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="directeur_memoire_id" value="{{ $enseignant->id }}">
                                        <button type="submit" class="btn btn-sm w-100 btn-choose-dm" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;">
                                            <i class="fas fa-user-check me-2"></i>
                                            Choisir comme DM
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm w-100" style="background-color: #6c757d; border-color: #6c757d; color: #ffffff;" disabled>
                                        <i class="fas fa-check me-2"></i>
                                        Déjà sélectionné
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Aucun enseignant disponible pour le moment.
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .teacher-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #e5e7eb;
    }
    
    .teacher-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    
    .teacher-card.border-primary {
        border-color: #2d3748 !important;
        border-width: 2px;
    }
    
    .teacher-avatar-fallback {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        border: 3px solid #2d3748;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .teacher-card .card-title {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .teacher-card .text-muted {
        font-size: 0.85rem;
    }
    
    .btn-choose-dm:hover {
        background-color: #374151 !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
</style>
@endpush
