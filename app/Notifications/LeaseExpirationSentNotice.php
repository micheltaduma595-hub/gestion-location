<?php

namespace App\Notifications;

use App\Models\Lease;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaseExpirationSentNotice extends Notification
{
    public function __construct(
        public readonly Lease $lease,
        public readonly User $recipient,
        public readonly bool $isOverdue,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $type = $this->isOverdue ? 'd’échéance dépassée' : 'de rappel d’échéance';
        $house = $this->lease->house?->title ?? 'Logement';

        return (new MailMessage)
            ->subject('Confirmation d’envoi d’une alerte de bail')
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line("Une alerte {$type} a été envoyée au client suivant :")
            ->line('Nom : '.$this->recipient->name)
            ->line('Adresse e-mail : '.$this->recipient->email)
            ->line('Logement : '.$house)
            ->line('Échéance du bail : '.$this->lease->end_date->format('d/m/Y').'.');
    }
}