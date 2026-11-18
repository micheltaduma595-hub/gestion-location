<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_submit_a_request_for_an_assigned_house_and_view_only_their_requests(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $house = $this->createHouse($manager, 'Logement attribué');
        $otherHouse = $this->createHouse($manager, 'Logement non attribué');
        Lease::create([
            'house_id' => $house->id,
            'client_id' => $client->id,
            'start_date' => '2026-01-01',
            'end_date' => '2027-01-01',
            'status' => 'actif',
        ]);
        $otherRequest = MaintenanceRequest::create([
            'user_id' => $otherClient->id,
            'category' => 'Plomberie',
            'subject' => 'Demande privée',
            'description' => 'Cette demande doit rester privée.',
        ]);

        $this->actingAs($client)
            ->get(route('maintenance-requests.create'))
            ->assertOk()
            ->assertSee($house->title)
            ->assertDontSee($otherHouse->title);

        $this->post(route('maintenance-requests.store'), [
            'house_id' => $house->id,
            'category' => 'Plomberie',
            'subject' => 'Fuite sous évier',
            'description' => 'Une fuite est apparue sous l’évier de la cuisine.',
        ])->assertRedirect(route('maintenance-requests.index'));

        $this->assertDatabaseHas('maintenance_requests', [
            'user_id' => $client->id,
            'house_id' => $house->id,
            'subject' => 'Fuite sous évier',
            'status' => 'ouverte',
        ]);

        $this->get(route('maintenance-requests.index'))
            ->assertOk()
            ->assertSee('Fuite sous évier')
            ->assertDontSee($otherRequest->subject)
            ->assertSee('Tableau de bord')
            ->assertSee('href="'.route('dashboard').'"', false);

        $this->post(route('maintenance-requests.store'), [
            'house_id' => $otherHouse->id,
            'category' => 'Autre',
            'subject' => 'Logement d’autrui',
            'description' => 'Tentative de déclarer un problème sur un autre logement.',
        ])->assertForbidden();
    }

    public function test_manager_can_update_request_status_and_response(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $client = User::factory()->create(['role' => 'client']);
        $maintenanceRequest = MaintenanceRequest::create([
            'user_id' => $client->id,
            'category' => 'Électricité',
            'subject' => 'Panne électrique',
            'description' => 'Une pièce n’a plus de courant.',
        ]);

        $this->actingAs($manager)
            ->get(route('maintenance-requests.index'))
            ->assertOk()
            ->assertSee('Panne électrique')
            ->assertSee($client->name);

        $this->patch(route('maintenance-requests.update', $maintenanceRequest), [
            'status' => 'en_cours',
            'manager_response' => 'Un technicien interviendra demain.',
        ])->assertRedirect(route('maintenance-requests.index'));

        $this->assertDatabaseHas('maintenance_requests', [
            'id' => $maintenanceRequest->id,
            'status' => 'en_cours',
            'manager_response' => 'Un technicien interviendra demain.',
        ]);
    }

    public function test_clients_cannot_update_requests_and_invalid_status_is_rejected(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $client = User::factory()->create(['role' => 'client']);
        $maintenanceRequest = MaintenanceRequest::create([
            'user_id' => $client->id,
            'category' => 'Autre',
            'subject' => 'Problème',
            'description' => 'Description du problème.',
        ]);

        $this->actingAs($client)
            ->patch(route('maintenance-requests.update', $maintenanceRequest), [
                'status' => 'resolue',
            ])
            ->assertForbidden();

        $this->actingAs($manager)
            ->from(route('maintenance-requests.index'))
            ->patch(route('maintenance-requests.update', $maintenanceRequest), [
                'status' => 'inconnu',
            ])
            ->assertSessionHasErrors(['status']);
    }

    public function test_manager_can_delete_a_resolved_request_but_not_an_unresolved_one(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $client = User::factory()->create(['role' => 'client']);
        $resolvedRequest = MaintenanceRequest::create([
            'user_id' => $client->id,
            'category' => 'Électricité',
            'subject' => 'Réparation terminée',
            'description' => 'La réparation a été effectuée.',
            'status' => 'resolue',
        ]);
        $openRequest = MaintenanceRequest::create([
            'user_id' => $client->id,
            'category' => 'Plomberie',
            'subject' => 'Demande à traiter',
            'description' => 'La fuite est toujours présente.',
            'status' => 'ouverte',
        ]);

        $this->actingAs($manager)
            ->delete(route('maintenance-requests.destroy', $openRequest))
            ->assertUnprocessable();

        $this->actingAs($manager)
            ->delete(route('maintenance-requests.destroy', $resolvedRequest))
            ->assertRedirect(route('maintenance-requests.index'));

        $this->assertDatabaseHas('maintenance_requests', ['id' => $openRequest->id]);
        $this->assertDatabaseMissing('maintenance_requests', ['id' => $resolvedRequest->id]);
    }

    public function test_clients_cannot_delete_maintenance_requests(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $maintenanceRequest = MaintenanceRequest::create([
            'user_id' => $client->id,
            'category' => 'Autre',
            'subject' => 'Ma réclamation',
            'description' => 'Description du problème.',
            'status' => 'resolue',
        ]);

        $this->actingAs($client)
            ->delete(route('maintenance-requests.destroy', $maintenanceRequest))
            ->assertForbidden();

        $this->assertDatabaseHas('maintenance_requests', ['id' => $maintenanceRequest->id]);
    }

    private function createHouse(User $manager, string $title): House
    {
        return House::create([
            'user_id' => $manager->id,
            'title' => $title,
            'description' => 'Logement de test.',
            'price' => 500,
            'address' => 'Goma',
            'status' => 'louee',
        ]);
    }
}
