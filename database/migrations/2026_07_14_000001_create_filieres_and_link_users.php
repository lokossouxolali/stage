<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('code', 50)->nullable()->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->restrictOnDelete();
        });

        if (Schema::hasColumn('users', 'filiere')) {
            DB::table('users')
                ->whereNotNull('filiere')
                ->where('filiere', '!=', '')
                ->pluck('filiere')
                ->unique()
                ->sort()
                ->each(function (string $ancienneValeur): void {
                    $nom = trim($ancienneValeur);

                    if ($nom === '') {
                        return;
                    }

                    $filiereId = DB::table('filieres')->where('nom', $nom)->value('id');

                    if (! $filiereId) {
                        $filiereId = DB::table('filieres')->insertGetId([
                            'nom' => $nom,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    DB::table('users')->where('filiere', $ancienneValeur)->update(['filiere_id' => $filiereId]);
                });

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('filiere');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('filiere')->nullable();
        });

        DB::table('users')->orderBy('id')->eachById(function (object $user): void {
            if ($user->filiere_id) {
                DB::table('users')->where('id', $user->id)->update([
                    'filiere' => DB::table('filieres')->where('id', $user->filiere_id)->value('nom'),
                ]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('filiere_id');
        });

        Schema::dropIfExists('filieres');
    }
};
