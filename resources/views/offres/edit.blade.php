@extends('layouts.app')

@section('title', 'Modifier l\'offre de stage')
@section('page-title', 'Modifier l\'offre de stage')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Modifier {{ $offre->titre }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('offres.update', $offre) }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre de l'offre *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-briefcase"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('titre') is-invalid @enderror" 
                                   id="titre" 
                                   name="titre" 
                                   value="{{ old('titre', $offre->titre) }}" 
                                   required 
                                   autofocus>
                        </div>
                        @error('titre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-align-left"></i>
                            </span>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required>{{ old('description', $offre->description) }}</textarea>
                        </div>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="missions" class="form-label">Missions principales *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-tasks"></i>
                            </span>
                            <textarea class="form-control @error('missions') is-invalid @enderror" 
                                      id="missions" 
                                      name="missions" 
                                      rows="3" 
                                      required>{{ old('missions', $offre->missions) }}</textarea>
                        </div>
                        @error('missions')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="competences_requises" class="form-label">Compétences requises</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-star"></i>
                            </span>
                            <textarea class="form-control @error('competences_requises') is-invalid @enderror" 
                                      id="competences_requises" 
                                      name="competences_requises" 
                                      rows="3">{{ old('competences_requises', $offre->competences_requises) }}</textarea>
                        </div>
                        @error('competences_requises')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <hr class="my-4">
                    <h6 class="mb-3 text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations du stage
                    </h6>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="type_stage" class="form-label">Type de stage *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-graduation-cap"></i>
                                    </span>
                                    <select class="form-select @error('type_stage') is-invalid @enderror" 
                                            id="type_stage" 
                                            name="type_stage" 
                                            required>
                                        <option value="">Sélectionnez le type</option>
                                        <option value="Obligatoire" {{ old('type_stage', $offre->type_stage) == 'Obligatoire' ? 'selected' : '' }}>Obligatoire</option>
                                        <option value="Perfectionnement" {{ old('type_stage', $offre->type_stage) == 'Perfectionnement' ? 'selected' : '' }}>Perfectionnement</option>
                                        <option value="Projet_fin_etudes" {{ old('type_stage', $offre->type_stage) == 'Projet_fin_etudes' ? 'selected' : '' }}>Projet fin d'études</option>
                                    </select>
                                </div>
                                @error('type_stage')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="niveau_etude" class="form-label">Niveau requis *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-graduate"></i>
                                    </span>
                                    <select class="form-select @error('niveau_etude') is-invalid @enderror" 
                                            id="niveau_etude" 
                                            name="niveau_etude" 
                                            required>
                                        <option value="">Sélectionnez le niveau</option>
                                        <option value="L1" {{ old('niveau_etude', $offre->niveau_etude) == 'L1' ? 'selected' : '' }}>L1</option>
                                        <option value="L2" {{ old('niveau_etude', $offre->niveau_etude) == 'L2' ? 'selected' : '' }}>L2</option>
                                        <option value="L3" {{ old('niveau_etude', $offre->niveau_etude) == 'L3' ? 'selected' : '' }}>L3</option>
                                        <option value="M1" {{ old('niveau_etude', $offre->niveau_etude) == 'M1' ? 'selected' : '' }}>M1</option>
                                        <option value="M2" {{ old('niveau_etude', $offre->niveau_etude) == 'M2' ? 'selected' : '' }}>M2</option>
                                    </select>
                                </div>
                                @error('niveau_etude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="duree" class="form-label">Durée (mois) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input type="number" 
                                           class="form-control @error('duree') is-invalid @enderror" 
                                           id="duree" 
                                           name="duree" 
                                           value="{{ old('duree', $offre->duree) }}" 
                                           required 
                                           min="1" 
                                           max="12"
                                           placeholder="Ex: 6">
                                </div>
                                @error('duree')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_debut" class="form-label">Date de début *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control @error('date_debut') is-invalid @enderror" 
                                           id="date_debut" 
                                           name="date_debut" 
                                           value="{{ old('date_debut', $offre->date_debut?->format('Y-m-d')) }}"
                                           required>
                                </div>
                                @error('date_debut')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_fin" class="form-label">Date de fin *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control @error('date_fin') is-invalid @enderror" 
                                           id="date_fin" 
                                           name="date_fin" 
                                           value="{{ old('date_fin', $offre->date_fin?->format('Y-m-d')) }}"
                                           required>
                                </div>
                                <small class="text-muted">Calculée automatiquement selon la durée</small>
                                @error('date_fin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre_places" class="form-label">Nombre de places</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <input type="number" 
                                           class="form-control @error('nombre_places') is-invalid @enderror" 
                                           id="nombre_places" 
                                           name="nombre_places" 
                                           value="{{ old('nombre_places', $offre->nombre_places) }}" 
                                           min="1" 
                                           max="10"
                                           placeholder="1">
                                </div>
                                <small class="text-muted">Par défaut: 1 place</small>
                                @error('nombre_places')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lieu" class="form-label">Lieu de travail</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('lieu') is-invalid @enderror" 
                                           id="lieu" 
                                           name="lieu" 
                                           value="{{ old('lieu', $offre->lieu) }}"
                                           placeholder="Ville, adresse...">
                                </div>
                                @error('lieu')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="date_limite_candidature" class="form-label">Date limite de candidature</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-clock"></i>
                            </span>
                            <input type="date" 
                                   class="form-control @error('date_limite_candidature') is-invalid @enderror" 
                                   id="date_limite_candidature" 
                                   name="date_limite_candidature" 
                                   value="{{ old('date_limite_candidature', $offre->date_limite_candidature?->format('Y-m-d')) }}">
                        </div>
                        <small class="text-muted">Laisser vide si aucune date limite</small>
                        @error('date_limite_candidature')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3 text-muted">
                        <i class="fas fa-paper-plane me-2"></i>
                        Publication
                    </h6>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="statut" 
                                   name="statut" 
                                   value="active" 
                                   {{ old('statut', $offre->statut) == 'active' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="statut">
                                Publier immédiatement
                            </label>
                        </div>
                        <small class="text-muted ms-4">Décochez pour sauvegarder en brouillon</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('offres.show', $offre) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Calcul automatique de la date de fin basée sur la durée
document.getElementById('duree').addEventListener('input', function() {
    const duree = parseInt(this.value);
    const dateDebut = document.getElementById('date_debut').value;
    
    if (duree && dateDebut) {
        const debut = new Date(dateDebut);
        const fin = new Date(debut);
        fin.setMonth(fin.getMonth() + duree);
        
        document.getElementById('date_fin').value = fin.toISOString().split('T')[0];
    }
});

// Calcul automatique de la date de fin basée sur la date de début
document.getElementById('date_debut').addEventListener('change', function() {
    const duree = parseInt(document.getElementById('duree').value);
    const dateDebut = this.value;
    
    if (duree && dateDebut) {
        const debut = new Date(dateDebut);
        const fin = new Date(debut);
        fin.setMonth(fin.getMonth() + duree);
        
        document.getElementById('date_fin').value = fin.toISOString().split('T')[0];
    }
});
</script>
@endpush
@endsection
