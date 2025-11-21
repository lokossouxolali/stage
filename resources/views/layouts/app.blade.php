<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion de Stages')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Réduction globale des tailles */
        .h1, h1 { font-size: 1.5rem !important; }
        .h2, h2 { font-size: 1.3rem !important; }
        .h3, h3 { font-size: 1.15rem !important; }
        .h4, h4 { font-size: 1rem !important; }
        .h5, h5 { font-size: 0.9rem !important; }
        .h6, h6 { font-size: 0.85rem !important; }
        
        .card-title { font-size: 0.85rem !important; }
        .table { font-size: 0.8rem !important; }
        .dropdown-item { font-size: 0.8rem !important; }
        .nav-link { font-size: 0.8rem !important; }
        .sidebar h4 { font-size: 0.9rem !important; }
        
        /* Style Lucid - Border-radius modérés */
        .btn { 
            border-radius: 0.375rem !important;
            padding: 0.3rem 0.6rem !important;
            font-size: 0.7rem !important;
            line-height: 1.2 !important;
        }
        .btn-sm { 
            border-radius: 0.3rem !important;
            padding: 0.25rem 0.5rem !important;
            font-size: 0.65rem !important;
            line-height: 1.2 !important;
        }
        .btn-lg { 
            border-radius: 0.4rem !important;
            padding: 0.4rem 0.8rem !important;
            font-size: 0.75rem !important;
            line-height: 1.2 !important;
        }
        
        /* Réduction des icônes dans les boutons */
        .btn i {
            font-size: 0.7rem !important;
        }
        
        .btn-sm i {
            font-size: 0.65rem !important;
        }
        
        .btn-lg i {
            font-size: 0.75rem !important;
        }
        
        /* Couleur des icônes dans les boutons outline */
        .btn-outline-primary i,
        .btn-outline-success i,
        .btn-outline-info i,
        .btn-outline-warning i,
        .btn-outline-danger i {
            color: #2d3748 !important;
        }
        
        .btn-outline-primary:hover i,
        .btn-outline-primary:focus i,
        .btn-outline-success:hover i,
        .btn-outline-success:focus i,
        .btn-outline-info:hover i,
        .btn-outline-info:focus i,
        .btn-outline-warning:hover i,
        .btn-outline-warning:focus i,
        .btn-outline-danger:hover i,
        .btn-outline-danger:focus i {
            color: #ffffff !important;
        }
        
        /* Couleur des boutons outline */
        .btn-outline-success,
        .btn-outline-info,
        .btn-outline-warning,
        .btn-outline-danger {
            color: #2d3748 !important;
            border-color: #2d3748 !important;
        }
        
        .btn-outline-success:hover,
        .btn-outline-success:focus,
        .btn-outline-success:active,
        .btn-outline-success.active,
        .btn-outline-info:hover,
        .btn-outline-info:focus,
        .btn-outline-info:active,
        .btn-outline-info.active,
        .btn-outline-warning:hover,
        .btn-outline-warning:focus,
        .btn-outline-warning:active,
        .btn-outline-warning.active,
        .btn-outline-danger:hover,
        .btn-outline-danger:focus,
        .btn-outline-danger:active,
        .btn-outline-danger.active {
            background-color: #2d3748 !important;
            border-color: #2d3748 !important;
            color: #ffffff !important;
        }
        
        /* Cards style Lucid */
        .card { border-radius: 0.5rem !important; }
        
        /* Cartes de statistiques - couleur du sidebar */
        .stats-card {
            background: #2d3748 !important;
            color: #ffffff !important;
            border-color: #2d3748 !important;
        }
        
        .stats-card .text-xs,
        .stats-card .stats-number,
        .stats-card i {
            color: #ffffff !important;
        }
        
        /* Formulaires style Lucid */
        .form-control { border-radius: 0.375rem !important; }
        
        /* Style personnalisé pour les selects - style moderne et compact */
        .form-select,
        select.form-select {
            border: 1.5px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            padding: 0.35rem 2rem 0.35rem 0.6rem !important;
            background-color: #ffffff !important;
            color: var(--text-primary) !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            appearance: none !important;
            font-size: 0.7rem !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 12 12'%3E%3Cpath fill='%232d3748' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.6rem center !important;
            background-size: 10px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            height: auto !important;
            line-height: 1.4 !important;
        }
        
        .form-select:hover,
        select.form-select:hover {
            border-color: #2d3748 !important;
            box-shadow: 0 2px 4px rgba(45, 55, 72, 0.1) !important;
        }
        
        .form-select:focus,
        select.form-select:focus {
            border-color: #2d3748 !important;
            box-shadow: 0 0 0 3px rgba(45, 55, 72, 0.1), 0 2px 8px rgba(45, 55, 72, 0.15) !important;
            outline: none !important;
            background-color: #ffffff !important;
        }
        
        .form-select-sm,
        select.form-select-sm {
            padding: 0.3rem 1.8rem 0.3rem 0.5rem !important;
            font-size: 0.65rem !important;
            background-position: right 0.5rem center !important;
            background-size: 9px !important;
            line-height: 1.4 !important;
        }
        
        .form-select option {
            padding: 0.5rem !important;
            background-color: #ffffff !important;
        }
        
        .form-select option:checked {
            background-color: #2d3748 !important;
            color: #ffffff !important;
        }
        
        /* Selects dans les input-group - même style compact */
        .input-group .form-select,
        .input-group select.form-select {
            padding: 0.35rem 2rem 0.35rem 0.6rem !important;
            font-size: 0.7rem !important;
        }
        
        .input-group-sm .form-select,
        .input-group-sm select.form-select {
            padding: 0.3rem 1.8rem 0.3rem 0.5rem !important;
            font-size: 0.65rem !important;
            background-size: 9px !important;
            background-position: right 0.5rem center !important;
        }
        
        /* Badges style Lucid */
        .badge { border-radius: 0.25rem !important; }
        
        /* Sidebar style Lucid */
        .sidebar { background: #2d3748 !important; }
        
        /* Main content background Lucid */
        .main-content { background: #f7fafc !important; }
        
        /* Sidebar avatar */
        .sidebar-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        /* Couleur des icônes - utiliser la couleur du sidebar */
        i.fas.text-primary,
        i.fas.text-danger,
        i.fas.text-success,
        i.fas.text-warning,
        i.fas.text-info,
        i.far.text-primary,
        i.far.text-danger,
        i.far.text-success,
        i.far.text-warning,
        i.far.text-info {
            color: #2d3748 !important;
        }
        
        /* Garder les icônes blanches blanches */
        i.fas.text-white,
        i.far.text-white {
            color: #ffffff !important;
        }
        
        /* Couleur de fond des boutons - utiliser la couleur du sidebar */
        .btn-primary,
        .btn-primary:not(:disabled):not(.disabled):active,
        .btn-primary:not(:disabled):not(.disabled).active,
        button.btn-primary,
        a.btn-primary {
            background-color: #2d3748 !important;
            border-color: #2d3748 !important;
            color: #ffffff !important;
        }
        
        .btn-primary:hover,
        .btn-primary:focus,
        button.btn-primary:hover,
        a.btn-primary:hover {
            background-color: #374151 !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        
        /* Bouton outline primary */
        .btn-outline-primary,
        a.btn-outline-primary {
            color: #2d3748 !important;
            border-color: #2d3748 !important;
            background-color: transparent !important;
        }
        
        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active,
        .btn-outline-primary.active,
        a.btn-outline-primary:hover,
        a.btn-outline-primary:focus,
        a.btn-outline-primary:active {
            background-color: #2d3748 !important;
            border-color: #2d3748 !important;
            color: #ffffff !important;
        }
        
        /* Autres boutons aussi */
        .btn-success,
        .btn-warning,
        .btn-danger,
        .btn-info {
            background-color: #2d3748 !important;
            border-color: #2d3748 !important;
            color: #ffffff !important;
        }
        
        .btn-success:hover,
        .btn-success:focus,
        .btn-warning:hover,
        .btn-warning:focus,
        .btn-danger:hover,
        .btn-danger:focus,
        .btn-info:hover,
        .btn-info:focus {
            background-color: #374151 !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        
        /* Icônes dans les input-group */
        .input-group-text i {
            color: #2d3748 !important;
        }
        
        /* Badge de notification sur l'icône */
        .btn[title="Notifications"] .badge {
            z-index: 10;
            font-weight: 700 !important;
            top: -5px !important;
            right: -5px !important;
        }
        
        /* Liens de navigation */
        a.text-decoration-none:not(.text-white):not(.text-muted) {
            color: #2d3748 !important;
        }
        
        a.text-decoration-none:hover:not(.text-white):not(.text-muted) {
            color: #374151 !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    @auth
                    <div class="text-center mb-4 px-2">
                        @if(auth()->user()->photo_path && auth()->user()->photo_url)
                            <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name }}" 
                                 class="rounded-circle mx-auto mb-2 d-block" 
                                 style="width: 60px; height: 60px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3); box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="sidebar-avatar mx-auto mb-2" style="display: none;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @else
                            <div class="sidebar-avatar mx-auto mb-2">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <h6 class="text-white mb-0 fw-semibold" style="font-size: 0.9rem; word-break: break-word;">
                            {{ auth()->user()->name }}
                        </h6>
                        <small class="text-white-50" style="font-size: 0.75rem; opacity: 0.8;">
                            @switch(auth()->user()->role)
                                @case('admin')
                                    Administrateur
                                    @break
                                @case('etudiant')
                                    Étudiant
                                    @break
                                @case('entreprise')
                                    Entreprise
                                    @break
                                @case('enseignant')
                                    Enseignant
                                    @break
                                @case('responsable_stages')
                                    Responsable Stages
                                    @break
                                @default
                                    {{ ucfirst(auth()->user()->role) }}
                            @endswitch
                        </small>
                    </div>
                    @endauth
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Tableau de bord
                            </a>
                        </li>
                        
                        @auth
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="fas fa-users me-2"></i>
                                        Utilisateurs
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('entreprises.*') ? 'active' : '' }}" href="{{ route('entreprises.index') }}">
                                        <i class="fas fa-building me-2"></i>
                                        Entreprises
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('rapports.*') ? 'active' : '' }}" href="{{ route('rapports.index') }}">
                                        <i class="fas fa-file-pdf me-2"></i>
                                        Rapports
                                    </a>
                                </li>
                            @endif
                            
                            @if(auth()->user()->isEntreprise() || auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('offres.*') ? 'active' : '' }}" href="{{ route('offres.index') }}">
                                        <i class="fas fa-briefcase me-2"></i>
                                        Offres de stage
                                    </a>
                                </li>
                                @if(auth()->user()->isEntreprise())
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('offres.mes') ? 'active' : '' }}" href="{{ route('offres.mes') }}">
                                            <i class="fas fa-list me-2"></i>
                                            Mes offres
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('candidatures.recues') ? 'active' : '' }}" href="{{ route('candidatures.recues') }}">
                                            <i class="fas fa-inbox me-2"></i>
                                            Candidatures reçues
                                        </a>
                                    </li>
                                @endif
                            @endif
                            
                            @if(auth()->user()->isEtudiant() || auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('candidatures.mes') ? 'active' : '' }}" href="{{ route('candidatures.mes') }}">
                                        <i class="fas fa-file-alt me-2"></i>
                                        Mes candidatures
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('rapports.mes') ? 'active' : '' }}" href="{{ route('rapports.mes') }}">
                                        <i class="fas fa-file-pdf me-2"></i>
                                        Mes rapports
                                    </a>
                                </li>
                                @if(auth()->user()->isEtudiant())
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('users.choisir-directeur-memoire') ? 'active' : '' }}" href="{{ route('users.choisir-directeur-memoire') }}">
                                            <i class="fas fa-user-tie me-2"></i>
                                            Directeur de mémoire
                                        </a>
                                    </li>
                                @endif
                            @endif
                            
                            @if(auth()->user()->isEnseignant() || auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('demandes-encadrement.*') ? 'active' : '' }}" href="{{ route('demandes-encadrement.index') }}">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        Demandes d'encadrement
                                        @php
                                            $demandesEnAttente = \App\Models\User::where('directeur_memoire_id', auth()->id())
                                                ->where('statut_demande_dm', 'en_attente')
                                                ->count();
                                        @endphp
                                        @if($demandesEnAttente > 0)
                                            <span class="badge bg-warning ms-2" style="font-size: 0.65rem;">{{ $demandesEnAttente }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('rapports.encadres') ? 'active' : '' }}" href="{{ route('rapports.encadres') }}">
                                        <i class="fas fa-file-pdf me-2"></i>
                                        Rapports encadrés
                                    </a>
                                </li>
                            @endif
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-2"></i>
                                    Mon profil
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top navbar -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Tableau de bord')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @auth
                            @php
                                $notificationsNonLues = \App\Models\Notification::where('user_id', auth()->id())
                                    ->where('lu', false)
                                    ->count();
                            @endphp
                            
                            <!-- Bouton Notifications -->
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary position-relative me-2" title="Notifications" style="padding: 0.3rem 0.6rem;">
                                <i class="fas fa-bell"></i>
                                @if($notificationsNonLues > 0)
                                    <span class="position-absolute badge rounded-pill" style="background-color: #dc3545; color: #ffffff; font-size: 0.6rem; font-weight: 700; padding: 0.2rem 0.45rem; min-width: 18px; height: 18px; line-height: 1; border: 2px solid #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.2); top: -8px; right: -8px; z-index: 10;">
                                        {{ $notificationsNonLues > 99 ? '99+' : $notificationsNonLues }}
                                    </span>
                                @endif
                            </a>
                            
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-2"></i>
                                    {{ auth()->user()->name }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit me-2"></i>Profil
                                    </a></li>
                                    <li><a class="dropdown-item position-relative" href="{{ route('notifications.index') }}">
                                        <i class="fas fa-bell me-2"></i>Notifications
                                        @if($notificationsNonLues > 0)
                                            <span class="position-absolute top-50 end-0 translate-middle-y badge rounded-pill me-2" style="background-color: #dc3545; color: #ffffff; font-size: 0.6rem; padding: 0.15rem 0.4rem; min-width: 18px;">
                                                {{ $notificationsNonLues > 99 ? '99+' : $notificationsNonLues }}
                                            </span>
                                        @endif
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Connexion</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Inscription</a>
                        @endauth
                    </div>
                </div>

                <!-- Flash messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page content -->
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
