<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\PasswordResetLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertOk()
            ->assertSee('Adresse courriel')
            ->assertSee('Envoyer le lien')
            ->assertSee('Retour à la connexion')
            ->assertDontSee('Forgot your password?');
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, PasswordResetLink::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, PasswordResetLink::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);

            $response->assertOk()
                ->assertSee('Nouveau mot de passe')
                ->assertSee('Confirmer le nouveau mot de passe')
                ->assertSee('Enregistrer le nouveau mot de passe')
                ->assertDontSee('Reset Password');

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, PasswordResetLink::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            return true;
        });
    }

    public function test_successful_password_reset_unlocks_the_account(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'failed_login_attempts' => 3,
            'login_locked_at' => now(),
        ]);

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, PasswordResetLink::class, function ($notification) use ($user) {
            $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])->assertRedirect(route('login'));

            return true;
        });

        $this->assertNull($user->fresh()->login_locked_at);
        $this->assertSame(0, $user->fresh()->failed_login_attempts);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'new-secure-password',
        ])->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_reset_email_is_in_french_and_uses_the_configured_application_url(): void
    {
        Notification::fake();
        config(['app.url' => 'https://location.example']);

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, PasswordResetLink::class, function ($notification) use ($user) {
            $message = $notification->toMail($user);

            $this->assertSame('Réinitialisation de votre mot de passe', $message->subject);
            $this->assertSame('Réinitialiser mon mot de passe', $message->actionText);
            $this->assertStringStartsWith(
                'https://location.example/reset-password/'.$notification->token,
                $message->actionUrl
            );
            $this->assertSame('Bonjour '.$user->name.',', $message->greeting);
            $messageLines = implode(' ', array_merge($message->introLines, $message->outroLines));
            $this->assertStringContainsString('60 minutes', $messageLines);
            $this->assertStringContainsString('Votre mot de passe ne sera pas modifié.', $messageLines);

            return true;
        });
    }
}
