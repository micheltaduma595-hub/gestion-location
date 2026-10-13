<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Locataire; // 👈 1. Importation du modèle Locataire
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'telephone' => ['nullable', 'string', 'max:20'], // Attention à bien utiliser 'telephone' comme dans votre formulaire view
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->telephone,          // Enregistrement du téléphone
            'role' => 'client',                     // Attribution du rôle client
            'password' => Hash::make($request->password),
        ]);

        // 3. Création automatique du profil locataire associé
        Locataire::create([
            'nom' => $request->name,
            'prenom' => '',                          // Vous pouvez ajuster si vous séparez le nom et le prénom
            'telephone' => $request->telephone ?? '',
            'email' => $request->email,
            'profession' => 'Non spécifié',           // Valeur par défaut modifiable
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}