@extends('layouts.app')

@section('title', 'Gestion des évaluations')
@section('page-title', 'Gestion des évaluations')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2"></i>
                    Liste des évaluations
                </h5>
                @if(auth()->user()->isAdmin() || auth()->user()->isEnseignant() || auth()->user()->isEntreprise())
                    <a href="{{ route('evaluations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Nouvelle évaluation
                    </a>
                @endif
            </div>
            <div class="card-body">
                <!-- Filtres -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par étudiant, évaluateur...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="typeFilter">
                            <option value="">Tous les types</option>
                            <option value="Technique">Technique</option>
                            <option value="Comportementale">Comportementale</option>
                            <option value="Finale">Finale</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statutFilter">
                            <option value="">Tous les statuts</option>
                            <option value="en_cours">En cours</option>
                            <option value="terminee">Terminée</option>
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

                <!-- Tableau des évaluations -->
                <div class="table-responsive">
                    <table class="table table-hover" id="evaluationsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Stage</th>
                                <th>Évaluateur</th>
                                <th>Type</th>
                                <th>Note</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evaluations as $evaluation)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($evaluation->stage->candidature->etudiant->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $evaluation->stage->candidature->etudiant->name }}</div>
                                                <small class="text-muted">{{ $evaluation->stage->candidature->etudiant->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $evaluation->stage->candidature->offre->titre }}</div>
                                        <small class="text-muted">{{ $evaluation->stage->candidature->offre->entreprise->nom }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($evaluation->evaluateur->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $evaluation->evaluateur->name }}</div>
                                                <small class="text-muted">{{ ucfirst($evaluation->evaluateur->role) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $evaluation->type }}</span>
                                    </td>
                                    <td>
                                        @if($evaluation->note)
                                            <div class="fw-bold">
                                                <span class="badge bg-{{ $evaluation->note >= 16 ? 'success' : ($evaluation->note >= 12 ? 'warning' : 'danger') }}">
                                                    {{ $evaluation->note }}/20
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-muted">Non noté</span>
                                        @endif
                                    </td>
                                    <td>{{ $evaluation->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($evaluation->statut === 'en_cours')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                En cours
                                            </span>
                                        @elseif($evaluation->statut === 'terminee')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Terminée
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('evaluations.show', $evaluation) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin() || $evaluation->evaluateur_id === auth()->id())
                                                <a href="{{ route('evaluations.edit', $evaluation) }}" 
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
                                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucune évaluation trouvée</p>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isEnseignant() || auth()->user()->isEntreprise())
                                            <a href="{{ route('evaluations.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>
                                                Créer la première évaluation
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($evaluations->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $evaluations->links() }}
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
document.getElementById('typeFilter').addEventListener('change', filterTable);
document.getElementById('statutFilter').addEventListener('change', filterTable);
document.getElementById('dateFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const statutFilter = document.getElementById('statutFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const table = document.getElementById('evaluationsTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue;
        
        const etudiant = cells[0].textContent.toLowerCase();
        const stage = cells[1].textContent.toLowerCase();
        const evaluateur = cells[2].textContent.toLowerCase();
        const type = cells[3].textContent;
        const statut = cells[6].textContent;
        
        const matchesSearch = etudiant.includes(searchTerm) || stage.includes(searchTerm) || evaluateur.includes(searchTerm);
        const matchesType = !typeFilter || type.includes(typeFilter);
        const matchesStatut = !statutFilter || 
            (statutFilter === 'en_cours' && statut.includes('En cours')) ||
            (statutFilter === 'terminee' && statut.includes('Terminée'));
        
        if (matchesSearch && matchesType && matchesStatut) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('statutFilter').value = '';
    document.getElementById('dateFilter').value = '';
    filterTable();
}
</script>
@endpush
@endsection
