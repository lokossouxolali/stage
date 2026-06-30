@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('content')
@php
    $roleBadges = [
        'super_admin' => ['label' => 'Super Administrateur', 'class' => 'badge-soft-danger'],
        'responsable_pedagogique' => ['label' => 'Responsable Pedagogique', 'class' => 'badge-soft-warning'],
        'admin' => ['label' => 'Responsable Pedagogique', 'class' => 'badge-soft-warning'],
        'etudiant' => ['label' => 'Etudiant', 'class' => 'badge-soft-primary'],
        'entreprise' => ['label' => 'Entreprise', 'class' => 'badge-soft-info'],
        'enseignant' => ['label' => 'Enseignant', 'class' => 'badge-soft-secondary'],
        'responsable_stages' => ['label' => 'Responsable', 'class' => 'badge-soft-secondary'],
    ];
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card metric-card"><div class="card-body"><div class="metric-label">Total</div><div class="metric-value mt-2">{{ $summary['total'] }}</div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card metric-card"><div class="card-body"><div class="metric-label">Actifs</div><div class="metric-value mt-2">{{ $summary['active'] }}</div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card metric-card"><div class="card-body"><div class="metric-label">En attente</div><div class="metric-value mt-2">{{ $summary['pending'] }}</div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card metric-card"><div class="card-body"><div class="metric-label">Resp. pedagogiques</div><div class="metric-value mt-2">{{ $summary['admins'] }}</div></div></div>
    </div>
</div>

@if(auth()->user()->isSuperAdmin())
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-shield me-2 text-primary"></i>Gestion des roles
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="metric-label">Super Administrateurs</div>
                    <div class="metric-value mt-2">{{ $summary['super_admins'] }}</div>
                </div>
                <div class="col-md-4">
                    <div class="metric-label">Responsables pedagogiques</div>
                    <div class="metric-value mt-2">{{ $summary['admins'] }}</div>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-md-end">
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus me-2"></i>Creer un compte
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('users.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-4">
                <label for="search" class="form-label">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="search" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Nom, email ou telephone">
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="">Tous</option>
                    @foreach($roleLabels as $value => $label)
                        <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <label for="statut_inscription" class="form-label">Inscription</label>
                <select class="form-select" id="statut_inscription" name="statut_inscription">
                    <option value="">Tous</option>
                    <option value="valide" @selected(request('statut_inscription') === 'valide')>Validee</option>
                    <option value="en_attente" @selected(request('statut_inscription') === 'en_attente')>En attente</option>
                    <option value="refuse" @selected(request('statut_inscription') === 'refuse')>Refusee</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <label for="activity" class="form-label">Statut</label>
                <select class="form-select" id="activity" name="activity">
                    <option value="">Tous</option>
                    <option value="active" @selected(request('activity') === 'active')>Actif</option>
                    <option value="inactive" @selected(request('activity') === 'inactive')>Inactif</option>
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-filter me-2"></i>Filtrer</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary" title="Reinitialiser"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <span><i class="fas fa-users me-2 text-primary"></i>Annuaire des utilisateurs</span>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="fas fa-download me-2"></i>Exporter
            </button>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-2"></i>Nouvel utilisateur
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Inscription</th>
                    <th>Statut</th>
                    <th>Creation</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $role = $roleBadges[$user->role] ?? ['label' => $user->roleLabel(), 'class' => 'badge-soft-secondary'];
                        $registrationClass = match($user->statut_inscription ?? 'valide') {
                            'en_attente' => 'badge-soft-warning',
                            'refuse' => 'badge-soft-danger',
                            default => 'badge-soft-success',
                        };
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($user->photo_path && $user->photo_url)
                                    <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="avatar avatar-sm" onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                                    <span class="avatar avatar-sm avatar-fallback" style="display: none;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                @else
                                    <span class="avatar avatar-sm avatar-fallback">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <div class="text-muted small">#{{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $user->email }}</div>
                            <div class="text-muted small">{{ $user->telephone ?: 'Telephone non renseigne' }}</div>
                        </td>
                        <td>
                            @php
                                $words = explode(' ', $role['label']);
                                $roleDisplay = count($words) > 1
                                    ? collect($words)->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('.')
                                    : $role['label'];
                            @endphp
                            <span class="badge-soft {{ $role['class'] }}" title="{{ $role['label'] }}">{{ $roleDisplay }}</span>
                        </td>
                        <td><span class="badge-soft {{ $registrationClass }}">{{ str_replace('_', ' ', ucfirst($user->statut_inscription ?? 'valide')) }}</span></td>
                        <td>
                            @if($user->est_actif)
                                <span class="badge-soft badge-soft-success">Actif</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">Inactif</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('users.show', $user) }}" class="action-button" title="Voir"><i class="fas fa-eye"></i></a>
                                @if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin())
                                    <a href="{{ route('users.edit', $user) }}" class="action-button" title="Modifier"><i class="fas fa-pen"></i></a>
                                @endif
                                @if(($user->statut_inscription ?? 'valide') === 'en_attente')
                                    <form method="POST" action="{{ route('users.valider-inscription', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-button" title="Valider"><i class="fas fa-check"></i></button>
                                    </form>
                                    <form method="POST" action="{{ route('users.refuser-inscription', $user) }}" data-confirm="Refuser cette inscription ?">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-button" title="Refuser"><i class="fas fa-times"></i></button>
                                    </form>
                                @endif
                                @if(auth()->id() !== $user->id && (!$user->isSuperAdmin() || auth()->user()->isSuperAdmin()))
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" data-confirm="Supprimer definitivement cet utilisateur ?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-button" title="Supprimer"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-users fa-2x text-muted mb-3"></i>
                            <div class="fw-semibold">Aucun utilisateur trouve</div>
                            <div class="text-muted">Modifiez vos filtres ou creez un nouvel utilisateur.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="card-footer d-flex justify-content-end">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('users.export') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel"><i class="fas fa-download me-2 text-primary"></i>Exporter les utilisateurs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="export_format" class="form-label">Format</label>
                        <select class="form-select" id="export_format" name="format" required>
                            <option value="">Selectionner</option>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export_role" class="form-label">Role</label>
                        <select class="form-select" id="export_role" name="role">
                            <option value="">Tous</option>
                            @foreach($roleLabels as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export_statut" class="form-label">Inscription</label>
                        <select class="form-select" id="export_statut" name="statut_inscription">
                            <option value="">Tous</option>
                            <option value="valide">Validee</option>
                            <option value="en_attente">En attente</option>
                            <option value="refuse">Refusee</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-loading-text="Export..."><i class="fas fa-download me-2"></i>Exporter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
