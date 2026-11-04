<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SitePageImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_image_replaces_a_service_card_image(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $manager = User::factory()->create(['role' => 'gestionnaire']);

        $this->actingAs($manager)
            ->post(route('site-page-images.update'), [
                'images' => [
                    'services.publication' => UploadedFile::fake()->image('maison.png'),
                ],
            ])
            ->assertRedirect(route('site-page-images.edit'))
            ->assertSessionHas('success');

        $settings = json_decode(Storage::disk('local')->get('site-page-images.json'), true);
        $this->assertSame('site-pages/maison.png', $settings['services']['publication']);
        Storage::disk('public')->assertExists('site-pages/maison.png');

        $this->get(route('services'))
            ->assertOk()
            ->assertSee('storage/site-pages/maison.png')
            ->assertDontSee('photo-1560518883-ce09059eeffa');
    }

    public function test_only_administrators_and_managers_can_manage_page_images(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $this->actingAs($client)
            ->get(route('site-page-images.edit'))
            ->assertForbidden();

        $this->actingAs($client)
            ->post(route('site-page-images.update'), [
                'images' => [
                    'services.publication' => UploadedFile::fake()->image('maison.png'),
                ],
            ])
            ->assertForbidden();
    }

    public function test_public_pages_keep_the_default_images_when_no_custom_image_is_set(): void
    {
        Storage::fake('local');

        $this->get(route('services'))
            ->assertOk()
            ->assertSee('photo-1560518883-ce09059eeffa');

        $this->get(route('apropos'))
            ->assertOk()
            ->assertSee('photo-1522071820081-009f0129c71c');
    }
}
