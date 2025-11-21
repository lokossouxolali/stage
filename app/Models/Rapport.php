<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'etudiant_id',
        'type_rapport',
        'titre',
        'fichier_path',
        'version',
        'statut',
        'destinataire',
        'commentaires_encadreur_entreprise',
        'commentaires_encadreur_academique',
        'date_soumission',
        'date_validation',
    ];

    protected function casts(): array
    {
        return [
            'date_soumission' => 'date',
            'date_validation' => 'date',
        ];
    }

    // Relations
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    // Scopes
    public function scopeSoumis($query)
    {
        return $query->where('statut', 'soumis');
    }

    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }
}
