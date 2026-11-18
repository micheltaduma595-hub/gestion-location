<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    // Affiche la page de contact
    public function show()
    {
        return view('contact');
    }

    // Traite l'envoi du message
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $contact = Contact::create($validated);

        try {
            Mail::to(config('mail.contact_address'))->send(new ContactMessage($contact));
        } catch (Throwable $exception) {
            Log::error('Contact email delivery failed.', [
                'contact_id' => $contact->id,
                'exception' => $exception->getMessage(),
            ]);

            return redirect()->route('contact')->with(
                'error',
                'Votre message a été enregistré, mais notre notification par e-mail a échoué. Veuillez nous contacter par un autre moyen.'
            );
        }

        return redirect()->route('contact')->with(
            'success',
            'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.'
        );
    }
}
