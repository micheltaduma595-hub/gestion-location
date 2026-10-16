<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact; // 👈 1. Importation du modèle Contact

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
        // Validation des champs du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // 👈 2. Enregistrement du message dans la base de données
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        // Redirection avec un message de succès
        return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }
}