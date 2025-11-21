@extends('layouts.app')

@section('title', 'Gestion des entreprises')
@section('page-title', 'Gestion des entreprises')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building me-2"></i>
                    Liste des entreprises
                </h5>
                <a href="{{ route('entreprises.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Ajouter une entreprise
                </a>
            </div>
            <div class="card-body">
                <!-- Filtres -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par nom, email ou secteur...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="secteurFilter">
                            <option value="">Tous les secteurs</option>
                            <option value="Informatique">Informatique</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Ressources Humaines">Ressources Humaines</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statutFilter">
                            <option value="">Tous les statuts</option>
                            <option value="1">Vérifiées</option>
                            <option value="0">Non vérifiées</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                            <i class="fas fa-refresh me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Tableau des entreprises -->
                <div class="table-responsive">
                    <table class="table table-hover" id="entreprisesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Secteur</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entreprises as $entreprise)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($entreprise->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $entreprise->nom }}</div>
                                                @if($entreprise->site_web)
                                                    <small class="text-muted">
                                                        <i class="fas fa-globe me-1"></i>
                                                        <a href="{{ $entreprise->site_web }}" target="_blank" class="text-decoration-none">
                                                            {{ $entreprise->site_web }}
                                                        </a>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope me-1 text-muted"></i>
                                        {{ $entreprise->email }}
                                    </td>
                                    <td>
                                        @if($entreprise->telephone)
                                            <i class="fas fa-phone me-1 text-muted"></i>
                                            {{ $entreprise->telephone }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $entreprise->secteur_activite }}</span>
                                    </td>
                                    <td>
                                        @if($entreprise->est_verifiee)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Vérifiée
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('entreprises.show', $entreprise) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('entreprises.edit', $entreprise) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteEntreprise({{ $entreprise->id }})" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucune entreprise trouvée</p>
                                        <a href="{{ route('entreprises.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Ajouter la première entreprise
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($entreprises->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $entreprises->links() }}
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
                <p>Êtes-vous sûr de vouloir supprimer cette entreprise ?</p>
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

@push('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
    font-weight: bold;
}
</style>
@endpush

@push('scripts')
<script>
function deleteEntreprise(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/entreprises/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Filtrage en temps réel
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('secteurFilter').addEventListener('change', filterTable);
document.getElementById('statutFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const secteurFilter = document.getElementById('secteurFilter').value;
    const statutFilter = document.getElementById('statutFilter').value;
    const table = document.getElementById('entreprisesTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue;
        
        const nom = cells[0].textContent.toLowerCase();
        const email = cells[1].textContent.toLowerCase();
        const secteur = cells[3].textContent;
        const statut = cells[4].textContent;
        
        const matchesSearch = nom.includes(searchTerm) || email.includes(searchTerm);
        const matchesSecteur = !secteurFilter || secteur.includes(secteurFilter);
        const matchesStatut = !statutFilter || 
            (statutFilter === '1' && statut.includes('Vérifiée')) ||
            (statutFilter === '0' && statut.includes('En attente'));
        
        if (matchesSearch && matchesSecteur && matchesStatut) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('secteurFilter').value = '';
    document.getElementById('statutFilter').value = '';
    filterTable();
}
</script>
@endpush
@endsection
