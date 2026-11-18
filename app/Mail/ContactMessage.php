<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactMessage extends Mailable
{
    public function __construct(
        public readonly Contact $contact,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau message depuis le formulaire de contact',
            replyTo: [
                new Address($this->contact->email, $this->contact->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message',
            text: 'emails.contact-message-text',
        );
    }
}
