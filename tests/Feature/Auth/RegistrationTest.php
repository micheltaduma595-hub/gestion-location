<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200)
            ->assertSee('name="nom"', false)
            ->assertSee('name="postnom"', false)
            ->assertSee('name="prenom"', false)
            ->assertSee('name="profession"', false)
            ->assertSee('href="'.route('home').'"', false)
            ->assertSee('Retour');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'nom' => 'Test',
            'postnom' => 'Postnom',
            'prenom' => 'User',
            'email' => 'test@example.com',
            'telephone' => '+243000000000',
            'profession' => 'Enseignant',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'test@example.com')->firstOrFail();
        $this->assertSame('Test Postnom User', $user->name);
        $this->assertSame(User::ROLE_TENANT, $user->role);
        $this->assertDatabaseHas('locataires', [
            'email' => $user->email,
            'nom' => 'Test',
            'postnom' => 'Postnom',
            'prenom' => 'User',
            'telephone' => '+243000000000',
            'profession' => 'Enseignant',
        ]);
    }
}
