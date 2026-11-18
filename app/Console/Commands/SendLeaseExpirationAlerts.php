<?php

namespace App\Console\Commands;

use App\Models\Lease;
use App\Notifications\LeaseExpirationAlert;
use App\Notifications\LeaseExpirationSentNotice;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendLeaseExpirationAlerts extends Command
{
    protected $signature = 'leases:send-expiration-alerts';

    protected $description = 'Envoie les alertes de fin de bail aux clients';

    public function handle(): int
    {
        $this->sendAlertsFor(Carbon::today()->addDays(3), 'expiration_reminder_sent_at', false);
        $this->sendAlertsFor(Carbon::today()->subDays(3), 'expiration_overdue_sent_at', true);

        return self::SUCCESS;
    }

    private function sendAlertsFor(Carbon $date, string $sentAtColumn, bool $isOverdue): void
    {
        Lease::query()
            ->with(['client', 'house.user'])
            ->where('status', 'actif')
            ->whereDate('end_date', $date->toDateString())
            ->whereNull($sentAtColumn)
            ->get()
            ->each(function (Lease $lease) use ($sentAtColumn, $isOverdue): void {
                if (! $lease->client) {
                    return;
                }

                $lease->client->notify(new LeaseExpirationAlert($lease, $isOverdue));

                if ($lease->house?->user) {
                    $lease->house->user->notify(new LeaseExpirationSentNotice(
                        $lease,
                        $lease->client,
                        $isOverdue,
                    ));
                }

                $lease->forceFill([$sentAtColumn => now()])->save();
            });
    }
}