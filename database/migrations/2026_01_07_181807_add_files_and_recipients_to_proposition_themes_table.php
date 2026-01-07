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
        // Créer la table si elle n'existe pas, sinon l'altérer
        if (!Schema::hasTable('proposition_themes')) {
            Schema::create('proposition_themes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
                $table->string('titre');
                $table->text('description');
                $table->text('objectifs')->nullable();
                $table->text('methodologie')->nullable();
                $table->string('fiche_stage_path')->nullable();
                $table->string('proposition_theme_path')->nullable();
                $table->boolean('envoye_au_directeur')->default(false);
                $table->boolean('envoye_a_l_admin')->default(false);
                $table->string('statut')->default('en_attente');
                $table->text('commentaires_admin')->nullable();
                $table->text('commentaires_enseignant')->nullable();
                $table->foreignId('directeur_memoire_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('date_soumission')->useCurrent();
                $table->timestamp('date_validation')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('proposition_themes', function (Blueprint $table) {
                if (!Schema::hasColumn('proposition_themes', 'fiche_stage_path')) {
                    $table->string('fiche_stage_path')->nullable()->after('methodologie');
                }
                if (!Schema::hasColumn('proposition_themes', 'proposition_theme_path')) {
                    $table->string('proposition_theme_path')->nullable()->after('fiche_stage_path');
                }
                if (!Schema::hasColumn('proposition_themes', 'envoye_au_directeur')) {
                    $table->boolean('envoye_au_directeur')->default(false)->after('proposition_theme_path');
                }
                if (!Schema::hasColumn('proposition_themes', 'envoye_a_l_admin')) {
                    $table->boolean('envoye_a_l_admin')->default(false)->after('envoye_au_directeur');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('proposition_themes')) {
            Schema::table('proposition_themes', function (Blueprint $table) {
                $table->dropColumn(['fiche_stage_path', 'proposition_theme_path', 'envoye_au_directeur', 'envoye_a_l_admin']);
            });
        }
    }
};
