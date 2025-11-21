<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise_id',
        'titre',
        'description',
        'missions',
        'competences_requises',
        'duree',
        'date_debut',
        'date_fin',
        'lieu',
        'type_stage',
        'niveau_etude',
        'remuneration',
        'statut',
        'date_limite_candidature',
        'nombre_places',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'date_limite_candidature' => 'date',
        ];
    }

    // Relations
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopeDisponible($query)
    {
        return $query->where('date_limite_candidature', '>=', now())
                    ->orWhereNull('date_limite_candidature');
    }
}
