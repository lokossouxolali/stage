@extends('layouts.app')

@section('title', 'Etudiants encadres')
@section('page-title', 'Etudiants encadres')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-user-graduate me-2"></i>
            Mes etudiants encadres
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Etudiant</th>
                        <th>Niveau</th>
                        <th>Filiere</th>
                        <th>Propositions</th>
                        <th>Candidatures</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($etudiants as $etudiant)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $etudiant->name }}</div>
                                <small class="text-muted">{{ $etudiant->email }}</small>
                            </td>
                            <td>{{ $etudiant->niveau_etude ?? '-' }}</td>
                            <td>{{ $etudiant->filiere?->nom ?? '-' }}</td>
                            <td>{{ $etudiant->propositionsThemes->count() }}</td>
                            <td>{{ $etudiant->candidatures->count() }}</td>
                            <td>
                                <span class="badge bg-success">Encadrement accepte</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Aucun etudiant encadre pour le moment.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($etudiants->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $etudiants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
