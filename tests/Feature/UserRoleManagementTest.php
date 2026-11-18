<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\PasswordResetLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_created_tenant_also_appears_in_the_tenant_directory(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->post(route('users.store'), [
                'name' => 'G DANIEL',
                'email' => 'daniel@example.com',
                'phone' => '+243831262631',
                'password' => 'password123',
                'role' => User::ROLE_TENANT,
            ])
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'G DANIEL',
            'email' => 'daniel@example.com',
            'role' => User::ROLE_TENANT,
        ]);
        $this->assertDatabaseHas('locataires', [
            'nom' => 'G DANIEL',
            'email' => 'daniel@example.com',
            'telephone' => '+243831262631',
        ]);
    }

    public function test_updating_an_existing_tenant_restores_its_missing_tenant_record(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $tenant = User::factory()->create([
            'name' => 'G DANIEL',
            'email' => 'daniel@example.com',
            'phone' => '+243831262631',
            'role' => User::ROLE_TENANT,
        ]);

        $this->actingAs($admin)
            ->put(route('users.update', $tenant), [
                'name' => $tenant->name,
                'email' => $tenant->email,
                'phone' => $tenant->phone,
                'role' => User::ROLE_TENANT,
            ])
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('locataires', [
            'nom' => 'G DANIEL',
            'email' => 'daniel@example.com',
            'telephone' => '+243831262631',
        ]);
    }

    public function test_admin_is_alerted_about_new_registrants_and_can_assign_them_a_role(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $newTenant = User::factory()->create([
            'name' => 'Nouveau Locataire',
            'email' => 'nouveau@example.com',
            'role' => User::ROLE_TENANT,
        ]);
        User::factory()->create([
            'name' => 'Ancien Client',
            'role' => User::ROLE_CLIENT,
        ]);
        User::factory()->create([
            'name' => 'Locataire déjà vérifié',
            'role' => User::ROLE_TENANT,
            'role_reviewed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee('1 nouveau(x) compte(s) à traiter')
            ->assertSee('Nouveau Locataire')
            ->assertSee('Nouveau — rôle à attribuer')
            ->assertSee('Ancien Client');

        $this->put(route('users.update', $newTenant), [
            'name' => $newTenant->name,
            'email' => $newTenant->email,
            'role' => User::ROLE_CLIENT,
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $newTenant->id,
            'role' => User::ROLE_CLIENT,
        ]);
        $this->assertNotNull($newTenant->fresh()->role_reviewed_at);

        $this->get(route('users.index'))
            ->assertOk()
            ->assertDontSee('nouveau(x) compte(s) à traiter')
            ->assertDontSee('Nouveau — rôle à attribuer');
    }

    public function test_admin_can_mark_a_tenant_role_as_reviewed_without_changing_it(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $newTenant = User::factory()->create([
            'role' => User::ROLE_TENANT,
            'role_reviewed_at' => null,
        ]);

        $this->actingAs($admin)
            ->put(route('users.update', $newTenant), [
                'name' => $newTenant->name,
                'email' => $newTenant->email,
                'role' => User::ROLE_TENANT,
            ])
            ->assertRedirect(route('users.index'));

        $this->assertNotNull($newTenant->fresh()->role_reviewed_at);
        $this->get(route('users.index'))
            ->assertOk()
            ->assertDontSee('nouveau(x) compte(s) à traiter')
            ->assertDontSee('Nouveau — rôle à attribuer');
    }

    public function test_only_administrators_can_manage_user_roles(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $this->actingAs($manager)
            ->get(route('users.index'))
            ->assertForbidden();

        $this->put(route('users.update', $client), [
            'name' => $client->name,
            'email' => $client->email,
            'role' => User::ROLE_MANAGER,
        ])->assertForbidden();
    }

    public function test_admin_cannot_remove_their_own_administrator_role(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->from(route('users.edit', $admin))
            ->put(route('users.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => User::ROLE_CLIENT,
            ])
            ->assertRedirect(route('users.edit', $admin))
            ->assertSessionHasErrors(['role']);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_admin_can_send_a_reset_link_to_a_locked_user(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create([
            'failed_login_attempts' => 3,
            'login_locked_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('users.send-password-reset', $user))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        Notification::assertSentTo($user, PasswordResetLink::class);

        $this->actingAs($admin)
            ->post(route('users.send-password-reset', $user))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error', 'Un lien de réinitialisation a déjà été demandé récemment. Attendez une minute avant de réessayer ou utilisez le lien déjà reçu par courriel.');

        Notification::assertSentToTimes($user, PasswordResetLink::class, 1);
    }

    public function test_smtp_failure_does_not_show_an_internal_server_error(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create([
            'failed_login_attempts' => 3,
            'login_locked_at' => now(),
        ]);
        $exception = new TransportException('SMTP server unavailable');

        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => $user->email])
            ->andThrow($exception);
        Log::shouldReceive('error')
            ->once()
            ->with('Password reset email delivery failed.', [
                'user_id' => $user->id,
                'exception' => TransportException::class,
                'message' => 'SMTP server unavailable',
            ]);

        $this->actingAs($admin)
            ->post(route('users.send-password-reset', $user))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas(
                'error',
                'Le courriel n’a pas pu être envoyé. Vérifiez la configuration SMTP, puis réessayez dans une minute.'
            );
    }
}
