<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PropositionThemeController;
use App\Http\Controllers\NotificationController;

// Route d'accueil
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour les entreprises
    // Admin : accès complet (CRUD)
    // Entreprises : consultation seulement
    Route::get('/entreprises', [EntrepriseController::class, 'index'])->name('entreprises.index');
    Route::get('/entreprises/{entreprise}', [EntrepriseController::class, 'show'])->name('entreprises.show');
    
    Route::middleware('role:admin')->group(function () {
        Route::get('/entreprises/create', [EntrepriseController::class, 'create'])->name('entreprises.create');
        Route::post('/entreprises', [EntrepriseController::class, 'store'])->name('entreprises.store');
        Route::get('/entreprises/{entreprise}/edit', [EntrepriseController::class, 'edit'])->name('entreprises.edit');
        Route::patch('/entreprises/{entreprise}', [EntrepriseController::class, 'update'])->name('entreprises.update');
        Route::delete('/entreprises/{entreprise}', [EntrepriseController::class, 'destroy'])->name('entreprises.destroy');
    });
    
    // Routes pour les offres (entreprises et admin)
    Route::middleware('role:entreprise,admin')->group(function () {
        Route::get('/offres/create', [OffreController::class, 'create'])->name('offres.create');
        Route::post('/offres', [OffreController::class, 'store'])->name('offres.store');
        Route::get('/offres/{offre}/edit', [OffreController::class, 'edit'])->name('offres.edit');
        Route::patch('/offres/{offre}', [OffreController::class, 'update'])->name('offres.update');
        Route::delete('/offres/{offre}', [OffreController::class, 'destroy'])->name('offres.destroy');
    });
    
    // Consultation des offres (tous les utilisateurs authentifiés)
    Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');
    Route::get('/offres/{offre}', [OffreController::class, 'show'])->name('offres.show');
    
    // Routes pour les candidatures
    Route::get('/candidatures/create/{offre?}', [CandidatureController::class, 'create'])->name('candidatures.create');
    Route::resource('candidatures', CandidatureController::class)->except(['create']);
    
    // Routes pour les rapports
    Route::resource('rapports', RapportController::class);
    
    // Routes pour les utilisateurs (admin seulement)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/valider-inscription', [UserController::class, 'validerInscription'])->name('users.valider-inscription');
        Route::patch('/users/{user}/refuser-inscription', [UserController::class, 'refuserInscription'])->name('users.refuser-inscription');
        Route::post('/users/export', [UserController::class, 'export'])->name('users.export');
    });
    
    // Routes spécifiques pour les entreprises
    Route::middleware('role:entreprise,admin')->group(function () {
        Route::get('/mes-offres', [OffreController::class, 'mesOffres'])->name('offres.mes');
        Route::get('/candidatures-recues', [CandidatureController::class, 'candidaturesRecues'])->name('candidatures.recues');
    });
    
    // Routes spécifiques pour les étudiants
    Route::middleware('role:etudiant,admin')->group(function () {
        Route::get('/mes-candidatures', [CandidatureController::class, 'mesCandidatures'])->name('candidatures.mes');
        Route::get('/offres-disponibles', [OffreController::class, 'offresDisponibles'])->name('offres.disponibles');
        Route::get('/choisir-directeur-memoire', function() {
            $enseignants = \App\Models\User::where('role', 'enseignant')
                ->where('statut_inscription', 'valide')
                ->where('est_actif', true)
                ->orderBy('name')
                ->get();
            return view('users.choisir-directeur', compact('enseignants'));
        })->name('users.choisir-directeur-memoire');
        Route::post('/choisir-directeur-memoire', [UserController::class, 'choisirDirecteurMemoire'])->name('users.choisir-directeur-memoire.store');
        Route::get('/mes-rapports', [RapportController::class, 'mesRapports'])->name('rapports.mes');
        Route::get('/liste-enseignants', [UserController::class, 'listeEnseignants'])->name('users.liste-enseignants');
    });
    
    // Routes spécifiques pour les enseignants
    Route::middleware('role:enseignant,admin')->group(function () {
        Route::get('/rapports-encadres', [RapportController::class, 'rapportsEncadres'])->name('rapports.encadres');
        Route::post('/rapports/{rapport}/commentaire', [RapportController::class, 'ajouterCommentaire'])->name('rapports.commentaire');
        Route::get('/etudiants-encadres', [UserController::class, 'etudiantsEncadres'])->name('users.etudiants-encadres');
        Route::get('/demandes-encadrement', [UserController::class, 'demandesEncadrement'])->name('demandes-encadrement.index');
        Route::post('/demandes-encadrement/{etudiant}/accepter', [UserController::class, 'accepterDemandeEncadrement'])->name('demandes-encadrement.accepter');
        Route::post('/demandes-encadrement/{etudiant}/refuser', [UserController::class, 'refuserDemandeEncadrement'])->name('demandes-encadrement.refuser');
    });
    
    // Routes pour les actions sur les candidatures
    Route::patch('/candidatures/{candidature}/accepter', [CandidatureController::class, 'accepter'])->name('candidatures.accepter');
    Route::patch('/candidatures/{candidature}/refuser', [CandidatureController::class, 'refuser'])->name('candidatures.refuser');
    Route::get('/candidatures/{candidature}/cv', [CandidatureController::class, 'downloadCv'])->name('candidatures.download.cv');
    Route::get('/candidatures/{candidature}/lettre-recommandation', [CandidatureController::class, 'downloadLettreRecommandation'])->name('candidatures.download.lettre');
    
    // Routes pour les actions sur les rapports
    Route::get('/rapports/{rapport}/download', [RapportController::class, 'download'])->name('rapports.download');
    Route::middleware('role:admin,enseignant')->group(function () {
        Route::patch('/rapports/{rapport}/valider', [RapportController::class, 'valider'])->name('rapports.valider');
        Route::patch('/rapports/{rapport}/rejeter', [RapportController::class, 'rejeter'])->name('rapports.rejeter');
    });
    
    // Routes pour les statistiques (admin seulement)
    Route::middleware('role:admin')->group(function () {
        Route::get('/statistiques', [DashboardController::class, 'statistiques'])->name('statistiques');
    });
    
    // Routes pour les profils utilisateur
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/password', [UserController::class, 'editPassword'])->name('profile.password');
    Route::patch('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');
    
    // Routes pour les notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'marquerLue'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'marquerToutesLues'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/nombre-non-lues', [NotificationController::class, 'nombreNonLues'])->name('notifications.nombre-non-lues');
    
    // Routes pour les propositions de thèmes
    Route::middleware('role:etudiant,admin')->group(function () {
        Route::get('/propositions/mes', [PropositionThemeController::class, 'mesPropositions'])->name('propositions.mes');
        Route::get('/propositions/create', [PropositionThemeController::class, 'create'])->name('propositions.create');
        Route::post('/propositions', [PropositionThemeController::class, 'store'])->name('propositions.store');
        Route::get('/propositions/{proposition}/edit', [PropositionThemeController::class, 'edit'])->name('propositions.edit');
        Route::patch('/propositions/{proposition}', [PropositionThemeController::class, 'update'])->name('propositions.update');
        Route::delete('/propositions/{proposition}', [PropositionThemeController::class, 'destroy'])->name('propositions.destroy');
    });
    
    Route::get('/propositions/{proposition}', [PropositionThemeController::class, 'show'])->name('propositions.show');
    
    // Routes admin pour les propositions
    Route::middleware('role:admin')->group(function () {
        Route::get('/propositions', [PropositionThemeController::class, 'index'])->name('propositions.index');
        Route::patch('/propositions/{proposition}/valider', [PropositionThemeController::class, 'valider'])->name('propositions.valider');
        Route::patch('/propositions/{proposition}/refuser', [PropositionThemeController::class, 'refuser'])->name('propositions.refuser');
    });
    
    // Routes enseignant pour les propositions
    Route::middleware('role:enseignant,admin')->group(function () {
        Route::get('/propositions-encadrees', [PropositionThemeController::class, 'propositionsEncadrees'])->name('propositions.encadrees');
        Route::post('/propositions/{proposition}/commentaire', [PropositionThemeController::class, 'ajouterCommentaire'])->name('propositions.commentaire');
        Route::patch('/propositions/{proposition}/valider-enseignant', [PropositionThemeController::class, 'validerParEnseignant'])->name('propositions.valider-enseignant');
        Route::patch('/propositions/{proposition}/rejeter-enseignant', [PropositionThemeController::class, 'rejeterParEnseignant'])->name('propositions.rejeter-enseignant');
    });
});
