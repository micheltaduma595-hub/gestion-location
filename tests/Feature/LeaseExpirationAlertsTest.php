<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\Lease;
use App\Models\User;
use App\Notifications\LeaseExpirationAlert;
use App\Notifications\LeaseExpirationSentNotice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LeaseExpirationAlertsTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_it_sends_the_three_day_reminder_once(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-10 09:00:00'));
        Notification::fake();
        $lease = $this->createLease('2026-11-13');

        $this->artisan('leases:send-expiration-alerts')->assertSuccessful();
        $this->artisan('leases:send-expiration-alerts')->assertSuccessful();

        Notification::assertSentTo($lease->client, LeaseExpirationAlert::class, fn ($notification) => ! $notification->isOverdue);
        Notification::assertSentToTimes($lease->client, LeaseExpirationAlert::class, 1);
        Notification::assertSentTo($lease->house->user, LeaseExpirationSentNotice::class, fn ($notification) =>
            $notification->recipient->is($lease->client) && ! $notification->isOverdue
        );
        $this->assertNotNull($lease->fresh()->expiration_reminder_sent_at);
    }

    public function test_it_sends_an_alert_three_days_after_expiration(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-10 09:00:00'));
        Notification::fake();
        $lease = $this->createLease('2026-11-07');

        $this->artisan('leases:send-expiration-alerts')->assertSuccessful();

        Notification::assertSentTo($lease->client, LeaseExpirationAlert::class, fn ($notification) => $notification->isOverdue);
        Notification::assertSentTo($lease->house->user, LeaseExpirationSentNotice::class, fn ($notification) =>
            $notification->recipient->is($lease->client) && $notification->isOverdue
        );
        $this->assertNotNull($lease->fresh()->expiration_overdue_sent_at);
    }

    private function createLease(string $endDate): Lease
    {
        $manager = User::factory()->create(['role' => 'gestionnaire']);
        $client = User::factory()->create(['role' => 'client']);
        $house = House::create([
            'user_id' => $manager->id,
            'title' => 'Maison de test',
            'description' => 'Logement de test.',
            'price' => 500,
            'address' => 'Adresse de test',
            'status' => 'louee',
        ]);

        return Lease::create([
            'house_id' => $house->id,
            'client_id' => $client->id,
            'start_date' => '2026-01-01',
            'end_date' => $endDate,
            'status' => 'actif',
        ]);
    }
}