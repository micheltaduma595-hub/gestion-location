<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Models\Locataire;
use App\Models\User;
use App\Models\Contact;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Si c'est un client ou un locataire -> Espace personnel
        if ($user->role === 'client' || $user->role === 'locataire') {
            $reservations = $user->reservations()->with('house')->latest()->get();
            $leases = $user->leases()->with('house')->latest()->get();
            $locataire = Locataire::where('email', $user->email)->first();
            $assignedHouses = $locataire
                ? $locataire->maisons()
                    ->whereNotIn('id', $leases->pluck('house_id'))
                    ->latest()
                    ->get()
                : collect();
            $payments = $locataire
                ? $locataire->payments()->with('house')->latest()->get()
                : collect();

            return view('client.dashboard', compact('reservations', 'leases', 'assignedHouses', 'payments'));
        }

        // 2. Si c'est l'administrateur -> Redirection directe vers la gestion des utilisateurs
        if ($user->role === 'admin') {
            return redirect()->route('users.index');
        }

        // 3. Si c'est le gestionnaire -> Contrôle les maisons, les locataires, la caisse et les messages
        $totalHouses = House::count();
        $availableHouses = House::where('status', 'disponible')->count();
        $totalTenants = User::where('role', 'client')->count();
        $houses = House::latest()->take(5)->get();
        $contacts = Contact::latest()->get(); // Récupération des messages de contact

        return view('dashboard', compact('totalHouses', 'availableHouses', 'totalTenants', 'houses', 'contacts'));
    }
}