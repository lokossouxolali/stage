@extends('layouts.app')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="auth-brand mb-3">
                <span class="brand-icon text-white"><i class="fas fa-lock"></i></span>
            </div>
            <h1 class="h4 fw-bold mb-1">Mot de passe oublié</h1>
            <p class="text-muted mb-0">Saisissez votre email pour recevoir un code de réinitialisation.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger small">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
                    <input type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email"
                        value="{{ old('email') }}"
                        required autocomplete="email" autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" data-loading-text="Envoi en cours...">
                <i class="fas fa-paper-plane me-2"></i>Envoyer le code OTP
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-muted small">
                <i class="fas fa-arrow-left me-1"></i>Retour à la connexion
            </a>
        </div>
    </div>
</div>
@endsection
