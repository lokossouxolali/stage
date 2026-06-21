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
        DB::table('rapports')
            ->join('stages', 'rapports.stage_id', '=', 'stages.id')
            ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
            ->whereNull('rapports.etudiant_id')
            ->whereNotNull('rapports.stage_id')
            ->select('rapports.id', 'candidatures.etudiant_id')
            ->orderBy('rapports.id')
            ->each(function ($rapport) {
                DB::table('rapports')
                    ->where('id', $rapport->id)
                    ->update(['etudiant_id' => $rapport->etudiant_id]);
            });
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
