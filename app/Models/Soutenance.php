<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soutenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'date_soutenance',
        'lieu',
        'salle',
        'description',
        'statut',
        'note_soutenance',
        'commentaires_jury',
        'recommandations_finales',
    ];

    protected function casts(): array
    {
        return [
            'date_soutenance' => 'datetime',
            'note_soutenance' => 'decimal:1',
        ];
    }

    // Relations
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    // Scopes
    public function scopePlanifiees($query)
    {
        return $query->where('statut', 'planifiee');
    }

    public function scopeTerminees($query)
    {
        return $query->where('statut', 'terminee');
    }
}
