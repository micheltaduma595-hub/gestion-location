<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk()
            ->assertSee('Coordonnées et photo')
            ->assertSee('Enregistrer mes coordonnées')
            ->assertSee('Modifier le mot de passe')
            ->assertSee('Mot de passe actuel')
            ->assertSee('Nouveau mot de passe')
            ->assertSee('Supprimer mon compte')
            ->assertSee('form-control');
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '243000000000',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertSame('243000000000', $user->phone);
        $this->assertNull($user->email_verified_at);
    }

    public function test_profile_photo_can_be_uploaded_and_replaced(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo' => UploadedFile::fake()->image('profile.png'),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $firstPhoto = $user->fresh()->profile_photo_path;
        $this->assertNotNull($firstPhoto);
        Storage::disk('public')->assertExists($firstPhoto);

        $this->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo' => UploadedFile::fake()->image('new-profile.png'),
        ])->assertSessionHasNoErrors();

        $secondPhoto = $user->fresh()->profile_photo_path;
        $this->assertNotSame($firstPhoto, $secondPhoto);
        Storage::disk('public')->assertMissing($firstPhoto);
        Storage::disk('public')->assertExists($secondPhoto);
    }

    public function test_non_image_profile_photo_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/profile')
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect('/profile')
            ->assertSessionHasErrors(['profile_photo']);

        $this->assertNull($user->fresh()->profile_photo_path);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
