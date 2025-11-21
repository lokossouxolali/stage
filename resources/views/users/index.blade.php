@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h6 class="mb-0 text-muted fw-normal">
            <i class="fas fa-users me-2"></i>
            Liste des Utilisateurs
        </h6>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('users.create') }}" class="btn btn-sm" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;">
            <i class="fas fa-plus me-1"></i>
            Nouvel Utilisateur
        </a>
        <button type="button" class="btn btn-sm" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="fas fa-download me-1"></i>
            Exporter
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-3">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #2d3748;">Nom</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #2d3748;">Email</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #2d3748;">Rôle</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #2d3748;">Inscription</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #2d3748;">Statut</th>
                            <th class="border-0 py-2" style="font-size: 0.65rem; font-weight: 600; color: #2d3748;">Créé le</th>
                            <th class="border-0 py-2 text-center" style="font-size: 0.65rem; font-weight: 600; color: #2d3748;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-bottom">
                                <td class="py-2">
                                    <div class="d-flex align-items-center">
                                        @if($user->photo_path && $user->photo_url)
                                            <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 32px; height: 32px; object-fit: cover;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="user-avatar me-2" style="display: none;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @else
                                            <div class="user-avatar me-2">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="fw-medium" style="font-size: 0.8rem;">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <span style="font-size: 0.75rem; color: #6c757d;">{{ $user->email }}</span>
                                </td>
                                <td class="py-2">
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="role-badge role-admin">ADMIN</span>
                                            @break
                                        @case('etudiant')
                                            <span class="role-badge role-etudiant">ÉTUDIANT</span>
                                            @break
                                        @case('entreprise')
                                            <span class="role-badge role-entreprise">ENTREPRISE</span>
                                            @break
                                        @case('enseignant')
                                            <span class="role-badge role-enseignant">ENSEIGNANT</span>
                                            @break
                                        @case('responsable_stages')
                                            <span class="role-badge role-responsable">RESPONSABLE</span>
                                            @break
                                        @default
                                            <span class="role-badge role-default">{{ strtoupper($user->role) }}</span>
                                    @endswitch
                                </td>
                                <td class="py-2">
                                    @switch($user->statut_inscription ?? 'valide')
                                        @case('en_attente')
                                            <span class="status-badge status-warning">EN ATTENTE</span>
                                            @break
                                        @case('valide')
                                            <span class="status-badge status-success">VALIDÉ</span>
                                            @break
                                        @case('refuse')
                                            <span class="status-badge status-danger">REFUSÉ</span>
                                            @break
                                        @default
                                            <span class="status-badge status-success">VALIDÉ</span>
                                    @endswitch
                                </td>
                                <td class="py-2">
                                    @if($user->est_actif)
                                        <span class="status-badge status-active">ACTIF</span>
                                    @else
                                        <span class="status-badge status-inactive">INACTIF</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    <span style="font-size: 0.75rem; color: #6c757d;">{{ $user->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="py-2">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('users.show', $user) }}" class="btn-action btn-view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(($user->statut_inscription ?? 'valide') === 'en_attente')
                                            <form method="POST" action="{{ route('users.valider-inscription', $user) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-action btn-success" title="Valider l'inscription">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('users.refuser-inscription', $user) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cette inscription ?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-action btn-danger" title="Refuser l'inscription">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                <div style="font-size: 0.75rem;">
                    {{ $users->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-2x text-muted mb-3" style="opacity: 0.5;"></i>
                <h6 class="text-muted mb-2" style="font-size: 0.85rem;">Aucun utilisateur trouvé</h6>
                <p class="text-muted mb-3" style="font-size: 0.75rem;">Commencez par créer votre premier utilisateur.</p>
                <a href="{{ route('users.create') }}" class="btn btn-sm" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;">
                    <i class="fas fa-plus me-1"></i>
                    Créer un utilisateur
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .role-badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .role-admin {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .role-etudiant {
        background-color: #1f2937;
        color: #ffffff;
    }

    .role-entreprise {
        background-color: #d1fae5;
        color: #059669;
    }

    .role-enseignant {
        background-color: #fed7aa;
        color: #ea580c;
    }

    .role-responsable {
        background-color: #e5e7eb;
        color: #374151;
    }

    .role-default {
        background-color: #f3f4f6;
        color: #6b7280;
    }

    .status-badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .status-success {
        background-color: #d1fae5;
        color: #059669;
    }

    .status-warning {
        background-color: #fef3c7;
        color: #d97706;
    }

    .status-danger {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .status-active {
        background-color: #d1fae5;
        color: #059669;
    }

    .status-inactive {
        background-color: #f3f4f6;
        color: #6b7280;
    }

    .btn-action {
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 0.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
    }

    .btn-view {
        background-color: transparent;
        color: #2d3748;
        border: 1px solid #2d3748;
    }

    .btn-view:hover {
        background-color: #2d3748;
        color: #ffffff;
    }


    .btn-success {
        background-color: transparent;
        color: #2d3748;
        border: 1px solid #2d3748;
    }

    .btn-success:hover {
        background-color: #2d3748;
        color: #ffffff;
    }

    .btn-danger, .btn-delete {
        background-color: transparent;
        color: #2d3748;
        border: 1px solid #2d3748;
    }

    .btn-danger:hover, .btn-delete:hover {
        background-color: #2d3748;
        color: #ffffff;
    }
    
    .btn-action i {
        color: #2d3748;
    }
    
    .btn-action:hover i {
        color: #ffffff;
    }

    .table-hover tbody tr:hover {
        background-color: #f9fafb;
    }

    .card {
        border: 1px solid #e5e7eb;
    }

    .table th {
        border-top: none;
        font-weight: 600;
    }

    .table td {
        vertical-align: middle;
    }

    /* Pagination personnalisée */
    .pagination {
        font-size: 0.75rem;
    }

    .pagination .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    /* Styles pour les boutons Nouvel Utilisateur et Exporter */
    .btn[style*="background-color: #2d3748"]:hover {
        background-color: #374151 !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
</style>

<!-- Modal d'export -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2d3748; color: #ffffff;">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="fas fa-download me-2"></i>Exporter les utilisateurs
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('users.export') }}" id="exportForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="export_format" class="form-label small fw-bold" style="color: #2d3748;">Format d'export *</label>
                        <select class="form-select" id="export_format" name="format" required>
                            <option value="">Sélectionnez un format</option>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export_role" class="form-label small fw-bold" style="color: #2d3748;">Type d'utilisateur</label>
                        <select class="form-select" id="export_role" name="role">
                            <option value="">Tous les utilisateurs</option>
                            <option value="admin">Administrateur</option>
                            <option value="etudiant">Étudiant</option>
                            <option value="enseignant">Enseignant</option>
                            <option value="entreprise">Entreprise</option>
                            <option value="responsable_stages">Responsable Stages</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export_statut" class="form-label small fw-bold" style="color: #2d3748;">Statut d'inscription</label>
                        <select class="form-select" id="export_statut" name="statut_inscription">
                            <option value="">Tous les statuts</option>
                            <option value="valide">Validé</option>
                            <option value="en_attente">En attente</option>
                            <option value="refuse">Refusé</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" style="background-color: #6c757d; border-color: #6c757d; color: #ffffff;" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm" style="background-color: #2d3748; border-color: #2d3748; color: #ffffff;">
                        <i class="fas fa-download me-1"></i>Exporter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
