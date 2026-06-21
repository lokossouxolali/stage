<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            if (!Schema::hasColumn('candidatures', 'lettre_motivation_path')) {
                $table->string('lettre_motivation_path')->nullable()->after('lettre_motivation');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::table('offres')
                ->whereIn('type_stage', ['Obligatoire', 'Projet_fin_etudes'])
                ->update(['type_stage' => 'Perfectionnement']);

            DB::statement("ALTER TABLE offres MODIFY type_stage ENUM('Perfectionnement', 'Professionnel', 'Académique', 'Mémoire') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE offres MODIFY type_stage ENUM('Obligatoire', 'Perfectionnement', 'Projet_fin_etudes') NOT NULL DEFAULT 'Obligatoire'");
        }

        Schema::table('candidatures', function (Blueprint $table) {
            if (Schema::hasColumn('candidatures', 'lettre_motivation_path')) {
                $table->dropColumn('lettre_motivation_path');
            }
        });
    }
};
