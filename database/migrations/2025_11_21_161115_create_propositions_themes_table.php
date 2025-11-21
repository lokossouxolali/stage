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
        Schema::create('propositions_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
            $table->text('objectifs')->nullable();
            $table->text('methodologie')->nullable();
            $table->string('statut')->default('en_attente'); // en_attente, valide, refuse
            $table->text('commentaires_admin')->nullable();
            $table->text('commentaires_enseignant')->nullable();
            $table->foreignId('directeur_memoire_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_soumission')->useCurrent();
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propositions_themes');
    }
};
