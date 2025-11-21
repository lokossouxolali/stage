@extends('layouts.app')

@section('title', 'Gestion des stages')
@section('page-title', 'Gestion des stages')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Liste des stages
                </h5>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('stages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Créer un stage
                    </a>
                @endif
            </div>
            <div class="card-body">
                <!-- Filtres -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par étudiant, entreprise...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statutFilter">
                            <option value="">Tous les statuts</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                            <option value="annule">Annulé</option>
                        </select>
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
                        <input type="date" class="form-control" id="dateFilter" placeholder="Filtrer par date">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                            <i class="fas fa-refresh me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Tableau des stages -->
                <div class="table-responsive">
                    <table class="table table-hover" id="stagesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Entreprise</th>
                                <th>Offre</th>
                                <th>Type</th>
                                <th>Période</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stages as $stage)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($stage->candidature->etudiant->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $stage->candidature->etudiant->name }}</div>
                                                <small class="text-muted">{{ $stage->candidature->etudiant->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($stage->candidature->offre->entreprise->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $stage->candidature->offre->entreprise->nom }}</div>
                                                <small class="text-muted">{{ $stage->candidature->offre->entreprise->secteur_activite }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $stage->candidature->offre->titre }}</div>
                                        <small class="text-muted">{{ Str::limit($stage->candidature->offre->description, 40) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $stage->candidature->offre->type_stage }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            @if($stage->date_debut)
                                                {{ $stage->date_debut->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">Non défini</span>
                                            @endif
                                        </div>
                                        @if($stage->date_fin)
                                            <small class="text-muted">
                                                au {{ $stage->date_fin->format('d/m/Y') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($stage->statut === 'en_cours')
                                            <span class="badge bg-success">
                                                <i class="fas fa-play me-1"></i>
                                                En cours
                                            </span>
                                        @elseif($stage->statut === 'termine')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-check me-1"></i>
                                                Terminé
                                            </span>
                                        @elseif($stage->statut === 'annule')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>
                                                Annulé
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('stages.show', $stage) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin() || auth()->user()->isEntreprise())
                                                <a href="{{ route('stages.edit', $stage) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun stage trouvé</p>
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('stages.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>
                                                Créer le premier stage
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($stages->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $stages->links() }}
                    </div>
                @endif
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
// Filtrage en temps réel
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('statutFilter').addEventListener('change', filterTable);
document.getElementById('typeFilter').addEventListener('change', filterTable);
document.getElementById('dateFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statutFilter = document.getElementById('statutFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const table = document.getElementById('stagesTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue;
        
        const etudiant = cells[0].textContent.toLowerCase();
        const entreprise = cells[1].textContent.toLowerCase();
        const offre = cells[2].textContent.toLowerCase();
        const type = cells[3].textContent;
        const statut = cells[5].textContent;
        
        const matchesSearch = etudiant.includes(searchTerm) || entreprise.includes(searchTerm) || offre.includes(searchTerm);
        const matchesStatut = !statutFilter || 
            (statutFilter === 'en_cours' && statut.includes('En cours')) ||
            (statutFilter === 'termine' && statut.includes('Terminé')) ||
            (statutFilter === 'annule' && statut.includes('Annulé'));
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
    document.getElementById('dateFilter').value = '';
    filterTable();
}
</script>
@endpush
@endsection
