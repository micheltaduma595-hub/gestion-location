<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use App\Models\House;
use Illuminate\Http\Request;

class LocataireController extends Controller
{
    /**
     * Affiche la liste des locataires avec leurs maisons associées.
     */
    public function index()
    {
        $locataires = Locataire::with('maisons')->latest()->get();
        return view('locataires.index', compact('locataires'));
    }

    /**
     * Affiche le formulaire de création d'un locataire.
     */
    public function create()
    {
        return view('locataires.create');
    }

    /**
     * Enregistre un nouveau locataire dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:locataires,email',
            'profession' => 'nullable|string|max:255',
        ]);

        Locataire::create($request->all());

        return redirect()->route('locataires.index')->with('success', 'Locataire enregistré avec succès.');
    }

    /**
     * Affiche le formulaire d'attribution d'une maison à un locataire.
     */
    public function attribuerMaisonForm(Locataire $locataire)
    {
        // Récupère uniquement les maisons qui sont disponibles
        $maisonsDisponibles = House::where('status', 'disponible')->get();
        return view('locataires.attribuer', compact('locataire', 'maisonsDisponibles'));
    }

    /**
     * Traite l'attribution de la maison au locataire.
     */
    public function attribuerMaison(Request $request, Locataire $locataire)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,id',
        ]);

        // Mettre à jour la maison sélectionnée
        $house = House::findOrFail($request->house_id);
        $house->update([
            'locataire_id' => $locataire->id,
            'status' => 'louée' // Met à jour le statut de la maison
        ]);

        return redirect()->route('locataires.index')->with('success', 'Maison attribuée au locataire avec succès.');
    }

    /**
     * Affiche le formulaire de modification d'un locataire.
     */
    public function edit(Locataire $locataire)
    {
        return view('locataires.edit', compact('locataire'));
    }

    /**
     * Met à jour les informations du locataire.
     */
    public function update(Request $request, Locataire $locataire)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:locataires,email,' . $locataire->id,
            'profession' => 'nullable|string|max:255',
        ]);

        $locataire->update($request->all());

        return redirect()->route('locataires.index')->with('success', 'Informations du locataire mises à jour avec succès.');
    }

    /**
     * Supprime un locataire.
     */
    public function destroy(Locataire $locataire)
    {
        // Libérer les maisons associées avant de supprimer le locataire
        House::where('locataire_id', $locataire->id)->update([
            'locataire_id' => null,
            'status' => 'disponible'
        ]);

        $locataire->delete();

        return redirect()->route('locataires.index')->with('success', 'Locataire supprimé avec succès.');
    }
}