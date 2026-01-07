<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Candidature;

class MigrateCandidatureFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candidatures:migrate-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrer les fichiers de candidatures du disque local vers public';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Migration des fichiers de candidatures...');

        $candidatures = Candidature::whereNotNull('cv_path')
            ->orWhereNotNull('lettre_recommandation_path')
            ->get();

        $migrated = 0;
        $errors = 0;

        foreach ($candidatures as $candidature) {
            // Migrer CV
            if ($candidature->cv_path && !Storage::disk('public')->exists($candidature->cv_path)) {
                if (Storage::disk('local')->exists($candidature->cv_path)) {
                    // Copier du disque local vers public
                    $content = Storage::disk('local')->get($candidature->cv_path);
                    Storage::disk('public')->put($candidature->cv_path, $content);
                    $migrated++;
                    $this->line("Migré CV: {$candidature->cv_path}");
                } else {
                    $this->error("CV non trouvé: {$candidature->cv_path}");
                    $errors++;
                }
            }

            // Migrer lettre de recommandation
            if ($candidature->lettre_recommandation_path && !Storage::disk('public')->exists($candidature->lettre_recommandation_path)) {
                if (Storage::disk('local')->exists($candidature->lettre_recommandation_path)) {
                    $content = Storage::disk('local')->get($candidature->lettre_recommandation_path);
                    Storage::disk('public')->put($candidature->lettre_recommandation_path, $content);
                    $migrated++;
                    $this->line("Migré lettre: {$candidature->lettre_recommandation_path}");
                } else {
                    $this->error("Lettre non trouvée: {$candidature->lettre_recommandation_path}");
                    $errors++;
                }
            }
        }

        $this->info("Migration terminée: {$migrated} fichiers migrés, {$errors} erreurs.");
    }
}
