@extends('layouts.app')

@section('title', 'Liste des enseignants')
@section('page-title', 'Liste des enseignants')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-chalkboard-teacher me-2"></i>
            Enseignants disponibles
        </h5>
        <a href="{{ route('users.choisir-directeur-memoire') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-user-tie me-1"></i>
            Choisir un directeur
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @forelse($enseignants as $enseignant)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 52px; height: 52px; background: #0b1f4d; color: #ffffff; font-weight: 700;">
                                    {{ strtoupper(substr($enseignant->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $enseignant->name }}</div>
                                    <small class="text-muted">{{ $enseignant->email }}</small>
                                    @if($enseignant->telephone)
                                        <div><small class="text-muted">{{ $enseignant->telephone }}</small></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Aucun enseignant disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
