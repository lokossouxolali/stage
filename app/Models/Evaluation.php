<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'evaluateur_id',
        'type_evaluateur',
        'note_technique',
        'note_communication',
        'note_autonomie',
        'note_ponctualite',
        'note_globale',
        'commentaires_positifs',
        'commentaires_amelioration',
        'recommandations',
        'statut',
        'date_evaluation',
    ];

    protected function casts(): array
    {
        return [
            'note_technique' => 'decimal:1',
            'note_communication' => 'decimal:1',
            'note_autonomie' => 'decimal:1',
            'note_ponctualite' => 'decimal:1',
            'note_globale' => 'decimal:1',
            'date_evaluation' => 'date',
        ];
    }

    // Relations
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function evaluateur()
    {
        return $this->belongsTo(User::class, 'evaluateur_id');
    }

    // Scopes
    public function scopeFinalisees($query)
    {
        return $query->where('statut', 'finalisee');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_evaluateur', $type);
    }
}
