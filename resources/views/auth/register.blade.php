@extends('layouts.app')

@section('title', 'Inscription')
@section('page-title', 'Inscription')

@section('content')
<div class="auth-wide">
    <div class="card auth-register-card">
        <div class="card-body p-4 p-lg-5">
            <div class="mb-4">
                <h1 class="h4 fw-bold mb-1">Creer un compte</h1>
                <p class="text-muted mb-0">Selectionnez votre profil, renseignez votre token, puis confirmez votre email par OTP.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="account-type-grid mb-4">
                    <label class="account-type-option">
                        <input type="radio" name="role" value="etudiant" @checked(old('role') === 'etudiant') required>
                        <span><i class="fas fa-graduation-cap"></i></span>
                        <strong>Etudiant</strong>
                    </label>
                    <label class="account-type-option">
                        <input type="radio" name="role" value="entreprise" @checked(old('role') === 'entreprise') required>
                        <span><i class="fas fa-building"></i></span>
                        <strong>Entreprise</strong>
                    </label>
                    <label class="account-type-option">
                        <input type="radio" name="role" value="enseignant" @checked(old('role') === 'enseignant') required>
                        <span><i class="fas fa-chalkboard-teacher"></i></span>
                        <strong>Enseignant</strong>
                    </label>
                </div>
                @error('role')
                    <div class="text-danger small mb-3">{{ $message }}</div>
                @enderror

                <div id="registration-fields" class="registration-fields">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="registration_token" class="form-label">Token d'inscription *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                <input type="text" class="form-control @error('registration_token') is-invalid @enderror" id="registration_token" name="registration_token" value="{{ old('registration_token') }}" required>
                            </div>
                            @error('registration_token')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom complet *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Adresse email *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Telephone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" autocomplete="tel">
                            </div>
                        </div>

                        <div class="col-md-6 role-field" data-role-field="etudiant">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}">
                            </div>
                        </div>

                        <div class="col-md-6 role-field" data-role-field="etudiant">
                            <label for="niveau_etude" class="form-label">Niveau d'etude *</label>
                            <select class="form-select @error('niveau_etude') is-invalid @enderror" id="niveau_etude" name="niveau_etude">
                                <option value="">Selectionner</option>
                                <option value="L1" @selected(old('niveau_etude') === 'L1')>L1</option>
                                <option value="L2" @selected(old('niveau_etude') === 'L2')>L2</option>
                                <option value="L3" @selected(old('niveau_etude') === 'L3')>L3</option>
                                <option value="M1" @selected(old('niveau_etude') === 'M1')>M1</option>
                                <option value="M2" @selected(old('niveau_etude') === 'M2')>M2</option>
                            </select>
                            @error('niveau_etude')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 role-field" data-role-field="etudiant">
                            <label for="filiere" class="form-label">Filiere *</label>
                            <input type="text" class="form-control @error('filiere') is-invalid @enderror" id="filiere" name="filiere" value="{{ old('filiere') }}">
                            @error('filiere')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 role-field" data-role-field="enseignant">
                            <label for="specialite" class="form-label">Specialite / Departement</label>
                            <input type="text" class="form-control @error('specialite') is-invalid @enderror" id="specialite" name="specialite" value="{{ old('specialite') }}">
                        </div>

                        <div class="col-md-6 role-field" data-role-field="entreprise">
                            <label for="nom_entreprise" class="form-label">Nom de l'entreprise *</label>
                            <input type="text" class="form-control @error('nom_entreprise') is-invalid @enderror" id="nom_entreprise" name="nom_entreprise" value="{{ old('nom_entreprise') }}">
                            @error('nom_entreprise')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 role-field" data-role-field="entreprise">
                            <label for="secteur_activite" class="form-label">Secteur d'activite</label>
                            <input type="text" class="form-control @error('secteur_activite') is-invalid @enderror" id="secteur_activite" name="secteur_activite" value="{{ old('secteur_activite') }}">
                        </div>

                        <div class="col-md-6 role-field" data-role-field="entreprise">
                            <label for="adresse_entreprise" class="form-label">Adresse</label>
                            <input type="text" class="form-control @error('adresse_entreprise') is-invalid @enderror" id="adresse_entreprise" name="adresse_entreprise" value="{{ old('adresse_entreprise') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                        <a href="{{ route('login') }}" class="text-primary fw-semibold">Se connecter</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-shield-alt me-2"></i>Recevoir le code OTP
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const fieldsWrap = document.getElementById('registration-fields');
    const roleFields = document.querySelectorAll('[data-role-field]');
    const requiredByRole = {
        etudiant: ['niveau_etude', 'filiere'],
        entreprise: ['nom_entreprise'],
        enseignant: []
    };

    function selectedRole() {
        const checked = document.querySelector('input[name="role"]:checked');
        return checked ? checked.value : '';
    }

    function updateFields() {
        const role = selectedRole();
        fieldsWrap.classList.toggle('is-visible', Boolean(role));

        roleFields.forEach(field => {
            field.style.display = field.dataset.roleField === role ? '' : 'none';
        });

        Object.values(requiredByRole).flat().forEach(id => {
            const input = document.getElementById(id);
            if (input) input.required = false;
        });

        (requiredByRole[role] || []).forEach(id => {
            const input = document.getElementById(id);
            if (input) input.required = true;
        });
    }

    roleInputs.forEach(input => input.addEventListener('change', updateFields));
    updateFields();
});
</script>
@endpush
@endsection
