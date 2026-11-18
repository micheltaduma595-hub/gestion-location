<?php

namespace Tests\Feature;

use App\Support\SiteLogo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteLogoTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_manager_can_open_logo_settings_without_a_database_settings_table(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);

        $response = $this->actingAs($manager)->get(route('site-logo.edit'));

        $response->assertOk()
            ->assertSee('Choisir une image depuis votre ordinateur');
    }

    public function test_client_dashboard_displays_the_configured_site_logo(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        Storage::disk('public')->put('logos/system-logo.png', 'image');
        SiteLogo::savePath('logos/system-logo.png');
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        $this->actingAs($client)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('id="clientSidebar"', false)
            ->assertSee('Navigation principale')
            ->assertSee('Paramètres de l’application')
            ->assertSee('Réclamations / Maintenance')
            ->assertSee('alt="Logo du site"', false)
            ->assertSee(asset('storage/logos/system-logo.png'), false);
    }
}
