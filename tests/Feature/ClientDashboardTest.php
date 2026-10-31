<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\Locataire;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_see_house_assigned_by_manager_and_own_payment_report(): void
    {
        $client = User::factory()->create([
            'name' => 'Luc Bla',
            'email' => 'lucbla@example.com',
            'role' => 'client',
        ]);
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $locataire = Locataire::create([
            'nom' => 'Luc',
            'prenom' => 'Bla',
            'email' => $client->email,
            'telephone' => '243000000000',
            'profession' => 'Commerçant',
        ]);
        $house = House::create([
            'user_id' => $manager->id,
            'title' => 'LUX1',
            'description' => 'Maison attribuée par le gestionnaire.',
            'price' => 454,
            'address' => 'Kambali',
            'status' => 'disponible',
        ]);
        $this->actingAs($manager)
            ->post(route('locataires.attribuer', $locataire), ['house_id' => $house->id])
            ->assertRedirect(route('locataires.index'));

        $this->assertDatabaseHas('houses', [
            'id' => $house->id,
            'locataire_id' => $locataire->id,
            'status' => 'louee',
        ]);
        Payment::create([
            'locataire_id' => $locataire->id,
            'house_id' => $house->id,
            'user_id' => $manager->id,
            'amount' => 454,
            'month_covered' => 'Octobre 2026',
            'payment_method' => 'Espèces',
            'reference_reçu' => 'REC-20261029-1234',
        ]);

        $this->actingAs($client)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Mes locations')
            ->assertSee('LUX1')
            ->assertSee('Attribué par votre gestionnaire')
            ->assertSee('Louée')
            ->assertSee('Mon rapport de paiements')
            ->assertSee('Octobre 2026')
            ->assertSee('REC-20261029-1234')
            ->assertSee('454.00 $');
    }
}
