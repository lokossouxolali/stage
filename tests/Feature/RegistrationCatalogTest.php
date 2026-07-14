<?php

namespace Tests\Feature;

use App\Models\Filiere;
use App\Models\Specialite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_form_displays_both_catalogs(): void
    {
        Filiere::create(['nom' => 'Informatique', 'code' => 'INFO']);
        Specialite::create(['nom' => 'Génie logiciel', 'code' => 'GL']);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Informatique')
            ->assertSee('Génie logiciel');
    }

    public function test_api_exposes_registration_options(): void
    {
        Filiere::create(['nom' => 'Informatique', 'code' => 'INFO']);
        Specialite::create(['nom' => 'Génie logiciel', 'code' => 'GL']);

        $this->getJson('/api/auth/registration-options')
            ->assertOk()
            ->assertJsonPath('filieres.0.nom', 'Informatique')
            ->assertJsonPath('specialites.0.nom', 'Génie logiciel');
    }

    public function test_department_head_can_manage_registration_catalogs(): void
    {
        $chef = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($chef)
            ->post(route('filieres.store'), ['nom' => 'Réseaux'])
            ->assertRedirect(route('filieres.index'));

        $this->actingAs($chef)
            ->post(route('specialites.store'), ['nom' => 'Systèmes embarqués'])
            ->assertRedirect(route('specialites.index'));

        $this->assertDatabaseHas('filieres', ['nom' => 'Réseaux']);
        $this->assertDatabaseHas('specialites', ['nom' => 'Systèmes embarqués']);
    }

    public function test_student_cannot_manage_registration_catalogs(): void
    {
        $student = User::factory()->create(['role' => User::ROLE_ETUDIANT]);

        $this->actingAs($student)->get(route('filieres.index'))->assertForbidden();
        $this->actingAs($student)->get(route('specialites.index'))->assertForbidden();
    }
}
