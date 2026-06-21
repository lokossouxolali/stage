@extends('layouts.app')

@section('title', 'Nouvelle candidature')
@section('page-title', 'Nouvelle candidature')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-paper-plane me-2"></i>
                    Candidature pour {{ $offre->titre }}
                </h5>
            </div>
            <div class="card-body">
                <!-- Informations sur l'offre -->
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations sur l'offre
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Entreprise:</strong> {{ $offre->entreprise?->nom ?? 'Non spécifiée' }}</p>
                            <p class="mb-1"><strong>Type:</strong> {{ $offre->type_stage }}</p>
                            <p class="mb-1"><strong>Durée:</strong> {{ $offre->duree }} mois</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Niveau requis:</strong> {{ $offre->niveau_etude }}</p>
                            @if($offre->lieu)
                                <p class="mb-1"><strong>Lieu:</strong> {{ $offre->lieu }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('candidatures.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="offre_id" value="{{ $offre->id }}">
                    
                    <div class="mb-3">
                        <label for="lettre_motivation" class="form-label">Lettre de motivation (PDF, DOCX) *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            <input type="file"
                                   class="form-control @error('lettre_motivation') is-invalid @enderror"
                                   id="lettre_motivation"
                                   name="lettre_motivation"
                                   accept=".pdf,.doc,.docx"
                                   required
                                   autofocus>
                        </div>
                        @error('lettre_motivation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Formats PDF, DOC ou DOCX, taille max: 5MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="cv_path" class="form-label">CV (PDF)</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            <input type="file" 
                                   class="form-control @error('cv_path') is-invalid @enderror" 
                                   id="cv_path" 
                                   name="cv_path" 
                                   accept=".pdf">
                        </div>
                        @error('cv_path')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Format PDF uniquement, taille max: 5MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="lettre_recommandation" class="form-label">Lettre de recommandation (PDF)</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            <input type="file" 
                                   class="form-control @error('lettre_recommandation') is-invalid @enderror" 
                                   id="lettre_recommandation" 
                                   name="lettre_recommandation" 
                                   accept=".pdf">
                        </div>
                        @error('lettre_recommandation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Optionnel - Format PDF uniquement, taille max: 5MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="portfolio" class="form-label">Portfolio/Lien GitHub</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-link"></i>
                            </span>
                            <input type="url" 
                                   class="form-control @error('portfolio') is-invalid @enderror" 
                                   id="portfolio" 
                                   name="portfolio" 
                                   value="{{ old('portfolio') }}" 
                                   placeholder="https://github.com/votre-profil ou https://votre-portfolio.com">
                        </div>
                        @error('portfolio')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Optionnel - Lien vers votre portfolio ou profil GitHub</small>
                    </div>

                    <div class="mb-3">
                        <label for="disponibilite" class="form-label">Disponibilité</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar"></i>
                            </span>
                            <input type="text" 
                                   class="form-control @error('disponibilite') is-invalid @enderror" 
                                   id="disponibilite" 
                                   name="disponibilite" 
                                   value="{{ old('disponibilite') }}" 
                                   placeholder="Ex: Disponible à partir de janvier 2024, 3 mois">
                        </div>
                        @error('disponibilite')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Précisez vos disponibilités pour ce stage</small>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>
                            Envoyer la candidature
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Validation des fichiers
const maxFileSize = 5 * 1024 * 1024;

const fileInputs = {
    lettre_motivation: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
    cv_path: ['application/pdf'],
    lettre_recommandation: ['application/pdf'],
};

Object.entries(fileInputs).forEach(([id, allowedTypes]) => {
    const input = document.getElementById(id);

    if (!input) {
        return;
    }

    input.addEventListener('change', function() {
        validateFile(this, allowedTypes);
    });
});

function validateFile(input, allowedTypes) {
    const file = input.files[0];

    if (!file) {
        input.classList.remove('is-invalid');
        input.setCustomValidity('');
        return;
    }

    if (!allowedTypes.includes(file.type)) {
        input.classList.add('is-invalid');
        input.setCustomValidity('Format de fichier non autorisé');
    } else if (file.size > maxFileSize) {
        input.classList.add('is-invalid');
        input.setCustomValidity('Le fichier ne doit pas dépasser 5MB');
    } else {
        input.classList.remove('is-invalid');
        input.setCustomValidity('');
    }
}
</script>
@endpush
@endsection
