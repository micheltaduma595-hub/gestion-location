<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class ContactEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_stores_the_message_and_emails_the_configured_inbox(): void
    {
        config(['mail.contact_address' => 'contact@example.com']);
        Mail::fake();

        $this->post(route('contact.send'), [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'message' => 'Bonjour, je souhaite des informations.',
        ])->assertRedirect(route('contact'))
            ->assertSessionHas('success');

        $contact = Contact::query()->firstOrFail();
        $this->assertSame('Jean Dupont', $contact->name);
        $this->assertSame('jean@example.com', $contact->email);

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) use ($contact): bool {
            return $mail->hasTo('contact@example.com')
                && $mail->envelope()->replyTo[0]->address === $contact->email
                && str_contains($mail->render(), 'Bonjour, je souhaite des informations.');
        });
    }

    public function test_contact_message_is_saved_and_failure_is_reported_when_email_cannot_be_sent(): void
    {
        config(['mail.contact_address' => 'contact@example.com']);
        Mail::shouldReceive('to')
            ->once()
            ->with('contact@example.com')
            ->andThrow(new RuntimeException('SMTP unavailable'));
        Log::shouldReceive('error')
            ->once()
            ->with('Contact email delivery failed.', \Mockery::on(
                fn (array $context): bool => $context['contact_id'] === 1
                    && $context['exception'] === 'SMTP unavailable'
            ));

        $this->post(route('contact.send'), [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'message' => 'Bonjour.',
        ])->assertRedirect(route('contact'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('contacts', [
            'email' => 'jean@example.com',
            'message' => 'Bonjour.',
        ]);
    }

    public function test_contact_message_is_limited_to_5000_characters(): void
    {
        Mail::fake();

        $this->from(route('contact'))
            ->post(route('contact.send'), [
                'name' => 'Jean Dupont',
                'email' => 'jean@example.com',
                'message' => str_repeat('a', 5001),
            ])
            ->assertRedirect(route('contact'))
            ->assertSessionHasErrors('message');

        $this->assertDatabaseCount('contacts', 0);
        Mail::assertNothingSent();
    }
}
