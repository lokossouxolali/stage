@extends('layouts.app')

@section('title', 'Gestion des propositions de thème')
@section('page-title', 'Gestion des propositions de thème')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h6 class="mb-0 text-muted fw-normal">
            <i class="fas fa-folder-open me-2"></i>
            Toutes les propositions de thème
        </h6>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group" role="group">
            <a href="{{ route('propositions.index') }}" class="btn btn-sm {{ !request('filtre') ? 'active' : '' }}" style="background-color: #0b1f4d; border-color: #0b1f4d; color: #ffffff;">
                Toutes
            </a>
            <a href="{{ route('propositions.index', ['filtre' => 'en_attente']) }}" class="btn btn-sm {{ request('filtre') === 'en_attente' ? 'active' : '' }}" style="background-color: #0b1f4d; border-color: #0b1f4d; color: #ffffff;">
                En attente
            </a>
            <a href="{{ route('propositions.index', ['filtre' => 'valide']) }}" class="btn btn-sm {{ request('filtre') === 'valide' ? 'active' : '' }}" style="background-color: #0b1f4d; border-color: #0b1f4d; color: #ffffff;">
                Validées
            </a>
            <a href="{{ route('propositions.index', ['filtre' => 'refuse']) }}" class="btn btn-sm {{ request('filtre') === 'refuse' ? 'active' : '' }}" style="background-color: #0b1f4d; border-color: #0b1f4d; color: #ffffff;">
                Refusées
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-3">
        @if($propositions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #0b1f4d;">Étudiant</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #0b1f4d;">Titre</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #0b1f4d;">Directeur</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #0b1f4d;">Destinataires</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #0b1f4d;">Statut</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #0b1f4d;">Date</th>
                            <th class="border-0 py-2 text-center" style="font-size: 0.65rem; font-weight: 600; color: #0b1f4d;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($propositions as $proposition)
                            <tr class="border-bottom">
                                <td class="py-2">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold" style="font-size: 0.75rem;">{{ $proposition->etudiant->name }}</div>
                                            <small class="text-muted">{{ $proposition->etudiant->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <div class="fw-bold" style="font-size: 0.75rem;">{{ Str::limit($proposition->titre, 30) }}</div>
                                    <small class="text-muted">{{ Str::limit($proposition->description, 40) }}</small>
                                </td>
                                <td class="py-2">
                                    {{ $proposition->directeurMemoire ? $proposition->directeurMemoire->name : 'Non assigné' }}
                                </td>
                                <td class="py-2">
                                    @if($proposition->envoye_au_directeur && $proposition->envoye_a_l_admin)
                                        <small class="badge bg-info">Directeur + Responsable pedagogique</small>
                                    @elseif($proposition->envoye_au_directeur)
                                        <small class="badge bg-primary">Directeur</small>
                                    @elseif($proposition->envoye_a_l_admin)
                                        <small class="badge bg-secondary">Responsable pedagogique</small>
                                    @else
                                        <small class="badge bg-light text-dark">Aucun</small>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($proposition->statut === 'en_attente')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($proposition->statut === 'valide')
                                        <span class="badge bg-success">Validée</span>
                                    @else
                                        <span class="badge bg-danger">Refusée</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    <small>{{ $proposition->date_soumission->format('d/m/Y') }}</small>
                                </td>
                                <td class="py-2 text-center">
                                    <a href="{{ route('propositions.show', $proposition) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $propositions->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucune proposition trouvée</h5>
                <p class="text-muted">Il n'y a pas de propositions de thème correspondant aux critères sélectionnés.</p>
            </div>
        @endif
    </div>
</div>
@endsection
