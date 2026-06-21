<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Responsable pedagogique (ancien role admin)
        User::updateOrCreate(
            ['email' => 'lokossouxolali@gmail.com'],
            [
                'name' => 'Responsable Pedagogique',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'telephone' => '+228 96 98 68 75',
                'statut_inscription' => 'valide',
                'est_actif' => true,
            ]
        );

        // Super Administrateur par defaut
        User::updateOrCreate(
            ['email' => 'superadmin@stage.local'],
            [
                'name' => 'Super Administrateur',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SUPER_ADMIN,
                'telephone' => null,
                'statut_inscription' => 'valide',
                'est_actif' => true,
            ]
        );
    }
}
