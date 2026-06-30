@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="auth-brand mb-3">
                <span class="brand-icon text-white"><i class="fas fa-key"></i></span>
            </div>
            <h1 class="h4 fw-bold mb-1">Nouveau mot de passe</h1>
            <p class="text-muted mb-0">Entrez le code reçu par email et choisissez un nouveau mot de passe.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success small">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <div class="mb-3">
                <label for="otp_code" class="form-label">Code OTP <span class="text-muted small">(reçu par email)</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-shield-halved text-primary"></i></span>
                    <input type="text" inputmode="numeric" pattern="\d{6}" maxlength="6"
                        class="form-control text-center fw-bold fs-5 tracking-widest @error('otp_code') is-invalid @enderror"
                        id="otp_code" name="otp_code"
                        value="{{ old('otp_code') }}"
                        placeholder="000000" required autocomplete="one-time-code">
                    @error('otp_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                    <input type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password" name="password"
                        minlength="8" required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text">Minimum 8 caractères.</div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                    <input type="password"
                        class="form-control"
                        id="password_confirmation" name="password_confirmation"
                        required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" data-loading-text="Réinitialisation...">
                <i class="fas fa-check me-2"></i>Réinitialiser le mot de passe
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('password.request') }}" class="text-muted small">
                <i class="fas fa-arrow-left me-1"></i>Renvoyer un code
            </a>
        </div>
    </div>
</div>
@endsection
