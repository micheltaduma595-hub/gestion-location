<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_open_the_application_settings_hub(): void
    {
        $manager = User::factory()->create([
            'name' => 'Gestionnaire Test',
            'email' => 'gestionnaire@example.com',
            'role' => 'gestionnaire',
        ]);

        $this->actingAs($manager)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertSee('Paramètres de l’application')
            ->assertSee('Logo du site')
            ->assertSee(route('site-logo.edit'))
            ->assertSee('Images des pages')
            ->assertSee(route('site-page-images.edit'))
            ->assertSee('Gestionnaire Test')
            ->assertSee(route('profile.edit'));
    }

    public function test_admin_and_client_can_open_only_the_settings_available_to_their_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($admin)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertSee('Paramètres de l’application')
            ->assertSee('Utilisateurs');

        preg_match('/<nav class="app-sidebar.*?<\/nav>/s', $response->getContent(), $sidebar);
        $this->assertNotEmpty($sidebar);
        $this->assertStringContainsString('Utilisateurs', $sidebar[0]);
        $this->assertStringContainsString('Paramètres de l’application', $sidebar[0]);
        $this->assertStringNotContainsString('Rapports et statistiques', $sidebar[0]);
        $this->assertStringNotContainsString('Réclamations / Maintenance', $sidebar[0]);
        $this->assertStringNotContainsString('Contrats de bail', $sidebar[0]);

        $clientResponse = $this->actingAs($client)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertSee('Paramètres de l’application')
            ->assertSee('Mon compte')
            ->assertSee(route('profile.edit'))
            ->assertDontSee('Rapports et statistiques');

        preg_match('/<nav class="app-sidebar.*?<\/nav>/s', $clientResponse->getContent(), $clientSidebar);
        $this->assertNotEmpty($clientSidebar);
        $this->assertStringContainsString('Paramètres de l’application', $clientSidebar[0]);
        $this->assertStringNotContainsString('Utilisateurs', $clientSidebar[0]);
        $this->assertStringNotContainsString('Rapports et statistiques', $clientSidebar[0]);
        $this->assertStringNotContainsString(route('site-logo.edit'), $clientSidebar[0]);
        $this->assertStringNotContainsString(route('site-page-images.edit'), $clientSidebar[0]);
    }
}
