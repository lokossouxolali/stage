<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('password_change_otps', function (Blueprint $table) {
            $table->string('password_hash')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('password_change_otps', function (Blueprint $table) {
            $table->string('password_hash')->nullable(false)->change();
        });
    }
};
