@extends('layouts.app')

@section('title', 'Mes Rapports')
@section('page-title', 'Mes Rapports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-pdf me-2"></i>
                    Mes Rapports de Stage
                </h5>
                <a href="{{ route('rapports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Nouveau rapport
                </a>
            </div>
            <div class="card-body">
                @if($rapports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Titre</th>
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
                                                @case('brouillon')
                                                    <span class="badge bg-secondary">Brouillon</span>
                                                    @break
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
                        <p class="text-muted">Commencez par créer votre premier rapport de stage.</p>
                        <a href="{{ route('rapports.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Créer un rapport
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection



