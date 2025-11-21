@extends('layouts.app')

@section('title', 'Nouvelle soutenance')
@section('page-title', 'Nouvelle soutenance')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Planifier une nouvelle soutenance
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('soutenances.store') }}">
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
                                <label for="type" class="form-label">Type de soutenance *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Sélectionnez le type</option>
                                        <option value="Intermediaire" {{ old('type') == 'Intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
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
                                <label for="date_soutenance" class="form-label">Date de soutenance *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control @error('date_soutenance') is-invalid @enderror" 
                                           id="date_soutenance" 
                                           name="date_soutenance" 
                                           value="{{ old('date_soutenance') }}" 
                                           required>
                                </div>
                                @error('date_soutenance')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lieu" class="form-label">Lieu de soutenance *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('lieu') is-invalid @enderror" 
                                           id="lieu" 
                                           name="lieu" 
                                           value="{{ old('lieu') }}" 
                                           required 
                                           placeholder="Ex: Salle A101, Amphi 1...">
                                </div>
                                @error('lieu')
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
                                <label for="heure_debut" class="form-label">Heure de début *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <input type="time" 
                                           class="form-control @error('heure_debut') is-invalid @enderror" 
                                           id="heure_debut" 
                                           name="heure_debut" 
                                           value="{{ old('heure_debut') }}" 
                                           required>
                                </div>
                                @error('heure_debut')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="heure_fin" class="form-label">Heure de fin</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <input type="time" 
                                           class="form-control @error('heure_fin') is-invalid @enderror" 
                                           id="heure_fin" 
                                           name="heure_fin" 
                                           value="{{ old('heure_fin') }}">
                                </div>
                                @error('heure_fin')
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
                                <label for="jury_president" class="form-label">Président du jury</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('jury_president') is-invalid @enderror" 
                                           id="jury_president" 
                                           name="jury_president" 
                                           value="{{ old('jury_president') }}" 
                                           placeholder="Nom du président du jury">
                                </div>
                                @error('jury_president')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jury_membres" class="form-label">Membres du jury</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('jury_membres') is-invalid @enderror" 
                                           id="jury_membres" 
                                           name="jury_membres" 
                                           value="{{ old('jury_membres') }}" 
                                           placeholder="Noms des membres du jury (séparés par des virgules)">
                                </div>
                                @error('jury_membres')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="objectifs" class="form-label">Objectifs de la soutenance</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-target"></i>
                            </span>
                            <textarea class="form-control @error('objectifs') is-invalid @enderror" 
                                      id="objectifs" 
                                      name="objectifs" 
                                      rows="3" 
                                      placeholder="Décrivez les objectifs de cette soutenance...">{{ old('objectifs') }}</textarea>
                        </div>
                        @error('objectifs')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="consignes" class="form-label">Consignes pour l'étudiant</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-list"></i>
                            </span>
                            <textarea class="form-control @error('consignes') is-invalid @enderror" 
                                      id="consignes" 
                                      name="consignes" 
                                      rows="3" 
                                      placeholder="Consignes spécifiques pour l'étudiant (durée de présentation, documents à préparer, etc.)...">{{ old('consignes') }}</textarea>
                        </div>
                        @error('consignes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut de la soutenance</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-toggle-on"></i>
                            </span>
                            <select class="form-select @error('statut') is-invalid @enderror" 
                                    id="statut" 
                                    name="statut">
                                <option value="planifiee" {{ old('statut', 'planifiee') == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                                <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="terminee" {{ old('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                                <option value="annulee" {{ old('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        @error('statut')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('soutenances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Planifier la soutenance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Calcul automatique de l'heure de fin basée sur l'heure de début
document.getElementById('heure_debut').addEventListener('change', function() {
    const heureDebut = this.value;
    if (heureDebut) {
        const [heures, minutes] = heureDebut.split(':');
        const debut = new Date();
        debut.setHours(parseInt(heures), parseInt(minutes));
        
        // Ajouter 1 heure par défaut
        const fin = new Date(debut.getTime() + 60 * 60 * 1000);
        const heureFin = fin.getHours().toString().padStart(2, '0') + ':' + fin.getMinutes().toString().padStart(2, '0');
        
        document.getElementById('heure_fin').value = heureFin;
    }
});
</script>
@endpush
@endsection
