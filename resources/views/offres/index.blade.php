@extends('layouts.app')

@section('title', 'Offres de stage')
@section('page-title', 'Offres de stage')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-briefcase me-2"></i>
                    Liste des offres de stage
                </h5>
                @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
                    <a href="{{ route('offres.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Publier une offre
                    </a>
                @endif
            </div>
            <div class="card-body">
                <!-- Filtres -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par titre, entreprise...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="typeFilter">
                            <option value="">Tous les types</option>
                            <option value="Obligatoire">Obligatoire</option>
                            <option value="Facultatif">Facultatif</option>
                            <option value="PFE">PFE</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="niveauFilter">
                            <option value="">Tous les niveaux</option>
                            <option value="L1">L1</option>
                            <option value="L2">L2</option>
                            <option value="L3">L3</option>
                            <option value="M1">M1</option>
                            <option value="M2">M2</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statutFilter">
                            <option value="">Tous les statuts</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="en_attente">En attente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                            <i class="fas fa-refresh me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Tableau des offres -->
                <div class="table-responsive">
                    <table class="table table-hover" id="offresTable">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Entreprise</th>
                                <th>Type</th>
                                <th>Durée</th>
                                <th>Niveau</th>
                                <th>Statut</th>
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
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($offre->entreprise->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $offre->entreprise->nom }}</div>
                                                <small class="text-muted">{{ $offre->entreprise->secteur_activite }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $offre->type_stage }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $offre->duree }} mois</div>
                                        @if($offre->date_debut)
                                            <small class="text-muted">
                                                Début: {{ $offre->date_debut->format('d/m/Y') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $offre->niveau_etude }}</span>
                                    </td>
                                    <td>
                                        @if($offre->statut === 'active')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Active
                                            </span>
                                        @elseif($offre->statut === 'inactive')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-pause me-1"></i>
                                                Inactive
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
                                            <a href="{{ route('offres.show', $offre) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
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
                                            @endif
                                            @if(auth()->user()->isEtudiant() && $offre->statut === 'active')
                                                <a href="{{ route('candidatures.create', $offre) }}" 
                                                   class="btn btn-sm btn-success" 
                                                   title="Postuler">
                                                    <i class="fas fa-paper-plane"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucune offre de stage trouvée</p>
                                        @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
                                            <a href="{{ route('offres.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>
                                                Publier la première offre
                                            </a>
                                        @endif
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
function deleteOffre(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/offres/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Filtrage en temps réel
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('typeFilter').addEventListener('change', filterTable);
document.getElementById('niveauFilter').addEventListener('change', filterTable);
document.getElementById('statutFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const niveauFilter = document.getElementById('niveauFilter').value;
    const statutFilter = document.getElementById('statutFilter').value;
    const table = document.getElementById('offresTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue;
        
        const titre = cells[0].textContent.toLowerCase();
        const entreprise = cells[1].textContent.toLowerCase();
        const type = cells[2].textContent;
        const niveau = cells[4].textContent;
        const statut = cells[5].textContent;
        
        const matchesSearch = titre.includes(searchTerm) || entreprise.includes(searchTerm);
        const matchesType = !typeFilter || type.includes(typeFilter);
        const matchesNiveau = !niveauFilter || niveau.includes(niveauFilter);
        const matchesStatut = !statutFilter || 
            (statutFilter === 'active' && statut.includes('Active')) ||
            (statutFilter === 'inactive' && statut.includes('Inactive')) ||
            (statutFilter === 'en_attente' && statut.includes('En attente'));
        
        if (matchesSearch && matchesType && matchesNiveau && matchesStatut) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('niveauFilter').value = '';
    document.getElementById('statutFilter').value = '';
    filterTable();
}
</script>
@endpush
@endsection
