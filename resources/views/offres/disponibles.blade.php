@extends('layouts.app')

@section('title', 'Offres disponibles')
@section('page-title', 'Offres disponibles')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-briefcase me-2"></i>
                    Offres ouvertes aux candidatures
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Entreprise</th>
                                <th>Niveau</th>
                                <th>Duree</th>
                                <th>Date limite</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($offres as $offre)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $offre->titre }}</div>
                                        <small class="text-muted">{{ Str::limit($offre->description, 80) }}</small>
                                    </td>
                                    <td>{{ $offre->entreprise->nom ?? '-' }}</td>
                                    <td><span class="badge bg-secondary">{{ $offre->niveau_etude ?? '-' }}</span></td>
                                    <td>{{ $offre->duree ? $offre->duree . ' mois' : '-' }}</td>
                                    <td>{{ $offre->date_limite_candidature ? $offre->date_limite_candidature->format('d/m/Y') : 'Non definie' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('offres.show', $offre) }}" class="action-button" title="Voir l'offre">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('candidatures.create', $offre) }}" class="action-button" title="Postuler" style="color:#0b1f4d;">
                                            <i class="fas fa-paper-plane"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Aucune offre active disponible pour le moment.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($offres->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $offres->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
