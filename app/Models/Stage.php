<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidature_id',
        'encadreur_entreprise_id',
        'encadreur_academique_id',
        'date_debut',
        'date_fin',
        'objectifs',
        'planning',
        'statut',
        'commentaires_encadreur_entreprise',
        'commentaires_encadreur_academique',
        'commentaires_etudiant',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
        ];
    }

    // Relations
    public function candidature()
    {
        return $this->belongsTo(Candidature::class);
    }

    public function encadreurEntreprise()
    {
        return $this->belongsTo(User::class, 'encadreur_entreprise_id');
    }

    public function encadreurAcademique()
    {
        return $this->belongsTo(User::class, 'encadreur_academique_id');
    }

    public function rapports()
    {
        return $this->hasMany(Rapport::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function soutenances()
    {
        return $this->hasMany(Soutenance::class);
    }

    // Scopes
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }
}
