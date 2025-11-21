@extends('layouts.app')

@section('title', 'Publier une offre de stage')
@section('page-title', 'Publier une offre de stage')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Informations de l'offre
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('offres.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
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
                                           value="{{ old('titre') }}" 
                                           required 
                                           autofocus 
                                           placeholder="Ex: Développeur Web Full Stack">
                                </div>
                                @error('titre')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="entreprise_id" class="form-label">Entreprise *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-building"></i>
                                    </span>
                                    <select class="form-select @error('entreprise_id') is-invalid @enderror" 
                                            id="entreprise_id" 
                                            name="entreprise_id" 
                                            required>
                                        <option value="">Sélectionnez une entreprise</option>
                                        @foreach($entreprises as $entreprise)
                                            <option value="{{ $entreprise->id }}" 
                                                    {{ old('entreprise_id', request('entreprise_id')) == $entreprise->id ? 'selected' : '' }}>
                                                {{ $entreprise->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('entreprise_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
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
                                      required 
                                      placeholder="Décrivez le poste, les missions, l'environnement de travail...">{{ old('description') }}</textarea>
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
                                      required 
                                      placeholder="Listez les principales missions du stagiaire...">{{ old('missions') }}</textarea>
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
                                      rows="3" 
                                      placeholder="Listez les compétences techniques et soft skills requises...">{{ old('competences_requises') }}</textarea>
                        </div>
                        @error('competences_requises')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3">
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
                                           value="{{ old('duree') }}" 
                                           required 
                                           min="1" 
                                           max="12">
                                </div>
                                @error('duree')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <select class="form-select @error('type_stage') is-invalid @enderror" 
                                id="type_stage" 
                                name="type_stage" 
                                required>
                            <option value="">Sélectionnez le type</option>
                            @foreach ($type_stage as $type)
                                @foreach ($type_stage as $type)
                                    <option value="{{ $type }}" {{ old('type_stage') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            @endforeach
                        </select>

                        
                        <div class="col-md-3">
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
                                        <option value="L1" {{ old('niveau_etude') == 'L1' ? 'selected' : '' }}>L1</option>
                                        <option value="L2" {{ old('niveau_etude') == 'L2' ? 'selected' : '' }}>L2</option>
                                        <option value="L3" {{ old('niveau_etude') == 'L3' ? 'selected' : '' }}>L3</option>
                                        <option value="M1" {{ old('niveau_etude') == 'M1' ? 'selected' : '' }}>M1</option>
                                        <option value="M2" {{ old('niveau_etude') == 'M2' ? 'selected' : '' }}>M2</option>
                                    </select>
                                </div>
                                @error('niveau_etude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
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
                                           value="{{ old('nombre_places', 1) }}" 
                                           min="1" 
                                           max="10">
                                </div>
                                @error('nombre_places')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="date_debut" class="form-label">Date de début</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control @error('date_debut') is-invalid @enderror" 
                                           id="date_debut" 
                                           name="date_debut" 
                                           value="{{ old('date_debut') }}">
                                </div>
                                @error('date_debut')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="date_fin" class="form-label">Date de fin</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control @error('date_fin') is-invalid @enderror" 
                                           id="date_fin" 
                                           name="date_fin" 
                                           value="{{ old('date_fin') }}">
                                </div>
                                @error('date_fin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="date_limite_candidature" class="form-label">Date limite candidature</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control @error('date_limite_candidature') is-invalid @enderror" 
                                           id="date_limite_candidature" 
                                           name="date_limite_candidature" 
                                           value="{{ old('date_limite_candidature') }}">
                                </div>
                                @error('date_limite_candidature')
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
                                <label for="lieu" class="form-label">Lieu de travail</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('lieu') is-invalid @enderror" 
                                           id="lieu" 
                                           name="lieu" 
                                           value="{{ old('lieu') }}" 
                                           placeholder="Ville, adresse...">
                                </div>
                                @error('lieu')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="remuneration" class="form-label">Rémunération (€/mois)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                    <input type="number" 
                                           class="form-control @error('remuneration') is-invalid @enderror" 
                                           id="remuneration" 
                                           name="remuneration" 
                                           value="{{ old('remuneration') }}" 
                                           min="0" 
                                           step="0.01" 
                                           placeholder="0 si non rémunéré">
                                </div>
                                @error('remuneration')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut de publication</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="statut" 
                                   name="statut" 
                                   value="active" 
                                   {{ old('statut') == 'active' ? 'checked' : '' }}>
                            <label class="form-check-label" for="statut">
                                Publier immédiatement
                            </label>
                        </div>
                        <small class="text-muted">Décochez pour sauvegarder en brouillon</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('offres.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Publier l'offre
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
