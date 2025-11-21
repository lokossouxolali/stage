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
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('offre_id')->constrained()->onDelete('cascade');
            $table->text('lettre_motivation')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('lettre_recommandation_path')->nullable();
            $table->text('commentaires_etudiant')->nullable();
            $table->text('commentaires_entreprise')->nullable();
            $table->enum('statut', ['en_attente', 'acceptee', 'refusee', 'en_cours_evaluation'])->default('en_attente');
            $table->date('date_candidature')->default(now());
            $table->date('date_reponse')->nullable();
            $table->timestamps();
            
            // Contrainte unique pour éviter les candidatures multiples
            $table->unique(['etudiant_id', 'offre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
