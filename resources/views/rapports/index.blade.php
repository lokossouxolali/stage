@extends('layouts.app')

@section('title', 'Gestion des rapports')
@section('page-title', 'Gestion des rapports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-pdf me-2"></i>
                    Liste des rapports
                </h5>
                @if(auth()->user()->isEtudiant())
                    <a href="{{ route('rapports.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Nouveau rapport
                    </a>
                @endif
            </div>
            <div class="card-body">
                <!-- Filtres -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par titre, étudiant...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="typeFilter">
                            <option value="">Tous les types</option>
                            <option value="Rapport_intermediaire">Rapport intermédiaire</option>
                            <option value="Rapport_final">Rapport final</option>
                            <option value="Presentation">Présentation</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statutFilter">
                            <option value="">Tous les statuts</option>
                            <option value="soumis">Soumis</option>
                            <option value="valide">Validé</option>
                            <option value="rejete">Rejeté</option>
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

                <!-- Tableau des rapports -->
                <div class="table-responsive">
                    <table class="table table-hover" id="rapportsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="font-size: 0.85rem; font-weight: 600;">Titre</th>
                                <th style="font-size: 0.85rem; font-weight: 600;">Étudiant</th>
                                <th style="font-size: 0.85rem; font-weight: 600;">Stage</th>
                                <th style="font-size: 0.85rem; font-weight: 600;">Type</th>
                                <th style="font-size: 0.85rem; font-weight: 600;">Date soumission</th>
                                <th style="font-size: 0.85rem; font-weight: 600;">Statut</th>
                                <th style="font-size: 0.85rem; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rapports as $rapport)
                                <tr>
                                    <td>
                                        <div class="fw-bold" style="font-size: 0.85rem;">{{ $rapport->titre }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($rapport->commentaires ?? '', 50) }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 12px;">
                                                {{ substr($rapport->etudiant->name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="font-size: 0.85rem;">{{ $rapport->etudiant->name ?? 'N/A' }}</div>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $rapport->etudiant->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($rapport->stage && $rapport->stage->candidature)
                                            <div class="fw-bold" style="font-size: 0.85rem;">{{ $rapport->stage->candidature->offre->titre ?? 'N/A' }}</div>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $rapport->stage->candidature->offre->entreprise->nom ?? 'Non spécifiée' }}</small>
                                        @else
                                            <div class="fw-bold" style="font-size: 0.85rem;">
                                                @if($rapport->type_rapport === 'memoire')
                                                    Mémoire
                                                @elseif($rapport->type_rapport === 'proposition_theme')
                                                    Proposition de thème
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Sans stage</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rapport->type_rapport === 'memoire')
                                            <span class="badge" style="background-color: #2d3748; color: #ffffff; font-size: 0.75rem; padding: 0.25rem 0.5rem;">Mémoire</span>
                                        @elseif($rapport->type_rapport === 'proposition_theme')
                                            <span class="badge" style="background-color: #2d3748; color: #ffffff; font-size: 0.75rem; padding: 0.25rem 0.5rem;">Proposition de thème</span>
                                        @else
                                            <span class="badge bg-info" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Rapport</span>
                                        @endif
                                    </td>
                                    <td style="font-size: 0.8rem;">{{ $rapport->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($rapport->statut === 'soumis')
                                            <span class="badge bg-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                                <i class="fas fa-clock me-1" style="font-size: 0.7rem;"></i>
                                                Soumis
                                            </span>
                                        @elseif($rapport->statut === 'valide')
                                            <span class="badge bg-success" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                                <i class="fas fa-check me-1" style="font-size: 0.7rem;"></i>
                                                Validé
                                            </span>
                                        @elseif($rapport->statut === 'rejete')
                                            <span class="badge bg-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                                <i class="fas fa-times me-1" style="font-size: 0.7rem;"></i>
                                                Rejeté
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('rapports.show', $rapport) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($rapport->fichier_path)
                                                <a href="{{ route('rapports.download', $rapport) }}" 
                                                   class="btn btn-sm btn-outline-success" 
                                                   title="Télécharger">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->isEtudiant() && $rapport->statut === 'rejete')
                                                <a href="{{ route('rapports.edit', $rapport) }}" 
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
                                        <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun rapport trouvé</p>
                                        @if(auth()->user()->isEtudiant())
                                            <a href="{{ route('rapports.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>
                                                Créer le premier rapport
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($rapports->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $rapports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}

#rapportsTable td {
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
}

#rapportsTable th {
    padding: 0.5rem 0.75rem;
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
    const table = document.getElementById('rapportsTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue;
        
        const titre = cells[0].textContent.toLowerCase();
        const etudiant = cells[1].textContent.toLowerCase();
        const stage = cells[2].textContent.toLowerCase();
        const type = cells[3].textContent;
        const statut = cells[5].textContent;
        
        const matchesSearch = titre.includes(searchTerm) || etudiant.includes(searchTerm) || stage.includes(searchTerm);
        const matchesType = !typeFilter || type.includes(typeFilter);
        const matchesStatut = !statutFilter || 
            (statutFilter === 'soumis' && statut.includes('Soumis')) ||
            (statutFilter === 'valide' && statut.includes('Validé')) ||
            (statutFilter === 'rejete' && statut.includes('Rejeté'));
        
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
