@extends('layouts.app')

@section('title', 'Modifier l\'Utilisateur')
@section('page-title', 'Modifier l\'Utilisateur')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-edit me-2"></i>
                    Modifier {{ $user->name }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Photo de profil</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="profile-photo-preview">
                                    @if($user->photo_path && $user->photo_url)
                                        <img src="{{ $user->photo_url }}" alt="Photo de profil" 
                                             class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e5e7eb;"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; font-size: 1.75rem; font-weight: 600; display: none;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; font-size: 1.75rem; font-weight: 600;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
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

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            <option value="etudiant" {{ old('role', $user->role) == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                            <option value="entreprise" {{ old('role', $user->role) == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                            <option value="enseignant" {{ old('role', $user->role) == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note :</strong> Pour changer le mot de passe, utilisez la fonctionnalité de réinitialisation de mot de passe.
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

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
