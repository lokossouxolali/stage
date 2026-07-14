<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pending_registrations', 'registration_token_id')) {
            Schema::table('pending_registrations', function (Blueprint $table) {
                $table->dropConstrainedForeignId('registration_token_id');
            });
        }

        Schema::dropIfExists('registration_tokens');
    }

    public function down(): void
    {
        // Le mecanisme de jetons d'inscription a ete supprime definitivement.
    }
};
