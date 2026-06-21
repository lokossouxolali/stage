<?php

namespace Tests\Feature;

use App\Mail\OtpCodeMail;
use App\Models\RegistrationToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'user',
                    'token',
                    'token_type'
                ]);
    }

    public function test_user_can_register(): void
    {
        Mail::fake();

        $plainToken = 'ETUDIANT-TEST-TOKEN';
        RegistrationToken::create([
            'token_hash' => RegistrationToken::hashToken($plainToken),
            'role' => 'etudiant',
        ]);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'etudiant',
            'registration_token' => $plainToken,
        ]);

        $response->assertStatus(202)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'pending_registration_id'
                ]);

        $otp = null;
        Mail::assertSent(OtpCodeMail::class, function ($mail) use (&$otp) {
            $otp = $mail->code;
            return true;
        });

        $verifyResponse = $this->postJson('/api/auth/register/verify', [
            'pending_registration_id' => $response->json('pending_registration_id'),
            'otp_code' => $otp,
        ]);

        $verifyResponse->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'user'
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'statut_inscription' => 'en_attente',
            'est_actif' => false,
        ]);
    }

    public function test_pending_user_cannot_login(): void
    {
        User::factory()->create([
            'email' => 'pending@example.com',
            'password' => bcrypt('password'),
            'statut_inscription' => 'en_attente',
            'est_actif' => false,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'pending@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(403)
                ->assertJson([
                    'success' => false,
                ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200);
    }

    public function test_user_can_get_profile(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/user');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'user'
                ]);
    }
}
