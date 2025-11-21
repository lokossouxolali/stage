<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les rapports existants qui ont un stage_id mais pas d'etudiant_id
        DB::statement("
            UPDATE rapports r
            INNER JOIN stages s ON r.stage_id = s.id
            INNER JOIN candidatures c ON s.candidature_id = c.id
            SET r.etudiant_id = c.etudiant_id
            WHERE r.etudiant_id IS NULL AND r.stage_id IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // On ne peut pas vraiment annuler cette opération sans perdre de données
        // On laisse vide car c'est une migration de données
    }
};
