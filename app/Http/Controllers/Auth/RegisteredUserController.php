<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Locataire;
use App\Models\User; // 👈 1. Importation du modèle Locataire
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        [$user] = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->telephone,
                'role' => User::ROLE_TENANT,
                'role_reviewed_at' => null,
                'password' => Hash::make($request->password),
            ]);

            Locataire::create([
                'nom' => $request->name,
                'prenom' => '',
                'telephone' => $request->telephone ?? '',
                'email' => $request->email,
                'profession' => 'Non spécifié',
            ]);

            return [$user];
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
