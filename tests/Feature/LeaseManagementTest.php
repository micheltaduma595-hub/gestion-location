<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\Lease;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_contracts_and_create_a_lease(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $client = User::factory()->create(['role' => 'client']);
        $house = $this->createHouse($manager);

        $this->actingAs($manager)
            ->get(route('leases.index'))
            ->assertOk()
            ->assertSee('Contrats de bail');

        $this->post(route('leases.store'), [
            'house_id' => $house->id,
            'client_id' => $client->id,
            'start_date' => '2026-11-01',
            'end_date' => '2027-10-31',
            'status' => 'actif',
        ])->assertRedirect(route('leases.index'));

        $this->assertDatabaseHas('leases', [
            'house_id' => $house->id,
            'client_id' => $client->id,
            'status' => 'actif',
        ]);
        $this->assertDatabaseHas('houses', [
            'id' => $house->id,
            'status' => 'louee',
        ]);

        $this->get(route('leases.index'))
            ->assertOk()
            ->assertSee($house->title)
            ->assertSee($client->name);

        $lease = Lease::firstOrFail();
        $this->get(route('leases.edit', $lease))
            ->assertOk()
            ->assertSee('Modifier le contrat de bail')
            ->assertSee('value="'.$house->id.'" selected', false);
    }

    public function test_a_house_cannot_have_two_active_leases(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $firstClient = User::factory()->create(['role' => 'client']);
        $secondClient = User::factory()->create(['role' => 'client']);
        $house = $this->createHouse($manager);
        Lease::create([
            'house_id' => $house->id,
            'client_id' => $firstClient->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'status' => 'actif',
        ]);

        $this->actingAs($manager)
            ->from(route('leases.create'))
            ->post(route('leases.store'), [
                'house_id' => $house->id,
                'client_id' => $secondClient->id,
                'start_date' => '2026-11-01',
                'end_date' => '2027-10-31',
                'status' => 'actif',
            ])
            ->assertRedirect(route('leases.create'))
            ->assertSessionHasErrors(['house_id']);

        $this->assertDatabaseCount('leases', 1);
    }

    public function test_lease_end_date_cannot_be_before_start_date(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $client = User::factory()->create(['role' => 'client']);
        $house = $this->createHouse($manager);

        $this->actingAs($manager)
            ->from(route('leases.create'))
            ->post(route('leases.store'), [
                'house_id' => $house->id,
                'client_id' => $client->id,
                'start_date' => '2026-11-01',
                'end_date' => '2026-10-31',
                'status' => 'actif',
            ])
            ->assertRedirect(route('leases.create'))
            ->assertSessionHasErrors(['end_date']);

        $this->assertDatabaseCount('leases', 0);
    }

    public function test_house_status_returns_to_available_when_active_lease_ends_or_is_deleted(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $client = User::factory()->create(['role' => 'client']);
        $house = $this->createHouse($manager);
        $lease = Lease::create([
            'house_id' => $house->id,
            'client_id' => $client->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'status' => 'actif',
        ]);
        $house->syncOccupancyStatus();

        $this->actingAs($manager)
            ->put(route('leases.update', $lease), [
                'house_id' => $house->id,
                'client_id' => $client->id,
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'status' => 'termine',
            ])
            ->assertRedirect(route('leases.index'));

        $this->assertDatabaseHas('houses', ['id' => $house->id, 'status' => 'disponible']);

        $this->actingAs($manager)
            ->post(route('leases.store'), [
                'house_id' => $house->id,
                'client_id' => $client->id,
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'status' => 'actif',
            ])
            ->assertRedirect(route('leases.index'));

        $activeLease = Lease::where('house_id', $house->id)->where('status', 'actif')->firstOrFail();
        $this->assertDatabaseHas('houses', ['id' => $house->id, 'status' => 'louee']);

        $this->delete(route('leases.destroy', $activeLease))
            ->assertRedirect(route('leases.index'));

        $this->assertDatabaseHas('houses', ['id' => $house->id, 'status' => 'disponible']);
    }

    private function createHouse(User $manager): House
    {
        return House::create([
            'user_id' => $manager->id,
            'title' => 'Maison de test',
            'description' => 'Logement de test.',
            'price' => 500,
            'address' => 'Adresse de test',
            'status' => 'disponible',
        ]);
    }
}
