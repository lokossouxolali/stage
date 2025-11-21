@extends('layouts.app')

@section('title', 'Nouvelle évaluation')
@section('page-title', 'Nouvelle évaluation')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Créer une nouvelle évaluation
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('evaluations.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stage_id" class="form-label">Stage *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-clipboard-list"></i>
                                    </span>
                                    <select class="form-select @error('stage_id') is-invalid @enderror" 
                                            id="stage_id" 
                                            name="stage_id" 
                                            required>
                                        <option value="">Sélectionnez un stage</option>
                                        @foreach($stages as $stage)
                                            <option value="{{ $stage->id }}" 
                                                    {{ old('stage_id', request('stage_id')) == $stage->id ? 'selected' : '' }}>
                                                {{ $stage->candidature->etudiant->name }} - {{ $stage->candidature->offre->titre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('stage_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type d'évaluation *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Sélectionnez le type</option>
                                        <option value="Technique" {{ old('type') == 'Technique' ? 'selected' : '' }}>Technique</option>
                                        <option value="Comportementale" {{ old('type') == 'Comportementale' ? 'selected' : '' }}>Comportementale</option>
                                        <option value="Finale" {{ old('type') == 'Finale' ? 'selected' : '' }}>Finale</option>
                                    </select>
                                </div>
                                @error('type')
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
                                <label for="note" class="form-label">Note (sur 20)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-star"></i>
                                    </span>
                                    <input type="number" 
                                           class="form-control @error('note') is-invalid @enderror" 
                                           id="note" 
                                           name="note" 
                                           value="{{ old('note') }}" 
                                           min="0" 
                                           max="20" 
                                           step="0.5" 
                                           placeholder="Ex: 16.5">
                                </div>
                                @error('note')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="text-muted">Note sur 20 (optionnel pour le moment)</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_evaluation" class="form-label">Date d'évaluation</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control @error('date_evaluation') is-invalid @enderror" 
                                           id="date_evaluation" 
                                           name="date_evaluation" 
                                           value="{{ old('date_evaluation', date('Y-m-d')) }}">
                                </div>
                                @error('date_evaluation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Critères d'évaluation -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Critères d'évaluation</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="competence_technique" class="form-label">Compétence technique (1-5)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-code"></i>
                                        </span>
                                        <select class="form-select @error('competence_technique') is-invalid @enderror" 
                                                id="competence_technique" 
                                                name="competence_technique">
                                            <option value="">Sélectionnez une note</option>
                                            <option value="1" {{ old('competence_technique') == '1' ? 'selected' : '' }}>1 - Très insuffisant</option>
                                            <option value="2" {{ old('competence_technique') == '2' ? 'selected' : '' }}>2 - Insuffisant</option>
                                            <option value="3" {{ old('competence_technique') == '3' ? 'selected' : '' }}>3 - Moyen</option>
                                            <option value="4" {{ old('competence_technique') == '4' ? 'selected' : '' }}>4 - Bien</option>
                                            <option value="5" {{ old('competence_technique') == '5' ? 'selected' : '' }}>5 - Très bien</option>
                                        </select>
                                    </div>
                                    @error('competence_technique')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="autonomie" class="form-label">Autonomie (1-5)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-check"></i>
                                        </span>
                                        <select class="form-select @error('autonomie') is-invalid @enderror" 
                                                id="autonomie" 
                                                name="autonomie">
                                            <option value="">Sélectionnez une note</option>
                                            <option value="1" {{ old('autonomie') == '1' ? 'selected' : '' }}>1 - Très insuffisant</option>
                                            <option value="2" {{ old('autonomie') == '2' ? 'selected' : '' }}>2 - Insuffisant</option>
                                            <option value="3" {{ old('autonomie') == '3' ? 'selected' : '' }}>3 - Moyen</option>
                                            <option value="4" {{ old('autonomie') == '4' ? 'selected' : '' }}>4 - Bien</option>
                                            <option value="5" {{ old('autonomie') == '5' ? 'selected' : '' }}>5 - Très bien</option>
                                        </select>
                                    </div>
                                    @error('autonomie')
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
                                    <label for="travail_equipe" class="form-label">Travail en équipe (1-5)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <select class="form-select @error('travail_equipe') is-invalid @enderror" 
                                                id="travail_equipe" 
                                                name="travail_equipe">
                                            <option value="">Sélectionnez une note</option>
                                            <option value="1" {{ old('travail_equipe') == '1' ? 'selected' : '' }}>1 - Très insuffisant</option>
                                            <option value="2" {{ old('travail_equipe') == '2' ? 'selected' : '' }}>2 - Insuffisant</option>
                                            <option value="3" {{ old('travail_equipe') == '3' ? 'selected' : '' }}>3 - Moyen</option>
                                            <option value="4" {{ old('travail_equipe') == '4' ? 'selected' : '' }}>4 - Bien</option>
                                            <option value="5" {{ old('travail_equipe') == '5' ? 'selected' : '' }}>5 - Très bien</option>
                                        </select>
                                    </div>
                                    @error('travail_equipe')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="communication" class="form-label">Communication (1-5)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-comments"></i>
                                        </span>
                                        <select class="form-select @error('communication') is-invalid @enderror" 
                                                id="communication" 
                                                name="communication">
                                            <option value="">Sélectionnez une note</option>
                                            <option value="1" {{ old('communication') == '1' ? 'selected' : '' }}>1 - Très insuffisant</option>
                                            <option value="2" {{ old('communication') == '2' ? 'selected' : '' }}>2 - Insuffisant</option>
                                            <option value="3" {{ old('communication') == '3' ? 'selected' : '' }}>3 - Moyen</option>
                                            <option value="4" {{ old('communication') == '4' ? 'selected' : '' }}>4 - Bien</option>
                                            <option value="5" {{ old('communication') == '5' ? 'selected' : '' }}>5 - Très bien</option>
                                        </select>
                                    </div>
                                    @error('communication')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="commentaires" class="form-label">Commentaires généraux</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-comment"></i>
                            </span>
                            <textarea class="form-control @error('commentaires') is-invalid @enderror" 
                                      id="commentaires" 
                                      name="commentaires" 
                                      rows="4" 
                                      placeholder="Donnez votre avis global sur le stage de l'étudiant, ses points forts, ses axes d'amélioration...">{{ old('commentaires') }}</textarea>
                        </div>
                        @error('commentaires')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="recommandations" class="form-label">Recommandations</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lightbulb"></i>
                            </span>
                            <textarea class="form-control @error('recommandations') is-invalid @enderror" 
                                      id="recommandations" 
                                      name="recommandations" 
                                      rows="3" 
                                      placeholder="Quelles sont vos recommandations pour l'étudiant ?">{{ old('recommandations') }}</textarea>
                        </div>
                        @error('recommandations')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut de l'évaluation</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-toggle-on"></i>
                            </span>
                            <select class="form-select @error('statut') is-invalid @enderror" 
                                    id="statut" 
                                    name="statut">
                                <option value="en_cours" {{ old('statut', 'en_cours') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="terminee" {{ old('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                            </select>
                        </div>
                        @error('statut')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('evaluations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Enregistrer l'évaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Calcul automatique de la note basée sur les critères
function calculateNote() {
    const competence = parseInt(document.getElementById('competence_technique').value) || 0;
    const autonomie = parseInt(document.getElementById('autonomie').value) || 0;
    const equipe = parseInt(document.getElementById('travail_equipe').value) || 0;
    const communication = parseInt(document.getElementById('communication').value) || 0;
    
    if (competence > 0 && autonomie > 0 && equipe > 0 && communication > 0) {
        const moyenne = (competence + autonomie + equipe + communication) / 4;
        const note = (moyenne / 5) * 20; // Conversion sur 20
        document.getElementById('note').value = note.toFixed(1);
    }
}

// Écouter les changements sur les critères
document.getElementById('competence_technique').addEventListener('change', calculateNote);
document.getElementById('autonomie').addEventListener('change', calculateNote);
document.getElementById('travail_equipe').addEventListener('change', calculateNote);
document.getElementById('communication').addEventListener('change', calculateNote);
</script>
@endpush
@endsection
