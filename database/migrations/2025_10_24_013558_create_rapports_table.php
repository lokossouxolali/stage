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
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('contenu')->nullable();
            $table->string('fichier_path')->nullable();
            $table->integer('version')->default(1);
            $table->enum('statut', ['brouillon', 'soumis', 'valide', 'rejete', 'en_revision'])->default('brouillon');
            $table->text('commentaires_encadreur_entreprise')->nullable();
            $table->text('commentaires_encadreur_academique')->nullable();
            $table->date('date_soumission')->nullable();
            $table->date('date_validation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
