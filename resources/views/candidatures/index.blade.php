@extends('layouts.app')

@section('title', 'Mes candidatures')
@section('page-title', 'Mes candidatures')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Mes candidatures
                </h5>
                <a href="{{ route('candidatures.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Nouvelle candidature
                </a>
            </div>
            <div class="card-body">
                <!-- Filtres -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par titre d'offre, entreprise...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statutFilter">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente">En attente</option>
                            <option value="acceptee">Acceptée</option>
                            <option value="refusee">Refusée</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="typeFilter">
                            <option value="">Tous les types</option>
                            <option value="Obligatoire">Obligatoire</option>
                            <option value="Facultatif">Facultatif</option>
                            <option value="PFE">PFE</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                            <i class="fas fa-refresh me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Tableau des candidatures -->
                <div class="table-responsive">
                    <table class="table table-hover" id="candidaturesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Offre</th>
                                <th>Entreprise</th>
                                <th>Type</th>
                                <th>Durée</th>
                                <th>Date candidature</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidatures as $candidature)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $candidature->offre->titre }}</div>
                                        <small class="text-muted">{{ Str::limit($candidature->offre->description, 50) }}</small>
                                        @if($candidature->offre->remuneration)
                                            <div class="mt-1">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-money-bill-wave me-1"></i>
                                                    {{ $candidature->offre->remuneration }}€/mois
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($candidature->offre->entreprise->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $candidature->offre->entreprise->nom }}</div>
                                                <small class="text-muted">{{ $candidature->offre->entreprise->secteur_activite }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $candidature->offre->type_stage }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $candidature->offre->duree }} mois</div>
                                        @if($candidature->offre->date_debut)
                                            <small class="text-muted">
                                                Début: {{ $candidature->offre->date_debut->format('d/m/Y') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ $candidature->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($candidature->statut === 'en_attente')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                En attente
                                            </span>
                                        @elseif($candidature->statut === 'acceptee')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Acceptée
                                            </span>
                                        @elseif($candidature->statut === 'refusee')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>
                                                Refusée
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('candidatures.show', $candidature) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($candidature->statut === 'en_attente')
                                                <a href="{{ route('candidatures.edit', $candidature) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteCandidature({{ $candidature->id }})" 
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucune candidature trouvée</p>
                                        <a href="{{ route('candidatures.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Créer ma première candidature
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($candidatures->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $candidatures->links() }}
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
                <p>Êtes-vous sûr de vouloir supprimer cette candidature ?</p>
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
function deleteCandidature(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/candidatures/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Filtrage en temps réel
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('statutFilter').addEventListener('change', filterTable);
document.getElementById('typeFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statutFilter = document.getElementById('statutFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const table = document.getElementById('candidaturesTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue;
        
        const offre = cells[0].textContent.toLowerCase();
        const entreprise = cells[1].textContent.toLowerCase();
        const type = cells[2].textContent;
        const statut = cells[5].textContent;
        
        const matchesSearch = offre.includes(searchTerm) || entreprise.includes(searchTerm);
        const matchesStatut = !statutFilter || 
            (statutFilter === 'en_attente' && statut.includes('En attente')) ||
            (statutFilter === 'acceptee' && statut.includes('Acceptée')) ||
            (statutFilter === 'refusee' && statut.includes('Refusée'));
        const matchesType = !typeFilter || type.includes(typeFilter);
        
        if (matchesSearch && matchesStatut && matchesType) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statutFilter').value = '';
    document.getElementById('typeFilter').value = '';
    filterTable();
}
</script>
@endpush
@endsection
