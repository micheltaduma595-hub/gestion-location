<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetLink extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = rtrim((string) config('app.url'), '/').route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false);

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe')
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Vous recevez ce message parce qu’une demande de réinitialisation du mot de passe de votre compte a été faite.')
            ->action('Réinitialiser mon mot de passe', $url)
            ->line('Ce lien expirera dans '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' minutes.')
            ->line('Si vous n’êtes pas à l’origine de cette demande, ignorez ce message. Votre mot de passe ne sera pas modifié.')
            ->salutation('Cordialement, l’équipe '.config('app.name'));
    }
}
