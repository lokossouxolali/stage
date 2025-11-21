@extends('layouts.app')

@section('title', 'Modifier l\'entreprise')
@section('page-title', 'Modifier l\'entreprise')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Modifier {{ $entreprise->nom }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('entreprises.update', $entreprise) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom de l'entreprise *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-building"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom', $entreprise->nom) }}" 
                                           required 
                                           autofocus>
                                </div>
                                @error('nom')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $entreprise->email) }}" 
                                           required>
                                </div>
                                @error('email')
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
                                <label for="telephone" class="form-label">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" 
                                           class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" 
                                           name="telephone" 
                                           value="{{ old('telephone', $entreprise->telephone) }}">
                                </div>
                                @error('telephone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_web" class="form-label">Site web</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-globe"></i>
                                    </span>
                                    <input type="url" 
                                           class="form-control @error('site_web') is-invalid @enderror" 
                                           id="site_web" 
                                           name="site_web" 
                                           value="{{ old('site_web', $entreprise->site_web) }}" 
                                           placeholder="https://example.com">
                                </div>
                                @error('site_web')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                      id="adresse" 
                                      name="adresse" 
                                      rows="2" 
                                      placeholder="Adresse complète de l'entreprise">{{ old('adresse', $entreprise->adresse) }}</textarea>
                        </div>
                        @error('adresse')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="secteur_activite" class="form-label">Secteur d'activité *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-industry"></i>
                                    </span>
                                    <select class="form-select @error('secteur_activite') is-invalid @enderror" 
                                            id="secteur_activite" 
                                            name="secteur_activite" 
                                            required>
                                        <option value="">Sélectionnez un secteur</option>
                                        <option value="Informatique" {{ old('secteur_activite', $entreprise->secteur_activite) == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                                        <option value="Finance" {{ old('secteur_activite', $entreprise->secteur_activite) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="Marketing" {{ old('secteur_activite', $entreprise->secteur_activite) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="Ressources Humaines" {{ old('secteur_activite', $entreprise->secteur_activite) == 'Ressources Humaines' ? 'selected' : '' }}>Ressources Humaines</option>
                                        <option value="Commerce" {{ old('secteur_activite', $entreprise->secteur_activite) == 'Commerce' ? 'selected' : '' }}>Commerce</option>
                                        <option value="Santé" {{ old('secteur_activite', $entreprise->secteur_activite) == 'Santé' ? 'selected' : '' }}>Santé</option>
                                        <option value="Éducation" {{ old('secteur_activite', $entreprise->secteur_activite) == 'Éducation' ? 'selected' : '' }}>Éducation</option>
                                        <option value="Autre" {{ old('secteur_activite', $entreprise->secteur_activite) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                                @error('secteur_activite')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="est_verifiee" class="form-label">Statut de vérification</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="est_verifiee" 
                                           name="est_verifiee" 
                                           value="1" 
                                           {{ old('est_verifiee', $entreprise->est_verifiee) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="est_verifiee">
                                        Entreprise vérifiée
                                    </label>
                                </div>
                                <small class="text-muted">Cochez cette case si l'entreprise est vérifiée et fiable</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-align-left"></i>
                            </span>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Décrivez l'entreprise, ses activités, sa mission...">{{ old('description', $entreprise->description) }}</textarea>
                        </div>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('entreprises.show', $entreprise) }}" class="btn btn-secondary">
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
@endsection
