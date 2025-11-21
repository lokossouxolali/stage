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
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidature_id')->constrained()->onDelete('cascade');
            $table->foreignId('encadreur_entreprise_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('encadreur_academique_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->text('objectifs')->nullable();
            $table->text('planning')->nullable();
            $table->enum('statut', ['en_cours', 'termine', 'suspendu', 'abandonne'])->default('en_cours');
            $table->text('commentaires_encadreur_entreprise')->nullable();
            $table->text('commentaires_encadreur_academique')->nullable();
            $table->text('commentaires_etudiant')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
