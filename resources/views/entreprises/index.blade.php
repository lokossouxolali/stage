@extends('layouts.app')

@section('title', 'Gestion des entreprises')
@section('page-title', 'Gestion des entreprises')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-building me-2 text-primary"></i>Liste des entreprises</span>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('entreprises.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>Ajouter une entreprise
                </a>
                @endif
            </div>
            <div class="card-body pb-0">
                <div class="row g-2 mb-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Rechercher par nom, email ou secteur...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="secteurFilter">
                            <option value="">Tous les secteurs</option>
                            <option value="Informatique">Informatique</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Ressources Humaines">Ressources Humaines</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary btn-sm w-100" onclick="resetFilters()">
                            <i class="fas fa-rotate-left me-1"></i>Réinitialiser
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="entreprisesTable">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Secteur</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entreprises as $entreprise)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $entreprise->nom }}</div>
                                    @if($entreprise->site_web)
                                        <small class="text-muted">
                                            <i class="fas fa-globe me-1"></i>
                                            <a href="{{ $entreprise->site_web }}" target="_blank" class="text-decoration-none">{{ $entreprise->site_web }}</a>
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-envelope me-1 text-muted"></i>{{ $entreprise->email }}
                                </td>
                                <td>
                                    @if($entreprise->telephone)
                                        <i class="fas fa-phone me-1 text-muted"></i>{{ $entreprise->telephone }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($entreprise->secteur_activite)
                                        <span class="badge-soft badge-soft-info">{{ $entreprise->secteur_activite }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('entreprises.show', $entreprise) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                        <a href="{{ route('entreprises.edit', $entreprise) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteEntreprise({{ $entreprise->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-building fa-2x mb-3 d-block"></i>
                                    Aucune entreprise trouvée.
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('entreprises.create') }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus me-1"></i>Ajouter la première entreprise
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($entreprises->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $entreprises->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin())
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette entreprise ?</p>
                <p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle me-1"></i><strong>Cette action est irréversible.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
@if(auth()->user()->isAdmin())
function deleteEntreprise(id) {
    document.getElementById('deleteForm').action = `/entreprises/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
@endif

const searchInput = document.getElementById('searchInput');
const secteurFilter = document.getElementById('secteurFilter');

function filterTable() {
    const search = searchInput.value.toLowerCase();
    const secteur = secteurFilter.value;
    document.querySelectorAll('#entreprisesTable tbody tr').forEach(row => {
        const cells = row.getElementsByTagName('td');
        if (!cells.length) return;
        const nom   = cells[0].textContent.toLowerCase();
        const email = cells[1].textContent.toLowerCase();
        const sec   = cells[3].textContent;
        const matchSearch  = nom.includes(search) || email.includes(search);
        const matchSecteur = !secteur || sec.includes(secteur);
        row.style.display = matchSearch && matchSecteur ? '' : 'none';
    });
}

function resetFilters() {
    searchInput.value = '';
    secteurFilter.value = '';
    filterTable();
}

searchInput.addEventListener('input', filterTable);
secteurFilter.addEventListener('change', filterTable);
</script>
@endpush
@endsection
