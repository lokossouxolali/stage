<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'offre_id',
        'lettre_motivation',
        'cv_path',
        'lettre_recommandation_path',
        'commentaires_etudiant',
        'commentaires_entreprise',
        'statut',
        'date_candidature',
        'date_reponse',
    ];

    protected function casts(): array
    {
        return [
            'date_candidature' => 'date',
            'date_reponse' => 'date',
        ];
    }

    // Relations
    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    public function stage()
    {
        return $this->hasOne(Stage::class);
    }

    // Scopes
    public function scopeAcceptees($query)
    {
        return $query->where('statut', 'acceptee');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }
}
