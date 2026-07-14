<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_ADMIN = 'responsable_pedagogique';

    public const ROLE_LEGACY_ADMIN = 'admin';

    public const ROLE_ETUDIANT = 'etudiant';

    public const ROLE_ENTREPRISE = 'entreprise';

    public const ROLE_ENSEIGNANT = 'enseignant';

    public const ROLE_RESPONSABLE_STAGES = 'responsable_stages';

    public const ROLE_LABELS = [
        self::ROLE_SUPER_ADMIN => 'Super Administrateur',
        self::ROLE_ADMIN => 'Chef de Département',
        self::ROLE_LEGACY_ADMIN => 'Chef de Département',
        self::ROLE_ETUDIANT => 'Etudiant',
        self::ROLE_ENTREPRISE => 'Entreprise',
        self::ROLE_ENSEIGNANT => 'Enseignant',
        self::ROLE_RESPONSABLE_STAGES => 'Responsable stages',
    ];

    public const MANAGED_ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_ETUDIANT,
        self::ROLE_ENTREPRISE,
        self::ROLE_ENSEIGNANT,
    ];

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
        'filiere_id',
        'specialite_id',
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
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            $version = $this->updated_at ? $this->updated_at->timestamp : time();

            return route('users.photo', ['user' => $this->id, 'v' => $version]);
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

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    public function specialite(): BelongsTo
    {
        return $this->belongsTo(Specialite::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'etudiant_id');
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
    public static function roleLabels(): array
    {
        return self::ROLE_LABELS;
    }

    public static function administrativeRoles(): array
    {
        return [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_LEGACY_ADMIN];
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role));
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin()
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_LEGACY_ADMIN], true) || $this->isSuperAdmin();
    }

    public function isResponsablePedagogique()
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_LEGACY_ADMIN], true);
    }

    public function isEtudiant()
    {
        return $this->role === self::ROLE_ETUDIANT;
    }

    public function isEntreprise()
    {
        return $this->role === self::ROLE_ENTREPRISE;
    }

    public function isEnseignant()
    {
        return $this->role === self::ROLE_ENSEIGNANT;
    }
}
