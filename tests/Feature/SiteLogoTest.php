<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
