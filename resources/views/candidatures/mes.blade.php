@extends('layouts.app')

@section('title', 'Mes Candidatures')
@section('page-title', 'Mes Candidatures')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Mes Candidatures
                </h5>
            </div>
            <div class="card-body">
                @if($candidatures->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Offre</th>
                                    <th>Entreprise</th>
                                    <th>Date candidature</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($candidatures as $candidature)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $candidature->offre->titre }}</div>
                                            <small class="text-muted">{{ Str::limit($candidature->offre->description, 50) }}</small>
                                        </td>
                                        <td>
                                            {{ $candidature->offre->entreprise->nom ?? 'Non spécifiée' }}
                                        </td>
                                        <td>{{ $candidature->date_candidature ? $candidature->date_candidature->format('d/m/Y') : $candidature->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @switch($candidature->statut)
                                                @case('en_attente')
                                                    <span class="badge bg-warning">En attente</span>
                                                    @break
                                                @case('acceptee')
                                                    <span class="badge bg-success">Acceptée</span>
                                                    @break
                                                @case('refusee')
                                                    <span class="badge bg-danger">Refusée</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('candidatures.show', $candidature) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($candidature->statut === 'en_attente')
                                                    <form method="POST" action="{{ route('candidatures.destroy', $candidature) }}" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette candidature ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
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
                        {{ $candidatures->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune candidature trouvée</h5>
                        <p class="text-muted">Vous n'avez pas encore postulé à une offre de stage.</p>
                        <a href="{{ route('offres.disponibles') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>
                            Voir les offres disponibles
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection




