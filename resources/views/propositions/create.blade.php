@extends('layouts.app')

@section('title', 'Soumettre une proposition de thème')
@section('page-title', 'Soumettre une proposition de thème')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            {{-- <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Nouvelle proposition de thème
                </h5>
            </div> --}}
            <div class="card-body">
                <form action="{{ route('propositions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Informations générales
                            </h6>
                        </div>
                    </div> --}}

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="titre" class="form-label fw-bold">Titre de la proposition <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror"
                                   id="titre" name="titre" value="{{ old('titre') }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="description" class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="objectifs" class="form-label fw-bold">Objectifs</label>
                            <textarea class="form-control @error('objectifs') is-invalid @enderror"
                                      id="objectifs" name="objectifs" rows="3">{{ old('objectifs') }}</textarea>
                            @error('objectifs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="methodologie" class="form-label fw-bold">Méthodologie</label>
                            <textarea class="form-control @error('methodologie') is-invalid @enderror"
                                      id="methodologie" name="methodologie" rows="3">{{ old('methodologie') }}</textarea>
                            @error('methodologie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-users me-2"></i>
                                Destinataires
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input @error('envoyer_au_directeur') is-invalid @enderror" type="checkbox" id="envoyer_au_directeur" name="envoyer_au_directeur" value="1" @checked(old('envoyer_au_directeur'))>
                                        <label class="form-check-label fw-bold" for="envoyer_au_directeur">
                                            Directeur de Mémoire (DM)
                                        </label>
                                        <div class="form-text">
                                            @if($directeur)
                                                {{ $directeur->name }}
                                            @else
                                                Aucun Directeur de Mémoire sélectionné.
                                            @endif
                                        </div>
                                        @error('envoyer_au_directeur')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input" type="checkbox" id="envoyer_a_l_admin" name="envoyer_a_l_admin" value="1" @checked(old('envoyer_a_l_admin'))>
                                        <label class="form-check-label fw-bold" for="envoyer_a_l_admin">
                                            Responsable pedagogique
                                        </label>
                                        <div class="form-text">Envoyer aussi à l'administration.</div>
                                    </div>
                                </div>
                            </div>
                            @error('destinataires')
                                <div class="alert alert-danger mt-3 mb-0">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-file-upload me-2"></i>
                                Documents à joindre
                            </h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fiche_stage" class="form-label fw-bold">Fiche de stage remplie <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('fiche_stage') is-invalid @enderror"
                                   id="fiche_stage" name="fiche_stage" accept=".pdf,.doc,.docx" required>
                            <div class="form-text">Formats acceptés : PDF, DOC, DOCX (max 5MB)</div>
                            @error('fiche_stage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="proposition_theme" class="form-label fw-bold">Document de proposition de thème <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('proposition_theme') is-invalid @enderror"
                                   id="proposition_theme" name="proposition_theme" accept=".pdf,.doc,.docx" required>
                            <div class="form-text">Formats acceptés : PDF, DOC, DOCX (max 5MB)</div>
                            @error('proposition_theme')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>
                                Soumettre la proposition
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
