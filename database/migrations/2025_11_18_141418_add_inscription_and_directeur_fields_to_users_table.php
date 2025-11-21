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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('statut_inscription', ['en_attente', 'valide', 'refuse'])->default('en_attente')->after('est_actif');
            $table->foreignId('directeur_memoire_id')->nullable()->after('entreprise_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['directeur_memoire_id']);
            $table->dropColumn(['statut_inscription', 'directeur_memoire_id']);
        });
    }
};
