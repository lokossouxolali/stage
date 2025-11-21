@extends('layouts.app')

@section('title', 'Inscription')
@section('page-title', 'Inscription')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <div class="mb-3">
                    <h4 class="card-title mb-1">Créer un compte</h4>
                    <p class="text-muted small mb-0">Rejoignez notre plateforme de gestion de stages</p>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <!-- Sélection du type de compte en premier -->
                    <div class="mb-2">
                        <label for="role" class="form-label small fw-bold">Type de compte *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">
                                <i class="fas fa-user-tag"></i>
                            </span>
                            <select class="form-select form-select-sm @error('role') is-invalid @enderror" 
                                    id="role" 
                                    name="role" 
                                    required>
                                <option value="">Sélectionnez votre rôle</option>
                                <option value="etudiant" {{ old('role') == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                                <option value="entreprise" {{ old('role') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                                <option value="enseignant" {{ old('role') == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                            </select>
                        </div>
                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Champs communs (masqués jusqu'à sélection du rôle) -->
                    <div id="common-fields" style="display: none;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="name" class="form-label small">Nom complet *</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control form-control-sm @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               required 
                                               autocomplete="name">
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="email" class="form-label small">Adresse email *</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control form-control-sm @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               required 
                                               autocomplete="email">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="telephone" class="form-label small">Téléphone</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="tel" 
                                               class="form-control form-control-sm @error('telephone') is-invalid @enderror" 
                                               id="telephone" 
                                               name="telephone" 
                                               value="{{ old('telephone') }}" 
                                               autocomplete="tel">
                                    </div>
                                    @error('telephone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="date_naissance" class="form-label small">Date de naissance</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <input type="date" 
                                               class="form-control form-control-sm @error('date_naissance') is-invalid @enderror" 
                                               id="date_naissance" 
                                               name="date_naissance" 
                                               value="{{ old('date_naissance') }}">
                                    </div>
                                    @error('date_naissance')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Champs spécifiques Étudiant -->
                        <div id="etudiant-fields" style="display: none;">
                            <hr class="my-2">
                            <h6 class="mb-2 small fw-bold"><i class="fas fa-graduation-cap me-1"></i>Informations étudiant</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label for="niveau_etude" class="form-label small">Niveau d'étude *</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fas fa-graduation-cap"></i>
                                            </span>
                                            <select class="form-select form-select-sm @error('niveau_etude') is-invalid @enderror" 
                                                    id="niveau_etude" 
                                                    name="niveau_etude">
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
                                
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label for="filiere" class="form-label small">Filière *</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fas fa-book"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('filiere') is-invalid @enderror" 
                                                   id="filiere" 
                                                   name="filiere" 
                                                   value="{{ old('filiere') }}" 
                                                   placeholder="Ex: Informatique, Gestion, etc.">
                                        </div>
                                        @error('filiere')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Champs spécifiques Enseignant -->
                        <div id="enseignant-fields" style="display: none;">
                            <hr class="my-2">
                            <h6 class="mb-2 small fw-bold"><i class="fas fa-chalkboard-teacher me-1"></i>Informations enseignant</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label for="specialite" class="form-label small">Spécialité / Département</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fas fa-briefcase"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('specialite') is-invalid @enderror" 
                                                   id="specialite" 
                                                   name="specialite" 
                                                   value="{{ old('specialite') }}" 
                                                   placeholder="Ex: Informatique, Mathématiques, etc.">
                                        </div>
                                        @error('specialite')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Champs spécifiques Entreprise -->
                        <div id="entreprise-fields" style="display: none;">
                            <hr class="my-2">
                            <h6 class="mb-2 small fw-bold"><i class="fas fa-building me-1"></i>Informations entreprise</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label for="nom_entreprise" class="form-label small">Nom de l'entreprise *</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fas fa-building"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('nom_entreprise') is-invalid @enderror" 
                                                   id="nom_entreprise" 
                                                   name="nom_entreprise" 
                                                   value="{{ old('nom_entreprise') }}" 
                                                   placeholder="Nom de votre entreprise">
                                        </div>
                                        @error('nom_entreprise')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label for="secteur_activite" class="form-label small">Secteur d'activité</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fas fa-industry"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('secteur_activite') is-invalid @enderror" 
                                                   id="secteur_activite" 
                                                   name="secteur_activite" 
                                                   value="{{ old('secteur_activite') }}" 
                                                   placeholder="Ex: Informatique, Commerce, etc.">
                                        </div>
                                        @error('secteur_activite')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label for="adresse_entreprise" class="form-label small">Adresse</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('adresse_entreprise') is-invalid @enderror" 
                                                   id="adresse_entreprise" 
                                                   name="adresse_entreprise" 
                                                   value="{{ old('adresse_entreprise') }}" 
                                                   placeholder="Adresse de l'entreprise">
                                        </div>
                                        @error('adresse_entreprise')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="password" class="form-label small">Mot de passe *</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control form-control-sm @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               required 
                                               autocomplete="new-password">
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="password_confirmation" class="form-label small">Confirmer le mot de passe *</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control form-control-sm" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required 
                                               autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-user-plus me-1"></i>
                                Créer mon compte
                            </button>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-2">
                    <p class="mb-0 small">
                        Déjà un compte ? 
                        <a href="{{ route('login') }}" class="text-decoration-none" style="color: #2d3748;">
                            Se connecter
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const commonFields = document.getElementById('common-fields');
    const etudiantFields = document.getElementById('etudiant-fields');
    const enseignantFields = document.getElementById('enseignant-fields');
    const entrepriseFields = document.getElementById('entreprise-fields');
    
    // Fonction pour gérer l'affichage des champs selon le rôle
    function toggleFields() {
        const selectedRole = roleSelect.value;
        
        if (selectedRole) {
            // Afficher les champs communs
            commonFields.style.display = 'block';
            
            // Masquer tous les champs spécifiques
            etudiantFields.style.display = 'none';
            enseignantFields.style.display = 'none';
            entrepriseFields.style.display = 'none';
            
            // Afficher les champs spécifiques selon le rôle
            if (selectedRole === 'etudiant') {
                etudiantFields.style.display = 'block';
                // Rendre obligatoires les champs étudiant
                document.getElementById('niveau_etude').required = true;
                document.getElementById('filiere').required = true;
            } else {
                document.getElementById('niveau_etude').required = false;
                document.getElementById('filiere').required = false;
            }
            
            if (selectedRole === 'enseignant') {
                enseignantFields.style.display = 'block';
            }
            
            if (selectedRole === 'entreprise') {
                entrepriseFields.style.display = 'block';
                // Rendre obligatoire le nom de l'entreprise
                document.getElementById('nom_entreprise').required = true;
            } else {
                document.getElementById('nom_entreprise').required = false;
            }
        } else {
            // Masquer tous les champs si aucun rôle n'est sélectionné
            commonFields.style.display = 'none';
        }
    }
    
    // Écouter les changements du sélecteur de rôle
    roleSelect.addEventListener('change', toggleFields);
    
    // Initialiser l'affichage au chargement de la page
    toggleFields();
});
</script>
@endpush
@endsection
