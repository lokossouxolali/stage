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
        Schema::table('rapports', function (Blueprint $table) {
            // Rendre stage_id nullable (on peut avoir des rapports sans stage maintenant)
            $table->foreignId('stage_id')->nullable()->change();
            
            // Ajouter type_rapport
            $table->enum('type_rapport', ['memoire', 'proposition_theme'])->nullable()->after('stage_id');
            
            // Supprimer contenu (plus nécessaire)
            $table->dropColumn('contenu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            // Restaurer contenu
            $table->text('contenu')->nullable();
            
            // Supprimer type_rapport
            $table->dropColumn('type_rapport');
            
            // Restaurer stage_id comme non nullable
            $table->foreignId('stage_id')->nullable(false)->change();
        });
    }
};
