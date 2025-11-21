<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin (toujours validé et actif par défaut)
        User::updateOrCreate(
            ['email' => 'admin@stage.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'telephone' => '+33 1 00 00 00 00',
                'statut_inscription' => 'valide',
                'est_actif' => true,
            ]
        );

        // Responsable des stages
        User::updateOrCreate(
            ['email' => 'marie.dubois@stage.com'],
            [
                'name' => 'Marie Dubois',
                'password' => Hash::make('password'),
                'role' => 'responsable_stages',
                'telephone' => '+33 1 00 00 00 01',
                'statut_inscription' => 'valide',
                'est_actif' => true,
            ]
        );

        // Enseignants
        $enseignant1 = User::updateOrCreate(
            ['email' => 'martin@stage.com'],
            [
                'name' => 'Professeur Martin',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'telephone' => '+33 1 00 00 00 02',
                'statut_inscription' => 'valide',
                'est_actif' => true,
            ]
        );

        $enseignant2 = User::updateOrCreate(
            ['email' => 'dubois@stage.com'],
            [
                'name' => 'Professeur Dubois',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'telephone' => '+33 1 00 00 00 05',
                'statut_inscription' => 'valide',
                'est_actif' => true,
            ]
        );

        // Étudiants
        $etudiant1 = User::updateOrCreate(
            ['email' => 'jean.dupont@student.com'],
            [
                'name' => 'Jean Dupont',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'telephone' => '+33 1 00 00 00 03',
                'niveau_etude' => 'M2',
                'filiere' => 'Informatique',
                'statut_inscription' => 'valide',
                'est_actif' => true,
                'directeur_memoire_id' => $enseignant1->id,
            ]
        );

        $etudiant2 = User::updateOrCreate(
            ['email' => 'sophie.martin@student.com'],
            [
                'name' => 'Sophie Martin',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'telephone' => '+33 1 00 00 00 04',
                'niveau_etude' => 'M1',
                'filiere' => 'Génie Logiciel',
                'statut_inscription' => 'valide',
                'est_actif' => true,
                'directeur_memoire_id' => $enseignant2->id,
            ]
        );

        // Étudiant en attente de validation
        User::updateOrCreate(
            ['email' => 'paul.durand@student.com'],
            [
                'name' => 'Paul Durand',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'telephone' => '+33 1 00 00 00 06',
                'niveau_etude' => 'M1',
                'filiere' => 'Informatique',
                'statut_inscription' => 'en_attente',
                'est_actif' => false,
            ]
        );

        // Représentants d'entreprises
        // Note: Les entreprises_id seront assignés manuellement via l'interface d'administration
        User::updateOrCreate(
            ['email' => 'pierre@techcorp.com'],
            [
                'name' => 'Pierre TechCorp',
                'password' => Hash::make('password'),
                'role' => 'entreprise',
                'telephone' => '+33 1 23 45 67 89',
                'entreprise_id' => null,
                'statut_inscription' => 'valide',
                'est_actif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'alice@dataflow.com'],
            [
                'name' => 'Alice DataFlow',
                'password' => Hash::make('password'),
                'role' => 'entreprise',
                'telephone' => '+33 1 98 76 54 32',
                'entreprise_id' => null,
                'statut_inscription' => 'valide',
                'est_actif' => true,
            ]
        );

        // Entreprise en attente de validation
        User::updateOrCreate(
            ['email' => 'marc@innovation.com'],
            [
                'name' => 'Marc Innovation',
                'password' => Hash::make('password'),
                'role' => 'entreprise',
                'telephone' => '+33 1 11 22 33 44',
                'entreprise_id' => null,
                'statut_inscription' => 'en_attente',
                'est_actif' => false,
            ]
        );
    }
}
