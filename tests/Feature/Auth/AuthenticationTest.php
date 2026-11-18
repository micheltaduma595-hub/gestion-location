<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\AccountLockedAfterFailedLogins;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk()
            ->assertSee('Adresse Email')
            ->assertSee('Mot de passe')
            ->assertSee("Retour à l'accueil pour s'inscrire", false)
            ->assertSee('Se connecter')
            ->assertDontSee('Attention / Important')
            ->assertDontSee('Se souvenir de moi')
            ->assertSee('Mot de passe oublié ?');
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

    public function test_account_is_locked_and_admin_notified_after_three_failed_passwords(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create();

        foreach (range(1, 3) as $attempt) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])->assertSessionHasErrors(['email']);
        }

        $this->assertSame(3, $user->fresh()->failed_login_attempts);
        $this->assertNotNull($user->fresh()->login_locked_at);
        Notification::assertSentTo($admin, AccountLockedAfterFailedLogins::class);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors(['email']);
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
