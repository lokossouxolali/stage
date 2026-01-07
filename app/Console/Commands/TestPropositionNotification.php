<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PropositionTheme;
use App\Models\User;
use App\Notifications\PropositionThemeStatusNotification;

class TestPropositionNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:proposition-notification {user_email?} {--status=valide} {--commentaires=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester l\'envoi des notifications pour les propositions de thème';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userEmail = $this->argument('user_email') ?? 'test@example.com';
        $status = $this->option('status');
        $commentaires = $this->option('commentaires');

        // Trouver ou créer un utilisateur de test
        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->info("Utilisateur avec l'email {$userEmail} non trouvé. Création d'un utilisateur de test...");
            $user = User::create([
                'name' => 'Utilisateur Test',
                'email' => $userEmail,
                'password' => bcrypt('password'),
                'role' => 'etudiant',
            ]);
        }

        // Créer une proposition de test
        $proposition = PropositionTheme::create([
            'titre' => 'Proposition de test pour notification',
            'description' => 'Ceci est une proposition de test pour vérifier le système de notifications par email.',
            'etudiant_id' => $user->id,
            'statut' => 'en_attente',
        ]);

        $this->info("Proposition créée avec l'ID: {$proposition->id}");

        // Envoyer la notification
        $this->info("Envoi de la notification avec le statut: {$status}");
        $user->notify(new PropositionThemeStatusNotification($proposition, $status, $commentaires));

        $this->info("Notification envoyée avec succès !");
        $this->info("Vérifiez les logs ou la configuration email pour voir le résultat.");

        return Command::SUCCESS;
    }
}
