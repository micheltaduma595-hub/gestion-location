<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseController extends Controller
{
    // Afficher toutes les maisons disponibles sur la page d'accueil ou catalogue
    public function index()
    {
        $houses = House::with('user')->where('status', 'disponible')->latest()->get();
        return view('welcome', compact('houses')); // Ou une vue dédiée si vous préférez
    }

    // Afficher le formulaire de création (réservé au gestionnaire)
    public function create()
    {
        return view('houses.create');
    }

    // Enregistrer une nouvelle maison
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('houses', 'public');
        }

        House::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'address' => $request->address,
            'image' => $imagePath,
            'status' => 'disponible',
        ]);

        // Redirection modifiée vers le dashboard
        return redirect()->route('dashboard')->with('success', 'Maison publiée avec succès !');
    }

    // Afficher les détails d'une maison (avec le contact du gestionnaire)
    public function show(House $house)
    {
        return view('houses.show', compact('house'));
    }
}