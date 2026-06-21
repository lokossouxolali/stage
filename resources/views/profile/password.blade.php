@extends('layouts.app')

@section('title', 'Changer le Mot de Passe')
@section('page-title', 'Changer le Mot de Passe')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 text-muted fw-normal">
                    <i class="fas fa-key me-2"></i>
                    Changer le Mot de Passe
                </h6>
            </div>
            <div class="card-body p-4">
                @if(session('password_change_pending') || $errors->has('otp_code'))
                    <form method="POST" action="{{ route('profile.password.update') }}" class="mb-4">
                        @csrf
                        @method('PATCH')

                        <div class="alert alert-info" style="font-size: 0.8rem; padding: 0.75rem;">
                            <i class="fas fa-shield-alt me-2"></i>
                            Entrez le code OTP recu par email pour appliquer le nouveau mot de passe.
                        </div>

                        <div class="mb-3">
                            <label for="otp_code" class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">
                                Code OTP <span class="text-danger">*</span>
                            </label>
                            <input type="text" inputmode="numeric" maxlength="6" class="form-control form-control-sm @error('otp_code') is-invalid @enderror"
                                   id="otp_code" name="otp_code" required autofocus
                                   style="font-size: 0.85rem;">
                            @error('otp_code')
                                <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fas fa-check me-1"></i>
                            Valider le OTP
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">
                            Mot de passe actuel <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control form-control-sm @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required
                               style="font-size: 0.85rem;">
                        @error('current_password')
                            <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">
                            Nouveau mot de passe <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" 
                               id="password" name="password" required
                               style="font-size: 0.85rem;">
                        @error('password')
                            <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">
                            Confirmer le nouveau mot de passe <span class="text-danger">*</span>
                        </label>
                        <input type="password" class="form-control form-control-sm" 
                               id="password_confirmation" name="password_confirmation" required
                               style="font-size: 0.85rem;">
                    </div>
                    
                    <div class="alert alert-info mb-4" style="font-size: 0.8rem; padding: 0.75rem;">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Conseil :</strong> Utilisez un mot de passe fort avec au moins 8 caractères, incluant des lettres, des chiffres et des symboles.
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fas fa-save me-1"></i>
                            Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: 1px solid #e5e7eb;
    }

    .btn-dark {
        background-color: #081a3d;
        border-color: #081a3d;
        color: #ffffff;
    }

    .btn-dark:hover {
        background-color: #111827;
        border-color: #111827;
        color: #ffffff;
    }

    .btn-outline-secondary {
        border-color: #d1d5db;
        color: #31527f;
    }

    .btn-outline-secondary:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
        color: #123a7a;
    }

    .form-control-sm {
        padding: 0.375rem 0.75rem;
    }

    .alert-info {
        background-color: #eff6ff;
        border-color: #bfdbfe;
        color: #1e40af;
    }
</style>
@endpush
