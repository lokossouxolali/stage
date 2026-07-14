<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialites', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('code', 50)->nullable()->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('specialite_id')
                ->nullable()
                ->constrained('specialites')
                ->restrictOnDelete();
        });

        if (! Schema::hasColumn('users', 'specialite')) {
            return;
        }

        DB::table('users')
            ->whereNotNull('specialite')
            ->where('specialite', '!=', '')
            ->pluck('specialite')
            ->unique()
            ->sort()
            ->each(function (string $ancienneValeur): void {
                $nom = trim($ancienneValeur);

                if ($nom === '') {
                    return;
                }

                $specialiteId = DB::table('specialites')->where('nom', $nom)->value('id');

                if (! $specialiteId) {
                    $specialiteId = DB::table('specialites')->insertGetId([
                        'nom' => $nom,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('users')
                    ->where('specialite', $ancienneValeur)
                    ->update(['specialite_id' => $specialiteId]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('specialite');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('specialite')->nullable();
        });

        DB::table('users')->orderBy('id')->eachById(function (object $user): void {
            if ($user->specialite_id) {
                DB::table('users')->where('id', $user->id)->update([
                    'specialite' => DB::table('specialites')->where('id', $user->specialite_id)->value('nom'),
                ]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('specialite_id');
        });

        Schema::dropIfExists('specialites');
    }
};
