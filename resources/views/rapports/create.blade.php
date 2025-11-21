@extends('layouts.app')

@section('title', 'Nouveau rapport')
@section('page-title', 'Nouveau rapport')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Créer un nouveau rapport
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('rapports.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="titre" class="form-label">Titre du rapport *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-file-text"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('titre') is-invalid @enderror" 
                                           id="titre" 
                                           name="titre" 
                                           value="{{ old('titre') }}" 
                                           required 
                                           autofocus 
                                           placeholder="Ex: Rapport intermédiaire - Développement web">
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
                                <label for="type_rapport" class="form-label">Type de rapport *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <select class="form-select @error('type_rapport') is-invalid @enderror" 
                                            id="type_rapport" 
                                            name="type_rapport" 
                                            required>
                                        <option value="">Sélectionnez un type</option>
                                        <option value="memoire" {{ old('type_rapport') == 'memoire' ? 'selected' : '' }}>Mémoire</option>
                                        <option value="proposition_theme" {{ old('type_rapport') == 'proposition_theme' ? 'selected' : '' }}>Proposition de thème</option>
                                    </select>
                                </div>
                                @error('type_rapport')
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
                                <label for="type" class="form-label">Type de rapport *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Sélectionnez le type</option>
                                        <option value="Rapport_intermediaire" {{ old('type') == 'Rapport_intermediaire' ? 'selected' : '' }}>Rapport intermédiaire</option>
                                        <option value="Rapport_final" {{ old('type') == 'Rapport_final' ? 'selected' : '' }}>Rapport final</option>
                                        <option value="Presentation" {{ old('type') == 'Presentation' ? 'selected' : '' }}>Présentation</option>
                                    </select>
                                </div>
                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_soumission" class="form-label">Date de soumission</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control @error('date_soumission') is-invalid @enderror" 
                                           id="date_soumission" 
                                           name="date_soumission" 
                                           value="{{ old('date_soumission', date('Y-m-d')) }}">
                                </div>
                                @error('date_soumission')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="destinataire" class="form-label">Destinataire *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user-check"></i>
                            </span>
                            <select class="form-select @error('destinataire') is-invalid @enderror" 
                                    id="destinataire" 
                                    name="destinataire" 
                                    required>
                                <option value="">Sélectionnez le destinataire</option>
                                <option value="admin" {{ old('destinataire') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                <option value="directeur_memoire" {{ old('destinataire') == 'directeur_memoire' ? 'selected' : '' }}>Directeur de mémoire</option>
                                <option value="les_deux" {{ old('destinataire') == 'les_deux' ? 'selected' : '' }}>Administrateur et Directeur de mémoire</option>
                            </select>
                        </div>
                        @error('destinataire')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Choisissez à qui envoyer ce rapport</small>
                    </div>

                    <div class="mb-3">
                        <label for="fichier" class="form-label">Fichier du rapport (PDF) *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            <input type="file" 
                                   class="form-control @error('fichier') is-invalid @enderror" 
                                   id="fichier" 
                                   name="fichier" 
                                   required 
                                   accept=".pdf">
                        </div>
                        @error('fichier')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Format PDF uniquement, taille max: 10MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="commentaires" class="form-label">Commentaires supplémentaires</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-comment"></i>
                            </span>
                            <textarea class="form-control @error('commentaires') is-invalid @enderror" 
                                      id="commentaires" 
                                      name="commentaires" 
                                      rows="3" 
                                      placeholder="Toute information supplémentaire que vous souhaitez partager...">{{ old('commentaires') }}</textarea>
                        </div>
                        @error('commentaires')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('rapports.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Enregistrer le rapport
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Validation du fichier
document.getElementById('fichier').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        if (file.type !== 'application/pdf') {
            this.classList.add('is-invalid');
            this.setCustomValidity('Seuls les fichiers PDF sont acceptés');
        } else if (file.size > 10 * 1024 * 1024) { // 10MB
            this.classList.add('is-invalid');
            this.setCustomValidity('Le fichier ne doit pas dépasser 10MB');
        } else {
            this.classList.remove('is-invalid');
            this.setCustomValidity('');
        }
    }
});

// Auto-remplissage de la date de soumission
document.getElementById('date_soumission').value = new Date().toISOString().split('T')[0];
</script>
@endpush
@endsection
