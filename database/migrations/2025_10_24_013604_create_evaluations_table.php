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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluateur_id')->constrained('users')->onDelete('cascade');
            $table->enum('type_evaluateur', ['encadreur_entreprise', 'encadreur_academique', 'jury'])->default('encadreur_entreprise');
            $table->decimal('note_technique', 3, 1)->nullable(); // Note sur 20
            $table->decimal('note_communication', 3, 1)->nullable(); // Note sur 20
            $table->decimal('note_autonomie', 3, 1)->nullable(); // Note sur 20
            $table->decimal('note_ponctualite', 3, 1)->nullable(); // Note sur 20
            $table->decimal('note_globale', 3, 1)->nullable(); // Note sur 20
            $table->text('commentaires_positifs')->nullable();
            $table->text('commentaires_amelioration')->nullable();
            $table->text('recommandations')->nullable();
            $table->enum('statut', ['brouillon', 'finalisee'])->default('brouillon');
            $table->date('date_evaluation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
