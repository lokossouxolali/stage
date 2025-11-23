@extends('layouts.app')

@section('title', 'Mes offres de stage')
@section('page-title', 'Mes offres de stage')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-briefcase me-2"></i>
                    Mes offres de stage
                </h5>
                <a href="{{ route('offres.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Publier une nouvelle offre
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tableau des offres -->
                <div class="table-responsive">
                    <table class="table table-hover" id="offresTable">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Durée</th>
                                <th>Niveau</th>
                                <th>Date de début</th>
                                <th>Statut</th>
                                <th>Candidatures</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($offres as $offre)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $offre->titre }}</div>
                                        <small class="text-muted">{{ Str::limit($offre->description, 60) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $offre->type_stage }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $offre->duree }} mois</div>
                                        @if($offre->date_fin)
                                            <small class="text-muted">
                                                Fin: {{ $offre->date_fin->format('d/m/Y') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $offre->niveau_etude }}</span>
                                    </td>
                                    <td>
                                        @if($offre->date_debut)
                                            <div class="fw-bold">{{ $offre->date_debut->format('d/m/Y') }}</div>
                                        @else
                                            <span class="text-muted">Non définie</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($offre->statut === 'active')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Active
                                            </span>
                                        @elseif($offre->statut === 'suspendue')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-pause me-1"></i>
                                                Brouillon
                                            </span>
                                        @elseif($offre->statut === 'fermee')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times me-1"></i>
                                                Fermée
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ ucfirst($offre->statut) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $nbCandidatures = $offre->candidatures->count();
                                        @endphp
                                        @if($nbCandidatures > 0)
                                            <a href="{{ route('candidatures.recues') }}?offre={{ $offre->id }}" 
                                               class="badge bg-primary text-decoration-none">
                                                <i class="fas fa-users me-1"></i>
                                                {{ $nbCandidatures }} candidature(s)
                                            </a>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-user-slash me-1"></i>
                                                Aucune
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('offres.show', $offre) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('offres.edit', $offre) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteOffre({{ $offre->id }})" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-3">Vous n'avez publié aucune offre de stage pour le moment</p>
                                        <a href="{{ route('offres.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Publier votre première offre
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($offres->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $offres->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette offre de stage ?</p>
                <p class="text-danger"><strong>Cette action est irréversible.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteOffre(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/offres/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection

