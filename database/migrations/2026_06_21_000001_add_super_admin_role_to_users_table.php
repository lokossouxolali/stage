<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'responsable_pedagogique', 'admin', 'responsable_stages', 'enseignant', 'etudiant', 'entreprise', 'jury') NOT NULL DEFAULT 'etudiant'");
        }

        DB::table('users')
            ->where('role', 'admin')
            ->update(['role' => 'responsable_pedagogique']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'responsable_pedagogique', 'responsable_stages', 'enseignant', 'etudiant', 'entreprise', 'jury') NOT NULL DEFAULT 'etudiant'");
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            return;
        }

        DB::table('users')
            ->where('role', 'super_admin')
            ->update(['role' => 'responsable_pedagogique']);

        DB::table('users')
            ->where('role', 'responsable_pedagogique')
            ->update(['role' => 'admin']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin', 'responsable_pedagogique', 'admin', 'responsable_stages', 'enseignant', 'etudiant', 'entreprise', 'jury') NOT NULL DEFAULT 'etudiant'");
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'responsable_stages', 'enseignant', 'etudiant', 'entreprise', 'jury') NOT NULL DEFAULT 'etudiant'");
        }
    }
};
