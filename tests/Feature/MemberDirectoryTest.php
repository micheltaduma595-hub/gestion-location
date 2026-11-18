<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_and_manager_can_view_member_photos_and_contact_details(): void
    {
        $member = User::factory()->create([
            'name' => 'Membre Exemple',
            'email' => 'membre@example.com',
            'phone' => '+243 000 000 000',
            'role' => User::ROLE_CLIENT,
            'profile_photo_path' => 'profile-photos/membre.png',
        ]);

        foreach ([User::ROLE_ADMIN, User::ROLE_MANAGER] as $role) {
            $viewer = User::factory()->create(['role' => $role]);

            $this->actingAs($viewer)
                ->get(route('members.index'))
                ->assertOk()
                ->assertSee('Annuaire des membres')
                ->assertSee('Membre Exemple')
                ->assertSee('membre@example.com')
                ->assertSee('+243 000 000 000')
                ->assertSee('alt="Photo de Membre Exemple"', false)
                ->assertSee(asset('storage/profile-photos/membre.png'), false);
        }
    }

    public function test_client_roles_cannot_view_the_member_directory(): void
    {
        foreach ([User::ROLE_CLIENT, User::ROLE_TENANT] as $role) {
            $client = User::factory()->create(['role' => $role]);

            $this->actingAs($client)
                ->get(route('members.index'))
                ->assertForbidden();
        }
    }

    public function test_manager_menu_links_to_member_directory_and_admin_menu_stays_limited(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);

        $this->actingAs($manager)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('href="'.route('members.index').'"', false);

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee('Annuaire des membres')
            ->assertSee('href="'.route('members.index').'"', false);

        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $this->actingAs($client)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('href="'.route('members.index').'"', false)
            ->assertDontSee('Membres');
    }
}
