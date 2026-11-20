<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use App\Models\House;
use App\Models\User;
use App\Support\SiteLogo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocataireController extends Controller
{
    private function authorizeStaff(): void
    {
        abort_unless(
            in_array(auth()->user()->role, [User::ROLE_ADMIN, User::ROLE_MANAGER], true),
            403
        );
    }

    /**
     * Affiche la liste des locataires avec leurs maisons associées.
     */
    public function index()
    {
        $this->authorizeStaff();

        $locataires = $this->tenantDirectory();

        return view('locataires.index', compact('locataires'));
    }

    public function show(Locataire $locataire)
    {
        $this->authorizeStaff();

        $locataire = $this->tenantDirectory()->firstWhere('id', $locataire->id);
        abort_if($locataire === null, 404);

        return view('locataires.show', compact('locataire'));
    }

    public function exportPdf()
    {
        $this->authorizeStaff();

        $locataires = $this->tenantDirectory();
        $siteLogo = SiteLogo::path();
        $logoDisk = Storage::disk('public');
        $siteLogoData = $siteLogo && $logoDisk->exists($siteLogo)
            ? 'data:' . $logoDisk->mimeType($siteLogo) . ';base64,' . base64_encode($logoDisk->get($siteLogo))
            : null;
        $flagData = 'data:image/svg+xml;base64,' . base64_encode(
            file_get_contents(public_path('images/drapeau-rdc.svg'))
        );

        return Pdf::loadView('locataires.pdf', [
            'locataires' => $locataires,
            'dateGeneration' => now()->format('d/m/Y H:i'),
            'companyName' => config('app.name') ?: 'GestionLoyer',
            'siteLogoData' => $siteLogoData,
            'flagData' => $flagData,
        ])->setPaper('a4', 'landscape')->stream('liste-locataires.pdf');
    }

    private function tenantDirectory(): Collection
    {
        Locataire::syncTenantAccounts();

        return Locataire::with([
            'maisons',
            'user.leases' => fn ($query) => $query->where('status', 'actif')->with('house'),
        ])->latest()->get()->each(function (Locataire $locataire): void {
            $leaseHouses = $locataire->user?->leases->pluck('house')->filter() ?? collect();
            $locataire->setRelation(
                'maisons',
                $locataire->maisons->concat($leaseHouses)->unique('id')->values()
            );
        });
    }

    /**
     * Affiche le formulaire de création d'un locataire.
     */
    public function create()
    {
        $this->authorizeStaff();

        return view('locataires.create');
    }

    /**
     * Enregistre un nouveau locataire dans la base de données.
     */
    public function store(Request $request)
    {
        $this->authorizeStaff();

        $request->validate([
            'nom' => 'required|string|max:255',
            'postnom' => 'nullable|string|max:255',
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
        $this->authorizeStaff();

        // Récupère uniquement les maisons qui sont disponibles
        $maisonsDisponibles = House::whereNull('locataire_id')
            ->whereDoesntHave('leases', fn ($query) => $query->where('status', 'actif'))
            ->get();
        return view('locataires.attribuer', compact('locataire', 'maisonsDisponibles'));
    }

    /**
     * Traite l'attribution de la maison au locataire.
     */
    public function attribuerMaison(Request $request, Locataire $locataire)
    {
        $this->authorizeStaff();

        $request->validate([
            'house_id' => 'required|exists:houses,id',
        ]);

        // Mettre à jour la maison sélectionnée
        $house = House::findOrFail($request->house_id);
        if ($house->isOccupied()) {
            return back()->withErrors(['house_id' => 'Cette maison est déjà occupée.'])->withInput();
        }

        $house->update([
            'locataire_id' => $locataire->id,
        ]);
        $house->syncOccupancyStatus();

        return redirect()->route('locataires.index')->with('success', 'Maison attribuée au locataire avec succès.');
    }

    /**
     * Affiche le formulaire de modification d'un locataire.
     */
    public function edit(Locataire $locataire)
    {
        $this->authorizeStaff();

        return view('locataires.edit', compact('locataire'));
    }

    /**
     * Met à jour les informations du locataire.
     */
    public function update(Request $request, Locataire $locataire)
    {
        $this->authorizeStaff();

        $request->validate([
            'nom' => 'required|string|max:255',
            'postnom' => 'nullable|string|max:255',
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
        $this->authorizeStaff();

        // Libérer les maisons associées avant de supprimer le locataire
        House::where('locataire_id', $locataire->id)->get()->each(function (House $house): void {
            $house->update(['locataire_id' => null]);
            $house->syncOccupancyStatus();
        });

        // Supprime l'utilisateur associé si son e-mail correspond
        if ($locataire->email) {
            User::where('email', $locataire->email)->delete();
        }

        // Supprime le locataire
        $locataire->delete();

        return redirect()->route('locataires.index')->with('success', 'Locataire et compte utilisateur associés supprimés avec succès.');
    }
}