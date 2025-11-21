@extends('layouts.app')

@section('title', 'Démonstration - Interface Ultra-Moderne')
@section('page-title', 'Démonstration des Effets Visuels')

@section('content')
<div class="row">
    <!-- Cartes de démonstration -->
    <div class="col-lg-4 mb-4">
        <div class="card glow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-magic me-2"></i>
                    Effets de Hover
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Survolez cette carte pour voir l'effet de levée et l'ombre dynamique.</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Bouton Primaire</button>
                    <button class="btn btn-outline-primary">Bouton Outline</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Statistiques Animées
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stats-number">1,234</div>
                        <small class="text-muted">Utilisateurs</small>
                    </div>
                    <div class="col-6">
                        <div class="stats-number">567</div>
                        <small class="text-muted">Projets</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-palette me-2"></i>
                    Couleurs & Badges
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-primary">Primaire</span>
                    <span class="badge bg-success">Succès</span>
                    <span class="badge bg-warning">Attention</span>
                    <span class="badge bg-danger">Danger</span>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 75%">75%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cartes de statistiques avec effets -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Utilisateurs</div>
                        <div class="stats-number">2,847</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Revenus</div>
                        <div class="stats-number">€45,678</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Conversion</div>
                        <div class="stats-number">12.5%</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Satisfaction</div>
                        <div class="stats-number">98%</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-heart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulaires avec animations -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Formulaire Animé
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="nom" placeholder="Votre nom">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="votre@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" rows="3" placeholder="Votre message..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>
                        Envoyer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-table me-2"></i>
                    Tableau Animé
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jean Dupont</td>
                                <td>Admin</td>
                                <td><span class="badge bg-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Marie Martin</td>
                                <td>Utilisateur</td>
                                <td><span class="badge bg-warning">En attente</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Pierre Durand</td>
                                <td>Modérateur</td>
                                <td><span class="badge bg-danger">Inactif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alertes avec animations -->
<div class="row">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Succès !</strong> Votre interface est maintenant ultra-moderne et réactive.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>

<!-- Boutons avec effets -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-mouse-pointer me-2"></i>
                    Boutons Interactifs
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <button class="btn btn-primary">
                        <i class="fas fa-star me-2"></i>
                        Bouton Primaire
                    </button>
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-heart me-2"></i>
                        Bouton Outline
                    </button>
                    <button class="btn btn-success">
                        <i class="fas fa-check me-2"></i>
                        Succès
                    </button>
                    <button class="btn btn-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Attention
                    </button>
                    <button class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>
                        Danger
                    </button>
                    <button class="btn btn-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Information
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
