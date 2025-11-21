<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone',
        'date_naissance',
        'niveau_etude',
        'filiere',
        'cv_path',
        'photo_path',
        'est_actif',
        'entreprise_id',
        'statut_inscription',
        'directeur_memoire_id',
        'statut_demande_dm',
        'raison_refus_dm',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_naissance' => 'date',
            'est_actif' => 'boolean',
        ];
    }

    /**
     * Obtenir l'URL de la photo de profil
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            // Utiliser asset() pour générer l'URL relative au domaine
            // Le lien symbolique public/storage pointe vers storage/app/public
            return asset('storage/' . $this->photo_path);
        }
        return null;
    }

    /**
     * Obtenir l'avatar (photo ou initiale)
     */
    public function getAvatarAttribute()
    {
        if ($this->photo_path) {
            return $this->photo_url;
        }
        return null;
    }

    // Relations
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'etudiant_id');
    }

    public function stagesEncadresEntreprise()
    {
        return $this->hasMany(Stage::class, 'encadreur_entreprise_id');
    }

    public function stagesEncadresAcademique()
    {
        return $this->hasMany(Stage::class, 'encadreur_academique_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluateur_id');
    }

    public function directeurMemoire()
    {
        return $this->belongsTo(User::class, 'directeur_memoire_id');
    }

    public function etudiantsEncadres()
    {
        return $this->hasMany(User::class, 'directeur_memoire_id');
    }

    public function propositionsThemes()
    {
        return $this->hasMany(PropositionTheme::class, 'etudiant_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Méthodes utilitaires
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEtudiant()
    {
        return $this->role === 'etudiant';
    }

    public function isEntreprise()
    {
        return $this->role === 'entreprise';
    }

    public function isEnseignant()
    {
        return $this->role === 'enseignant';
    }
}
