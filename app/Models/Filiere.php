<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'description',
    ];

    public function etudiants(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class);
    }
}
