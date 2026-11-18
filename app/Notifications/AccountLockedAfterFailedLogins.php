<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountLockedAfterFailedLogins extends Notification
{
    public function __construct(
        public readonly User $lockedUser,
        public readonly ?string $ipAddress,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Compte bloqué après plusieurs tentatives de connexion')
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Le compte suivant a été bloqué après trois mots de passe incorrects :')
            ->line('Nom : '.$this->lockedUser->name)
            ->line('Adresse e-mail : '.$this->lockedUser->email)
            ->line('Adresse IP : '.($this->ipAddress ?? 'Non disponible'))
            ->line('Le compte restera bloqué jusqu’à la réinitialisation du mot de passe par son propriétaire.');
    }
}