<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_clients_are_sent_to_their_personal_dashboard_after_login(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $this->get(route('houses.index'))
            ->assertRedirect(route('login'));

        $response = $this->post('/login', [
            'email' => $client->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Mon espace client')
            ->assertSee($client->email)
            ->assertDontSee('Ajouter une maison');
    }

    public function test_managers_keep_their_intended_destination_after_login(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);

        $this->get(route('houses.index'))
            ->assertRedirect(route('login'));

        $response = $this->post('/login', [
            'email' => $manager->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('houses.index', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
