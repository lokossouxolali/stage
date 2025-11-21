@extends('layouts.app')

@section('title', 'Modifier mon Profil')
@section('page-title', 'Modifier mon Profil')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 text-muted fw-normal">
                    <i class="fas fa-user-edit me-2"></i>
                    Modifier mon Profil
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row g-3">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold mb-2" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">
                                Photo de profil
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="profile-photo-preview">
                                    @if(auth()->user()->photo_path && auth()->user()->photo_url)
                                        <img src="{{ auth()->user()->photo_url }}" alt="Photo de profil" 
                                             class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e5e7eb;"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="profile-avatar-preview rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; font-size: 1.75rem; font-weight: 600; display: none;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="profile-avatar-preview rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; font-size: 1.75rem; font-weight: 600;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control form-control-sm @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif"
                                           onchange="previewPhoto(this)">
                                    <small class="text-muted" style="font-size: 0.7rem;">Formats acceptés : JPG, PNG, GIF (max 2MB)</small>
                                    @error('photo')
                                        <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">
                                Nom complet <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required 
                                   style="font-size: 0.85rem;">
                            @error('name')
                                <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                   style="font-size: 0.85rem;">
                            @error('email')
                                <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(auth()->user()->telephone !== null)
                        <div class="col-md-6">
                            <label for="telephone" class="form-label fw-semibold mb-1" style="font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">
                                Téléphone
                            </label>
                            <input type="text" class="form-control form-control-sm @error('telephone') is-invalid @enderror" 
                                   id="telephone" name="telephone" value="{{ old('telephone', auth()->user()->telephone) }}"
                                   style="font-size: 0.85rem;">
                            @error('telephone')
                                <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fas fa-save me-1"></i>
                            Mettre à jour
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
        background-color: #1f2937;
        border-color: #1f2937;
        color: #ffffff;
    }

    .btn-dark:hover {
        background-color: #111827;
        border-color: #111827;
        color: #ffffff;
    }

    .btn-outline-secondary {
        border-color: #d1d5db;
        color: #6b7280;
    }

    .btn-outline-secondary:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
        color: #374151;
    }

    .form-control-sm {
        padding: 0.375rem 0.75rem;
    }
</style>
@push('scripts')
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.profile-photo-preview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Photo de profil" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e5e7eb;">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
