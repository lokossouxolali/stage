<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'adresse',
        'secteur_activite',
        'description',
        'site_web',
        'est_verifiee',
    ];

    protected function casts(): array
    {
        return [
            'est_verifiee' => 'boolean',
        ];
    }

    // Relations
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function offres()
    {
        return $this->hasMany(Offre::class);
    }
}
