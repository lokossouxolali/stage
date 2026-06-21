@php
    $user = auth()->user();
    $roleLabels = [
        'super_admin' => 'Super Administrateur',
        'responsable_pedagogique' => 'Responsable Pedagogique',
        'admin' => 'Responsable Pedagogique',
        'etudiant' => 'Etudiant',
        'entreprise' => 'Entreprise',
        'enseignant' => 'Enseignant',
        'responsable_stages' => 'Responsable stages',
    ];
@endphp

<aside class="sidebar">
    {{-- <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <span class="brand-icon"><i class="fas fa-layer-group"></i></span>
    </a> --}}

    <div class="sidebar-profile">
        @if($user->photo_path && $user->photo_url)
            <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="avatar avatar-lg" onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
            <span class="avatar avatar-lg avatar-fallback" style="display: none;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
        @else
            <span class="avatar avatar-lg avatar-fallback">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
        @endif
        <div class="profile-name">{{ $user->name }}</div>
        <div class="profile-role">{{ $roleLabels[$user->role] ?? ucfirst($user->role) }}</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Pilotage</div>
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="fas fa-tachometer-alt"></i><span>Tableau de bord</span>
        </a>

        @if($user->isAdmin())
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="fas fa-users"></i><span>Utilisateurs</span>
            </a>
            @if($user->isSuperAdmin())
                <a class="nav-link {{ request()->routeIs('users.*') && request('role') ? 'active' : '' }}" href="{{ route('users.index', ['role' => 'responsable_pedagogique']) }}">
                    <i class="fas fa-user-shield"></i><span>Gestion des roles</span>
                </a>
            @endif
            <a class="nav-link {{ request()->routeIs('registration-tokens.*') ? 'active' : '' }}" href="{{ route('registration-tokens.index') }}">
                <i class="fas fa-ticket-alt"></i><span>Tokens inscription</span>
            </a>
            <a class="nav-link {{ request()->routeIs('entreprises.*') ? 'active' : '' }}" href="{{ route('entreprises.index') }}">
                <i class="fas fa-building"></i><span>Entreprises</span>
            </a>
            <a class="nav-link {{ request()->routeIs('statistiques') ? 'active' : '' }}" href="{{ route('statistiques') }}">
                <i class="fas fa-chart-pie"></i><span>Statistiques</span>
            </a>
        @endif

        <div class="nav-section">Activite</div>
        @if($user->isEntreprise() || $user->isAdmin())
            <a class="nav-link {{ request()->routeIs('offres.*') ? 'active' : '' }}" href="{{ route('offres.index') }}">
                <i class="fas fa-briefcase"></i><span>Offres de stage</span>
            </a>
        @endif

        @if($user->isEntreprise())
            <a class="nav-link {{ request()->routeIs('offres.mes') ? 'active' : '' }}" href="{{ route('offres.mes') }}">
                <i class="fas fa-list"></i><span>Mes offres</span>
            </a>
            <a class="nav-link {{ request()->routeIs('candidatures.recues') ? 'active' : '' }}" href="{{ route('candidatures.recues') }}">
                <i class="fas fa-inbox"></i><span>Candidatures recues</span>
            </a>
        @endif

        @if($user->isEtudiant())
            <a class="nav-link {{ request()->routeIs('candidatures.mes') ? 'active' : '' }}" href="{{ route('candidatures.mes') }}">
                <i class="fas fa-file-alt"></i><span>Mes candidatures</span>
            </a>
        @endif

        @if($user->isEtudiant())
            <a class="nav-link {{ request()->routeIs('offres.disponibles') ? 'active' : '' }}" href="{{ route('offres.disponibles') }}">
                <i class="fas fa-search"></i><span>Offres disponibles</span>
            </a>
            <a class="nav-link {{ request()->routeIs('users.choisir-directeur-memoire') ? 'active' : '' }}" href="{{ route('users.choisir-directeur-memoire') }}">
                <i class="fas fa-user-tie"></i><span>Directeur memoire</span>
            </a>
        @endif

        @if($user->isEnseignant())
            <a class="nav-link {{ request()->routeIs('demandes-encadrement.*') ? 'active' : '' }}" href="{{ route('demandes-encadrement.index') }}">
                <i class="fas fa-user-graduate"></i><span>Demandes encadrement</span>
            </a>
        @endif

        @if($user->isEtudiant() || $user->isEnseignant() || $user->isAdmin())
            <div class="nav-section">Propositions</div>
            @if($user->isEtudiant())
                <a class="nav-link {{ request()->routeIs('propositions.create') ? 'active' : '' }}" href="{{ route('propositions.create') }}">
                    <i class="fas fa-plus-circle"></i><span>Nouvelle proposition</span>
                </a>
                <a class="nav-link {{ request()->routeIs('propositions.mes') ? 'active' : '' }}" href="{{ route('propositions.mes') }}">
                    <i class="fas fa-list-check"></i><span>Mes propositions</span>
                </a>
            @endif
            @if($user->isEnseignant())
                <a class="nav-link {{ request()->routeIs('propositions.encadrees') ? 'active' : '' }}" href="{{ route('propositions.encadrees') }}">
                    <i class="fas fa-inbox"></i><span>Propositions recues</span>
                </a>
            @endif
            @if($user->isAdmin())
                <a class="nav-link {{ request()->routeIs('propositions.index') ? 'active' : '' }}" href="{{ route('propositions.index') }}">
                    <i class="fas fa-folder-open"></i><span>Toutes les propositions</span>
                </a>
            @endif
        @endif
    </nav>
</aside>
