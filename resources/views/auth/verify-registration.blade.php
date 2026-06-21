@extends('layouts.app')

@section('title', 'Verification inscription')

@section('content')
<div class="card auth-card">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
            <span class="brand-icon text-white mx-auto mb-3"><i class="fas fa-envelope-open-text"></i></span>
            <h1 class="h4 fw-bold mb-1">Verifier votre email</h1>
            <p class="text-muted mb-0">Entrez le code OTP envoye a {{ $pending->email }}.</p>
        </div>

        <form method="POST" action="{{ route('register.verify.submit') }}">
            @csrf
            <div class="mb-3">
                <label for="otp_code" class="form-label">Code OTP</label>
                <input type="text" inputmode="numeric" maxlength="6" class="form-control form-control-lg text-center @error('otp_code') is-invalid @enderror" id="otp_code" name="otp_code" required autofocus>
                @error('otp_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info small">
                Le code expire le {{ $pending->otp_expires_at->format('d/m/Y H:i') }}.
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-check me-2"></i>Valider mon inscription
            </button>
        </form>

        <form method="POST" action="{{ route('register.verify.resend') }}" class="text-center mt-3">
            @csrf
            <span class="text-muted small">Vous n'avez pas recu le code ?</span>
            <button type="submit" class="btn btn-link btn-sm p-0 align-baseline fw-semibold">
                Renvoyer le code OTP
            </button>
        </form>
    </div>
</div>
@endsection
