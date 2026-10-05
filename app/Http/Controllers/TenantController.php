<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    // Afficher la liste des locataires (rôle 'client')
    public function index()
    {
        $tenants = User::where('role', 'client')->latest()->get();
        return view('tenants.index', compact('tenants'));
    }

    // Afficher le formulaire d'ajout d'un locataire
    public function create()
    {
        return view('tenants.create');
    }

    // Enregistrer un nouveau locataire
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'client', // Forcé en tant que locataire/client
        ]);

        return redirect()->route('tenants.index')->with('success', 'Locataire enregistré avec succès !');
    }

    // Afficher le formulaire de modification
    public function edit(User $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    // Mettre à jour les informations du locataire
    public function update(Request $request, User $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $tenant->id,
            'phone' => 'required|string|max:20',
        ]);

        $tenant->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('tenants.index')->with('success', 'Informations du locataire mises à jour !');
    }

    // Supprimer un locataire
    public function destroy(User $tenant)
    {
        $tenant->delete();
        return redirect()->route('tenants.index')->with('success', 'Locataire supprimé avec succès !');
    }
}