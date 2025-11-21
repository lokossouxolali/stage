<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropositionTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'titre',
        'description',
        'objectifs',
        'methodologie',
        'statut',
        'commentaires_admin',
        'commentaires_enseignant',
        'directeur_memoire_id',
        'date_soumission',
        'date_validation',
    ];

    protected function casts(): array
    {
        return [
            'date_soumission' => 'datetime',
            'date_validation' => 'datetime',
        ];
    }

    // Relations
    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function directeurMemoire()
    {
        return $this->belongsTo(User::class, 'directeur_memoire_id');
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValidees($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeRefusees($query)
    {
        return $query->where('statut', 'refuse');
    }
}
