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
            $table->enum('statut_demande_dm', ['en_attente', 'accepte', 'refuse'])->nullable()->after('directeur_memoire_id');
            $table->text('raison_refus_dm')->nullable()->after('statut_demande_dm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['statut_demande_dm', 'raison_refus_dm']);
        });
    }
};
