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
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
            $table->text('missions')->nullable();
            $table->text('competences_requises')->nullable();
            $table->string('duree')->nullable(); // ex: "3 mois", "6 mois"
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->string('lieu')->nullable();
            $table->enum('type_stage', ['Obligatoire', 'Perfectionnement', 'Projet_fin_etudes'])->default('Obligatoire');
            $table->enum('niveau_etude', ['L1', 'L2', 'L3', 'M1', 'M2', 'Doctorat'])->nullable();
            $table->string('remuneration')->nullable();
            $table->enum('statut', ['active', 'fermee', 'suspendue'])->default('active');
            $table->date('date_limite_candidature')->nullable();
            $table->integer('nombre_places')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
