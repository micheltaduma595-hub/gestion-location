<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\Locataire;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentFollowUpTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_account_missing_from_directory_is_restored_in_payment_form(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $tenant = User::factory()->create([
            'name' => 'Luc Bla',
            'email' => 'luc@example.com',
            'phone' => '+2439876666',
            'role' => User::ROLE_TENANT,
        ]);

        $this->actingAs($manager)
            ->get(route('payments.create'))
            ->assertOk()
            ->assertSee('Luc Bla')
            ->assertSee('+2439876666');

        $this->assertDatabaseHas('locataires', [
            'nom' => 'Luc Bla',
            'email' => $tenant->email,
            'telephone' => '+2439876666',
        ]);
    }

    public function test_manager_can_view_payment_totals_and_filter_the_ledger(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $tenant = Locataire::create([
            'nom' => 'Marie',
            'prenom' => 'Test',
            'email' => 'marie@example.com',
            'telephone' => '243000000000',
        ]);
        $house = House::create([
            'user_id' => $manager->id,
            'title' => 'Maison du lac',
            'description' => 'Logement de test.',
            'price' => 500,
            'address' => 'Goma',
            'status' => 'louee',
        ]);
        Payment::create([
            'locataire_id' => $tenant->id,
            'house_id' => $house->id,
            'user_id' => $manager->id,
            'amount' => 500,
            'month_covered' => 'Novembre 2026',
            'payment_method' => 'Espèces',
            'reference_reçu' => 'REC-20261105-1001',
        ]);
        Payment::create([
            'locataire_id' => $tenant->id,
            'house_id' => $house->id,
            'user_id' => $manager->id,
            'amount' => 250,
            'month_covered' => 'Octobre 2026',
            'payment_method' => 'Virement',
            'reference_reçu' => 'REC-20261001-1002',
            'created_at' => now()->subMonth(),
            'updated_at' => now()->subMonth(),
        ]);

        $this->actingAs($manager)
            ->get(route('payments.index'))
            ->assertOk()
            ->assertSee('Suivi des paiements & loyers', false)
            ->assertSee('Maison du lac')
            ->assertSee('Marie')
            ->assertSee('750,00 $')
            ->assertSee('Registre financier')
            ->assertSee('Suivi des paiements');

        $this->get(route('payments.index', ['payment_method' => 'Espèces']))
            ->assertOk()
            ->assertSee('REC-20261105-1001')
            ->assertDontSee('REC-20261001-1002')
            ->assertSee('500,00 $');

        $this->get(route('payments.index', ['q' => 'Maison du lac']))
            ->assertOk()
            ->assertSee('REC-20261105-1001')
            ->assertSee('REC-20261001-1002');
    }

    public function test_date_range_filters_must_be_valid(): void
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);

        $this->actingAs($manager)
            ->get(route('payments.index', [
                'date_from' => '2026-11-10',
                'date_to' => '2026-11-01',
            ]))
            ->assertSessionHasErrors(['date_to']);
    }
}
