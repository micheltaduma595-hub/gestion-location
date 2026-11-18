<?php

namespace App\Notifications;

use App\Models\Lease;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaseExpirationAlert extends Notification
{
    public function __construct(
        public readonly Lease $lease,
        public readonly bool $isOverdue,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $house = $this->lease->house?->title ?? 'votre logement';
        $endDate = $this->lease->end_date->format('d/m/Y');

        return (new MailMessage)
            ->subject($this->isOverdue ? 'Votre bail est arrivé à échéance' : 'Votre bail arrive bientôt à échéance')
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line($this->isOverdue
                ? "Votre bail pour {$house} est arrivé à échéance le {$endDate}. Merci de contacter votre gestionnaire."
                : "Votre bail pour {$house} arrive à échéance le {$endDate}, dans trois jours.")
            ->line('Pour toute question, veuillez contacter votre gestionnaire.');
    }
}