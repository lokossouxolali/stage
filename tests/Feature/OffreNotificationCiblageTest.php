<?php

namespace Tests\Feature;

use App\Jobs\NotifierEtudiantsNouvelleOffre;
use App\Mail\OffrePubliee;
use App\Models\Entreprise;
use App\Models\Filiere;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OffreNotificationCiblageTest extends TestCase
{
    use RefreshDatabase;

    public function test_offer_creation_requires_a_target_filiere_and_dispatches_notification_job(): void
    {
        Queue::fake();

        $entreprise = Entreprise::create([
            'nom' => 'Entreprise Test',
            'email' => 'entreprise@example.com',
        ]);
        $filiere = Filiere::create(['nom' => 'Informatique']);
        $user = User::factory()->create([
            'role' => User::ROLE_ENTREPRISE,
            'entreprise_id' => $entreprise->id,
        ]);
        $payload = $this->validOfferPayload();

        $this->actingAs($user)
            ->post(route('offres.store'), $payload)
            ->assertSessionHasErrors('filiere_id');

        $this->actingAs($user)
            ->post(route('offres.store'), $payload + ['filiere_id' => $filiere->id])
            ->assertRedirect(route('offres.mes'));

        $this->assertDatabaseHas('offres', [
            'titre' => 'Stage développeur',
            'filiere_id' => $filiere->id,
        ]);
        Queue::assertPushed(NotifierEtudiantsNouvelleOffre::class);
    }

    public function test_only_active_validated_students_from_target_filiere_are_notified(): void
    {
        Mail::fake();

        $filiereCible = Filiere::create(['nom' => 'Informatique']);
        $autreFiliere = Filiere::create(['nom' => 'Gestion']);
        $destinataire = User::factory()->create([
            'role' => User::ROLE_ETUDIANT,
            'filiere_id' => $filiereCible->id,
            'est_actif' => true,
            'statut_inscription' => 'valide',
        ]);
        $horsDomaine = User::factory()->create([
            'role' => User::ROLE_ETUDIANT,
            'filiere_id' => $autreFiliere->id,
            'est_actif' => true,
            'statut_inscription' => 'valide',
        ]);
        $compteInactif = User::factory()->create([
            'role' => User::ROLE_ETUDIANT,
            'filiere_id' => $filiereCible->id,
            'est_actif' => false,
            'statut_inscription' => 'valide',
        ]);

        $offre = $this->createOffer($filiereCible);

        (new NotifierEtudiantsNouvelleOffre($offre))->handle();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $destinataire->id,
            'type' => 'offre_publiee',
        ]);
        $this->assertDatabaseMissing('notifications', ['user_id' => $horsDomaine->id]);
        $this->assertDatabaseMissing('notifications', ['user_id' => $compteInactif->id]);
        Mail::assertSent(OffrePubliee::class, fn (OffrePubliee $mail): bool => $mail->hasTo($destinataire->email));
        Mail::assertNotSent(OffrePubliee::class, fn (OffrePubliee $mail): bool => $mail->hasTo($horsDomaine->email));
    }

    private function createOffer(Filiere $filiere): Offre
    {
        $entreprise = Entreprise::create([
            'nom' => 'Entreprise Test',
            'email' => 'offres@example.com',
        ]);

        return Offre::create($this->validOfferPayload() + [
            'entreprise_id' => $entreprise->id,
            'filiere_id' => $filiere->id,
            'statut' => 'active',
        ]);
    }

    private function validOfferPayload(): array
    {
        return [
            'titre' => 'Stage développeur',
            'description' => 'Description du stage',
            'missions' => 'Développer une application',
            'duree' => 3,
            'type_stage' => 'Professionnel',
            'niveau_etude' => 'L3',
            'date_debut' => now()->addMonth()->toDateString(),
            'date_fin' => now()->addMonths(4)->toDateString(),
            'nombre_places' => 1,
        ];
    }
}
