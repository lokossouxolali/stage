<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Supprimer les tables dans l'ordre inverse des dépendances
        // D'abord les tables qui dépendent de stages
        Schema::dropIfExists('soutenances');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('rapports');
        // Ensuite la table stages
        Schema::dropIfExists('stages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cette migration ne peut pas être inversée car nous ne recréons pas les tables
        // Les migrations originales doivent être utilisées pour recréer les tables si nécessaire
    }
};
