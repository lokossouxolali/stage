@extends('layouts.app')

@section('title', 'Modifier la proposition')
@section('page-title', 'Modifier la proposition')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Modifier la proposition
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('propositions.update', $proposition) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="titre" class="form-label fw-bold">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $proposition->titre) }}" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $proposition->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="objectifs" class="form-label fw-bold">Objectifs</label>
                            <textarea class="form-control @error('objectifs') is-invalid @enderror" id="objectifs" name="objectifs" rows="3">{{ old('objectifs', $proposition->objectifs) }}</textarea>
                            @error('objectifs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="methodologie" class="form-label fw-bold">Methodologie</label>
                            <textarea class="form-control @error('methodologie') is-invalid @enderror" id="methodologie" name="methodologie" rows="3">{{ old('methodologie', $proposition->methodologie) }}</textarea>
                            @error('methodologie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="directeur_memoire_id" class="form-label fw-bold">Directeur de memoire</label>
                        <select class="form-select @error('directeur_memoire_id') is-invalid @enderror" id="directeur_memoire_id" name="directeur_memoire_id">
                            <option value="">Aucun directeur selectionne</option>
                            @foreach($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}" {{ (int) old('directeur_memoire_id', $proposition->directeur_memoire_id) === $enseignant->id ? 'selected' : '' }}>
                                    {{ $enseignant->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('directeur_memoire_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
