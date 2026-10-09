<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HouseController extends Controller
{
    // Afficher toutes les maisons disponibles sur la page d'accueil ou catalogue
    public function index()
    {
        $houses = House::with('user')->where('status', 'disponible')->latest()->get();
        return view('welcome', compact('houses')); 
    }

    // Afficher le formulaire de création (réservé au gestionnaire/admin)
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

        return redirect()->route('dashboard')->with('success', 'Maison publiée avec succès !');
    }

    // Afficher les détails d'une maison
    public function show(House $house)
    {
        return view('houses.show', compact('house'));
    }

    // Afficher le formulaire de modification
    public function edit(House $house)
    {
        return view('houses.edit', compact('house'));
    }

    // Mettre à jour les informations d'une maison
    public function update(Request $request, House $house)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|in:disponible,louée',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($house->image) {
                Storage::disk('public')->delete($house->image);
            }
            $house->image = $request->file('image')->store('houses', 'public');
        }

        $house->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        return redirect()->route('dashboard')->with('success', 'Maison mise à jour avec succès !');
    }

    // Supprimer une maison
    public function destroy(House $house)
    {
        if ($house->image) {
            Storage::disk('public')->delete($house->image);
        }

        $house->delete();

        return redirect()->route('dashboard')->with('success', 'Maison supprimée avec succès !');
    }
}