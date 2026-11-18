<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouveau message de contact</title>
</head>
<body style="margin: 0; padding: 32px 16px; background-color: #f3f6fb; color: #172033; font-family: Arial, sans-serif;">
    <table role="presentation" style="width: 100%; max-width: 640px; margin: 0 auto; border-collapse: collapse;">
        <tr>
            <td style="padding: 24px; background-color: #123568; color: #ffffff; border-radius: 12px 12px 0 0;">
                <p style="margin: 0 0 8px; font-size: 13px; letter-spacing: 1px; text-transform: uppercase;">GestionLoyer</p>
                <h1 style="margin: 0; font-size: 24px;">Nouveau message de contact</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 28px 24px; background-color: #ffffff; border-radius: 0 0 12px 12px;">
                <p style="margin-top: 0;">Un visiteur vous a envoyé un message depuis le formulaire de contact.</p>
                <p><strong>Nom :</strong> {{ $contact->name }}</p>
                <p><strong>Adresse e-mail :</strong> {{ $contact->email }}</p>
                <p style="margin-bottom: 8px;"><strong>Message :</strong></p>
                <div style="padding: 16px; background-color: #f3f6fb; border-left: 4px solid #0d6efd; line-height: 1.6;">
                    {!! nl2br(e($contact->message)) !!}
                </div>
                <p style="margin-bottom: 0; color: #64748b; font-size: 13px;">Vous pouvez répondre directement à cet e-mail pour contacter l’expéditeur.</p>
            </td>
        </tr>
    </table>
</body>
</html>
