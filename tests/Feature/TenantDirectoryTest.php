<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\Lease;
use App\Models\Locataire;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrators_and_managers_can_view_and_export_the_tenant_directory(): void
    {
        $tenant = Locataire::create([
            'nom' => 'Exemple',
            'postnom' => 'Dupont',
            'prenom' => 'Marie',
            'email' => 'marie@example.com',
            'telephone' => '+243 000 000 000',
            'profession' => 'Commerçante',
        ]);
        $tenant->forceFill([
            'created_at' => Carbon::parse('2025-03-14 12:00:00'),
            'updated_at' => Carbon::parse('2025-03-14 12:00:00'),
        ])->save();

        foreach ([User::ROLE_ADMIN, User::ROLE_MANAGER] as $role) {
            $viewer = User::factory()->create(['role' => $role]);

            $this->actingAs($viewer)
                ->get(route('locataires.index'))
                ->assertOk()
                ->assertSee('Exemple Dupont Marie')
                ->assertSee('href="'.route('locataires.index').'"', false)
                ->assertSee('Exporter en PDF')
                ->assertDontSee('marie@example.com')
                ->assertDontSee('14/03/2025');

            $this->get(route('locataires.show', $tenant))
                ->assertOk()
                ->assertSee('marie@example.com')
                ->assertSee('Commerçante')
                ->assertSee('14/03/2025')
                ->assertSee('Aucune maison');

            $response = $this->get(route('locataires.export.pdf'));

            $response->assertOk()
                ->assertHeader('content-type', 'application/pdf')
                ->assertHeader('content-disposition', 'inline; filename=liste-locataires.pdf');
            $this->assertStringStartsWith('%PDF-', $response->getContent());
            $this->assertDatabaseHas('locataires', ['id' => $tenant->id]);
        }
    }

    public function test_manager_sidebar_shows_dashboard_before_tenant_directory(): void
    {
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);

        $html = $this->actingAs($manager)
            ->get(route('locataires.index'))
            ->assertOk()
            ->getContent();

        $dashboardPosition = strpos($html, 'href="'.route('dashboard').'"');
        $tenantDirectoryPosition = strpos($html, 'href="'.route('locataires.index').'"');

        $this->assertNotFalse($dashboardPosition);
        $this->assertNotFalse($tenantDirectoryPosition);
        $this->assertLessThan($tenantDirectoryPosition, $dashboardPosition);
    }

    public function test_synced_tenants_show_their_user_account_registration_date(): void
    {
        $registrationDate = Carbon::parse('2024-06-15 12:00:00');
        User::factory()->create([
            'name' => 'Compte Exemple',
            'email' => 'compte@example.com',
            'role' => User::ROLE_CLIENT,
            'created_at' => $registrationDate,
            'updated_at' => $registrationDate,
        ]);
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);

        $this->actingAs($manager)
            ->get(route('locataires.index'))
            ->assertOk();

        $tenant = Locataire::where('email', 'compte@example.com')->firstOrFail();

        $this->get(route('locataires.show', $tenant))
            ->assertOk()
            ->assertSee('15/06/2024');
    }

    public function test_manager_can_edit_all_tenant_fields(): void
    {
        $tenant = Locataire::create([
            'nom' => 'AncienNom',
            'postnom' => 'AncienPostnom',
            'prenom' => 'AncienPrenom',
            'email' => 'ancien@example.com',
            'telephone' => '000000',
            'profession' => 'Ancienne profession',
        ]);
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);

        $this->actingAs($manager)
            ->get(route('locataires.edit', $tenant))
            ->assertOk()
            ->assertSee('name="nom"', false)
            ->assertSee('value="AncienNom"', false)
            ->assertSee('name="postnom"', false)
            ->assertSee('value="AncienPostnom"', false)
            ->assertSee('name="prenom"', false)
            ->assertSee('value="AncienPrenom"', false)
            ->assertSee('name="telephone"', false)
            ->assertSee('value="000000"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="profession"', false)
            ->assertSee('value="Ancienne profession"', false);

        $this->put(route('locataires.update', $tenant), [
            'nom' => 'NouveauNom',
            'postnom' => 'NouveauPostnom',
            'prenom' => 'NouveauPrenom',
            'email' => 'nouveau@example.com',
            'telephone' => '111111',
            'profession' => 'Nouvelle profession',
        ])->assertRedirect(route('locataires.index'));

        $this->assertDatabaseHas('locataires', [
            'id' => $tenant->id,
            'nom' => 'NouveauNom',
            'postnom' => 'NouveauPostnom',
            'prenom' => 'NouveauPrenom',
            'email' => 'nouveau@example.com',
            'telephone' => '111111',
            'profession' => 'Nouvelle profession',
        ]);
    }

    public function test_profession_update_persists_for_a_tenant_with_a_user_account(): void
    {
        $account = User::factory()->create([
            'email' => 'compte-profession@example.com',
            'role' => User::ROLE_CLIENT,
        ]);
        $tenant = Locataire::create([
            'nom' => 'Locataire',
            'prenom' => 'Compte',
            'email' => $account->email,
            'telephone' => '000000',
            'profession' => 'Ancienne profession',
        ]);
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);

        $this->actingAs($manager)
            ->put(route('locataires.update', $tenant), [
                'nom' => 'Locataire',
                'prenom' => 'Compte',
                'email' => $account->email,
                'telephone' => '000000',
                'profession' => 'Nouvelle profession',
            ])
            ->assertRedirect(route('locataires.index'));

        $this->get(route('locataires.index'))->assertOk();

        $this->assertDatabaseHas('locataires', [
            'id' => $tenant->id,
            'profession' => 'Nouvelle profession',
        ]);
    }

    public function test_active_lease_house_is_shown_as_attributed_to_the_synced_tenant(): void
    {
        $client = User::factory()->create([
            'name' => 'Locataire avec bail',
            'email' => 'bail@example.com',
            'role' => User::ROLE_CLIENT,
        ]);
        $manager = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $house = House::create([
            'user_id' => $manager->id,
            'title' => 'Maison louée par bail',
            'description' => 'Maison de test',
            'price' => 500,
            'address' => 'Adresse de test',
            'status' => 'louee',
        ]);
        Lease::create([
            'house_id' => $house->id,
            'client_id' => $client->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'status' => 'actif',
        ]);

        $this->actingAs($manager)
            ->get(route('locataires.index'))
            ->assertOk()
            ->assertSee('Locataire avec bail')
            ->assertDontSee('Maison louée par bail');

        $tenant = Locataire::where('email', $client->email)->firstOrFail();

        $this->get(route('locataires.show', $tenant))
            ->assertOk()
            ->assertSee('Maison louée par bail')
            ->assertDontSee('Aucune maison');
    }

    public function test_client_roles_cannot_view_or_export_the_tenant_directory(): void
    {
        foreach ([User::ROLE_CLIENT, User::ROLE_TENANT] as $role) {
            $client = User::factory()->create(['role' => $role]);

            $this->actingAs($client)
                ->get(route('locataires.index'))
                ->assertForbidden();

            $this->get(route('locataires.show', Locataire::create([
                'nom' => 'Exemple',
                'prenom' => 'Locataire',
                'telephone' => '000000',
                'profession' => 'Test',
            ])))
                ->assertForbidden();

            $this->get(route('locataires.export.pdf'))
                ->assertForbidden();
        }
    }
}
