<?php

namespace Tests\Feature;

use App\Models\FinancialActivity;
use App\Models\House;
use App\Models\Locataire;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_sees_yearly_financial_and_operational_statistics(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $client = User::factory()->create(['role' => 'client']);
        $tenant = Locataire::create([
            'nom' => 'Jean',
            'prenom' => 'Test',
            'email' => 'jean@example.com',
            'telephone' => '243000000000',
        ]);
        $house = House::create([
            'user_id' => $manager->id,
            'title' => 'Maison Statistique',
            'description' => 'Logement de test.',
            'price' => 500,
            'address' => 'Goma',
            'status' => 'louee',
        ]);
        House::create([
            'user_id' => $manager->id,
            'title' => 'Maison Disponible',
            'description' => 'Logement libre.',
            'price' => 300,
            'address' => 'Goma',
            'status' => 'disponible',
        ]);
        $payment = Payment::create([
            'locataire_id' => $tenant->id,
            'house_id' => $house->id,
            'user_id' => $manager->id,
            'amount' => 500,
            'month_covered' => 'Mars 2025',
            'payment_method' => 'Espèces',
            'reference_reçu' => 'REC-20250305-5678',
        ]);
        $payment->forceFill([
            'created_at' => '2025-03-05 10:00:00',
            'updated_at' => '2025-03-05 10:00:00',
        ])->save();
        FinancialActivity::create([
            'user_id' => $manager->id,
            'type' => 'income',
            'description' => 'Autre recette',
            'amount' => 100,
            'occurred_on' => '2025-03-06',
        ]);
        FinancialActivity::create([
            'user_id' => $manager->id,
            'type' => 'expense',
            'description' => 'Réparation',
            'amount' => 50,
            'occurred_on' => '2025-03-07',
        ]);
        $reservation = Reservation::create([
            'house_id' => $house->id,
            'client_id' => $client->id,
            'status' => 'validee',
        ]);
        $reservation->forceFill([
            'created_at' => '2025-03-08 10:00:00',
            'updated_at' => '2025-03-08 10:00:00',
        ])->save();
        $maintenanceRequest = MaintenanceRequest::create([
            'user_id' => $client->id,
            'house_id' => $house->id,
            'category' => 'Plomberie',
            'subject' => 'Fuite',
            'description' => 'Fuite à réparer.',
            'status' => 'en_cours',
        ]);
        $maintenanceRequest->forceFill([
            'created_at' => '2025-03-09 10:00:00',
            'updated_at' => '2025-03-09 10:00:00',
        ])->save();

        $this->actingAs($manager)
            ->get(route('reports.index', ['year' => 2025]))
            ->assertOk()
            ->assertSee('Rapports et statistiques')
            ->assertSee('600,00 $')
            ->assertSee('50,00 $')
            ->assertSee('550,00 $')
            ->assertSee('50,0 %')
            ->assertSee('Maison Statistique')
            ->assertSee('Espèces')
            ->assertSee('Validées : 1')
            ->assertSee('En cours : 1');
    }

    public function test_only_managers_and_admins_can_open_reports(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($client)
            ->get(route('reports.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Rapports et statistiques');
    }
}
