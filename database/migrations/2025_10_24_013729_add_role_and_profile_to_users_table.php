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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'responsable_stages', 'enseignant', 'etudiant', 'entreprise', 'jury'])->default('etudiant');
            $table->string('telephone')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('niveau_etude')->nullable();
            $table->string('filiere')->nullable();
            $table->text('cv_path')->nullable();
            $table->text('photo_path')->nullable();
            $table->boolean('est_actif')->default(true);
            $table->foreignId('entreprise_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['entreprise_id']);
            $table->dropColumn([
                'role', 'telephone', 'date_naissance', 'niveau_etude', 
                'filiere', 'cv_path', 'photo_path', 'est_actif', 'entreprise_id'
            ]);
        });
    }
};
