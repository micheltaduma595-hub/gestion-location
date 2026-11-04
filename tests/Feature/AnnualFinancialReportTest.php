<?php

namespace Tests\Feature;

use App\Models\FinancialActivity;
use App\Models\House;
use App\Models\Locataire;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnualFinancialReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_sees_yearly_rent_income_manual_activities_and_totals(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $tenant = Locataire::create([
            'nom' => 'Jean',
            'prenom' => 'Client',
            'email' => 'jean@example.com',
            'telephone' => '243000000000',
            'profession' => 'Commerçant',
        ]);
        $house = House::create([
            'user_id' => $manager->id,
            'title' => 'Maison A',
            'description' => 'Maison de test',
            'price' => 250,
            'address' => 'Goma',
            'status' => 'louee',
        ]);
        Payment::create([
            'locataire_id' => $tenant->id,
            'house_id' => $house->id,
            'user_id' => $manager->id,
            'amount' => 250,
            'month_covered' => 'Mai 2025',
            'payment_method' => 'Espèces',
            'reference_reçu' => 'REC-20250505-1234',
            'created_at' => '2025-05-05 10:00:00',
            'updated_at' => '2025-05-05 10:00:00',
        ]);
        FinancialActivity::create([
            'user_id' => $manager->id,
            'type' => 'income',
            'description' => 'Vente exceptionnelle',
            'amount' => 50,
            'occurred_on' => '2025-06-01',
        ]);
        FinancialActivity::create([
            'user_id' => $manager->id,
            'type' => 'expense',
            'description' => 'Réparation',
            'amount' => 30,
            'occurred_on' => '2025-06-02',
        ]);
        FinancialActivity::create([
            'user_id' => $manager->id,
            'type' => 'expense',
            'description' => 'Dépense hors année',
            'amount' => 500,
            'occurred_on' => '2024-06-02',
        ]);

        $this->actingAs($manager)
            ->get(route('annual-report.index', ['year' => 2025]))
            ->assertOk()
            ->assertSee('Loyer - Maison A')
            ->assertSee('Vente exceptionnelle')
            ->assertSee('Réparation')
            ->assertSee('300,00 $')
            ->assertSee('30,00 $')
            ->assertSee('270,00 $')
            ->assertDontSee('Dépense hors année');
    }

    public function test_manager_can_add_income_and_expenses_to_the_selected_year(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);

        $this->actingAs($manager)
            ->post(route('annual-report.store'), [
                'year' => 2025,
                'type' => 'income',
                'description' => 'Autre recette',
                'amount' => 120,
                'occurred_on' => '2025-04-12',
                'payment_method' => 'Virement',
            ])
            ->assertRedirect(route('annual-report.index', ['year' => 2025]));

        $this->post(route('annual-report.store'), [
            'year' => 2025,
            'type' => 'expense',
            'description' => 'Entretien',
            'amount' => 20,
            'occurred_on' => '2025-04-13',
        ])->assertRedirect(route('annual-report.index', ['year' => 2025]));

        $this->assertDatabaseHas('financial_activities', [
            'user_id' => $manager->id,
            'type' => 'income',
            'description' => 'Autre recette',
            'amount' => 120,
            'occurred_on' => '2025-04-12',
        ]);
        $this->assertDatabaseHas('financial_activities', [
            'user_id' => $manager->id,
            'type' => 'expense',
            'description' => 'Entretien',
            'amount' => 20,
            'occurred_on' => '2025-04-13',
        ]);
    }

    public function test_only_managers_can_access_the_annual_report(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $this->actingAs($client)
            ->get(route('annual-report.index'))
            ->assertForbidden();

        $this->post(route('annual-report.store'), [
            'type' => 'expense',
            'description' => 'Dépense non autorisée',
            'amount' => 10,
            'occurred_on' => now()->toDateString(),
        ])->assertForbidden();
    }
}
