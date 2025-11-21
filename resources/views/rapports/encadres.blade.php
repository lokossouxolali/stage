@extends('layouts.app')

@section('title', 'Rapports Encadrés')
@section('page-title', 'Rapports Encadrés')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-pdf me-2"></i>
                    Rapports des Étudiants Encadrés
                </h5>
            </div>
            <div class="card-body">
                @if($rapports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Étudiant</th>
                                    <th>Stage</th>
                                    <th>Destinataire</th>
                                    <th>Date soumission</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rapports as $rapport)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $rapport->titre }}</div>
                                            <small class="text-muted">{{ Str::limit($rapport->commentaires ?? '', 50) }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($rapport->etudiant->name ?? '?', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $rapport->etudiant->name ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $rapport->etudiant->email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($rapport->stage && $rapport->stage->candidature)
                                                <div class="fw-bold">{{ $rapport->stage->candidature->offre->titre ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $rapport->stage->candidature->offre->entreprise->nom ?? 'Non spécifiée' }}</small>
                                            @else
                                                <div class="fw-bold">
                                                    @if($rapport->type_rapport === 'memoire')
                                                        Mémoire
                                                    @elseif($rapport->type_rapport === 'proposition_theme')
                                                        Proposition de thème
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                                <small class="text-muted">Sans stage</small>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($rapport->destinataire)
                                                @case('admin')
                                                    <span class="badge bg-danger">Administrateur</span>
                                                    @break
                                                @case('directeur_memoire')
                                                    <span class="badge bg-primary">Directeur de mémoire</span>
                                                    @break
                                                @case('les_deux')
                                                    <span class="badge bg-info">Admin + Directeur</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $rapport->date_soumission ? $rapport->date_soumission->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            @switch($rapport->statut)
                                                @case('soumis')
                                                    <span class="badge bg-warning">Soumis</span>
                                                    @break
                                                @case('valide')
                                                    <span class="badge bg-success">Validé</span>
                                                    @break
                                                @case('rejete')
                                                    <span class="badge bg-danger">Rejeté</span>
                                                    @break
                                                @case('en_revision')
                                                    <span class="badge bg-info">En révision</span>
                                                    @break
                                            @endswitch
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
                                                @if(auth()->user()->isEnseignant() && in_array($rapport->statut, ['soumis', 'en_revision']))
                                                    <form method="POST" action="{{ route('rapports.valider', $rapport) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Valider">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('rapports.rejeter', $rapport) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter ce rapport ?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Rejeter">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $rapports->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun rapport trouvé</h5>
                        <p class="text-muted">Aucun rapport n'a été soumis par vos étudiants encadrés pour le moment.</p>
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
@endsection



