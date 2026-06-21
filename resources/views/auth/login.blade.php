@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="auth-brand mb-3">
                <span class="brand-icon text-white"><i class="fas fa-layer-group"></i></span>
            </div>
            <h1 class="h4 fw-bold mb-1">Connexion</h1>
            <p class="text-muted mb-0">Accedez a votre espace de gestion.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password">
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" data-loading-text="Connexion...">
                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
            </button>
        </form>

        <div class="text-center mt-4">
            <span class="text-muted">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" class="fw-semibold text-primary">Creer un compte</a>
        </div>
    </div>
</div>
@endsection
