@extends('layouts.app')

@section('title', 'Gestion des soutenances')
@section('page-title', 'Gestion des soutenances')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-microphone me-2"></i>
                    Liste des soutenances
                </h5>
                @if(auth()->user()->isAdmin() || auth()->user()->isEnseignant())
                    <a href="{{ route('soutenances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Nouvelle soutenance
                    </a>
                @endif
            </div>
            <div class="card-body">
                <!-- Filtres -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par étudiant, stage...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statutFilter">
                            <option value="">Tous les statuts</option>
                            <option value="planifiee">Planifiée</option>
                            <option value="en_cours">En cours</option>
                            <option value="terminee">Terminée</option>
                            <option value="annulee">Annulée</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="typeFilter">
                            <option value="">Tous les types</option>
                            <option value="Intermediaire">Intermédiaire</option>
                            <option value="Finale">Finale</option>
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

                <!-- Tableau des soutenances -->
                <div class="table-responsive">
                    <table class="table table-hover" id="soutenancesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Stage</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Lieu</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($soutenances as $soutenance)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($soutenance->stage->candidature->etudiant->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $soutenance->stage->candidature->etudiant->name }}</div>
                                                <small class="text-muted">{{ $soutenance->stage->candidature->etudiant->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $soutenance->stage->candidature->offre->titre }}</div>
                                        <small class="text-muted">{{ $soutenance->stage->candidature->offre->entreprise->nom }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $soutenance->type }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            @if($soutenance->date_soutenance)
                                                {{ $soutenance->date_soutenance->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">Non définie</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($soutenance->heure_debut)
                                            <div class="fw-bold">{{ $soutenance->heure_debut }}</div>
                                            @if($soutenance->heure_fin)
                                                <small class="text-muted">- {{ $soutenance->heure_fin }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Non définie</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($soutenance->lieu)
                                            <div class="fw-bold">{{ $soutenance->lieu }}</div>
                                        @else
                                            <span class="text-muted">Non défini</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($soutenance->statut === 'planifiee')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-calendar me-1"></i>
                                                Planifiée
                                            </span>
                                        @elseif($soutenance->statut === 'en_cours')
                                            <span class="badge bg-info">
                                                <i class="fas fa-play me-1"></i>
                                                En cours
                                            </span>
                                        @elseif($soutenance->statut === 'terminee')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Terminée
                                            </span>
                                        @elseif($soutenance->statut === 'annulee')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>
                                                Annulée
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('soutenances.show', $soutenance) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin() || auth()->user()->isEnseignant())
                                                <a href="{{ route('soutenances.edit', $soutenance) }}" 
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
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-microphone fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucune soutenance trouvée</p>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isEnseignant())
                                            <a href="{{ route('soutenances.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>
                                                Créer la première soutenance
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($soutenances->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $soutenances->links() }}
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
    const table = document.getElementById('soutenancesTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue;
        
        const etudiant = cells[0].textContent.toLowerCase();
        const stage = cells[1].textContent.toLowerCase();
        const type = cells[2].textContent;
        const statut = cells[6].textContent;
        
        const matchesSearch = etudiant.includes(searchTerm) || stage.includes(searchTerm);
        const matchesStatut = !statutFilter || 
            (statutFilter === 'planifiee' && statut.includes('Planifiée')) ||
            (statutFilter === 'en_cours' && statut.includes('En cours')) ||
            (statutFilter === 'terminee' && statut.includes('Terminée')) ||
            (statutFilter === 'annulee' && statut.includes('Annulée'));
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
