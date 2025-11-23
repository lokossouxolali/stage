@extends('layouts.app')

@section('title', 'Candidatures reçues')
@section('page-title', 'Candidatures reçues')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-inbox me-2"></i>
                    Candidatures reçues
                </h5>
                <a href="{{ route('offres.mes') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Mes offres
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filtres -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par nom étudiant, offre...">
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
                        <select class="form-select" id="offreFilter">
                            <option value="">Toutes les offres</option>
                            @foreach($candidatures->pluck('offre')->unique('id') as $offre)
                                <option value="{{ $offre->id }}">{{ $offre->titre }}</option>
                            @endforeach
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
                                <th>Candidat</th>
                                <th>Offre</th>
                                <th>Niveau</th>
                                <th>Date candidature</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidatures as $candidature)
                                <tr data-offre-id="{{ $candidature->offre->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ strtoupper(substr($candidature->etudiant->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $candidature->etudiant->name }}</div>
                                                <small class="text-muted">{{ $candidature->etudiant->email }}</small>
                                                @if($candidature->etudiant->niveau_etude)
                                                    <div class="mt-1">
                                                        <span class="badge bg-secondary">{{ $candidature->etudiant->niveau_etude }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $candidature->offre->titre }}</div>
                                        <small class="text-muted">{{ Str::limit($candidature->offre->description, 50) }}</small>
                                    </td>
                                    <td>
                                        @if($candidature->etudiant->niveau_etude)
                                            <span class="badge bg-info">{{ $candidature->etudiant->niveau_etude }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $candidature->date_candidature->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $candidature->date_candidature->format('H:i') }}</small>
                                    </td>
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
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($candidature->statut === 'en_attente')
                                                <form method="POST" 
                                                      action="{{ route('candidatures.accepter', $candidature) }}" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir accepter cette candidature ?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success" 
                                                            title="Accepter">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" 
                                                      action="{{ route('candidatures.refuser', $candidature) }}" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cette candidature ?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Refuser">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-3">Aucune candidature reçue pour le moment</p>
                                        <a href="{{ route('offres.mes') }}" class="btn btn-primary">
                                            <i class="fas fa-briefcase me-2"></i>
                                            Voir mes offres
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
document.getElementById('offreFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statutFilter = document.getElementById('statutFilter').value;
    const offreFilter = document.getElementById('offreFilter').value;
    const table = document.getElementById('candidaturesTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue;
        
        const candidat = cells[0].textContent.toLowerCase();
        const offre = cells[1].textContent.toLowerCase();
        const statut = cells[4].textContent;
        const offreId = row.dataset.offreId || '';
        
        const matchesSearch = candidat.includes(searchTerm) || offre.includes(searchTerm);
        const matchesStatut = !statutFilter || 
            (statutFilter === 'en_attente' && statut.includes('En attente')) ||
            (statutFilter === 'acceptee' && statut.includes('Acceptée')) ||
            (statutFilter === 'refusee' && statut.includes('Refusée'));
        const matchesOffre = !offreFilter || offreId === offreFilter;
        
        if (matchesSearch && matchesStatut && matchesOffre) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statutFilter').value = '';
    document.getElementById('offreFilter').value = '';
    filterTable();
}
</script>
@endpush
@endsection

