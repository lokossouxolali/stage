<?php

namespace App\Jobs;

use App\Mail\OffrePubliee;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class NotifierEtudiantsNouvelleOffre implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 300;

    public function __construct(public Offre $offre) {}

    public function handle(): void
    {
        if (! $this->offre->filiere_id) {
            return;
        }

        $this->offre->load('entreprise');

        $nomEntreprise = $this->offre->entreprise->nom ?? 'une entreprise';
        $lien = route('offres.show', $this->offre);
        $now = now();

        // Traitement par lots de 50 pour éviter l'épuisement mémoire
        User::where('role', User::ROLE_ETUDIANT)
            ->where('filiere_id', $this->offre->filiere_id)
            ->where('est_actif', true)
            ->where('statut_inscription', 'valide')
            ->select(['id', 'name', 'email'])
            ->chunkById(50, function ($etudiants) use ($nomEntreprise, $lien, $now) {
                // Notifications in-app (batch insert)
                $notifications = $etudiants->map(fn ($e) => [
                    'user_id' => $e->id,
                    'type' => 'offre_publiee',
                    'titre' => 'Nouvelle offre de stage publiée',
                    'message' => 'Une nouvelle offre "'.$this->offre->titre.'" a été publiée par '.$nomEntreprise.'.',
                    'lien' => $lien,
                    'lu' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

                Notification::insert($notifications);

                // Email à chaque étudiant
                foreach ($etudiants as $etudiant) {
                    try {
                        Mail::to($etudiant->email)->send(new OffrePubliee($this->offre, $etudiant));
                    } catch (\Exception $e) {
                        \Log::error('Email offre publiée échoué pour '.$etudiant->email.' : '.$e->getMessage());
                    }
                }
            });
    }
}
