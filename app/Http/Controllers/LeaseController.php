<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Lease;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaseController extends Controller
{
    public function index(Request $request)
    {
        $leases = Lease::with(['house', 'client'])
            ->when(in_array($request->query('status'), ['actif', 'termine'], true), function ($query) use ($request) {
                $query->where('status', $request->query('status'));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->trim()->toString();

                $query->where(function ($query) use ($search) {
                    $query->whereHas('house', function ($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%");
                    })->orWhereHas('client', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('leases.index', [
            'leases' => $leases,
            'totalCount' => Lease::count(),
            'activeCount' => Lease::where('status', 'actif')->count(),
            'endedCount' => Lease::where('status', 'termine')->count(),
        ]);
    }

    public function create()
    {
        return view('leases.create', [
            'lease' => new Lease(['status' => 'actif']),
            'clients' => $this->clients(),
            'houses' => $this->availableHouses(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateLease($request);

        if ($validated['status'] === 'actif' && Lease::where('house_id', $validated['house_id'])->where('status', 'actif')->exists()) {
            return back()
                ->withErrors(['house_id' => 'Ce logement possède déjà un contrat actif.'])
                ->withInput();
        }

        $lease = Lease::create($validated);
        $lease->house->syncOccupancyStatus();

        return redirect()->route('leases.index')->with('success', 'Contrat de bail créé avec succès.');
    }

    public function edit(Lease $lease)
    {
        return view('leases.edit', [
            'lease' => $lease,
            'clients' => $this->clients(),
            'houses' => $this->availableHouses($lease),
        ]);
    }

    public function update(Request $request, Lease $lease)
    {
        $validated = $this->validateLease($request);

        if (
            $validated['status'] === 'actif'
            && Lease::where('house_id', $validated['house_id'])
                ->where('status', 'actif')
                ->where('id', '!=', $lease->id)
                ->exists()
        ) {
            return back()
                ->withErrors(['house_id' => 'Ce logement possède déjà un autre contrat actif.'])
                ->withInput();
        }

        $previousHouse = $lease->house;

        if ($lease->end_date->toDateString() !== $validated['end_date']) {
            $lease->forceFill([
                'expiration_reminder_sent_at' => null,
                'expiration_overdue_sent_at' => null,
            ])->save();
        }

        $lease->update($validated);
        $previousHouse->syncOccupancyStatus();
        $lease->house->syncOccupancyStatus();

        return redirect()->route('leases.index')->with('success', 'Contrat de bail mis à jour avec succès.');
    }

    public function destroy(Lease $lease)
    {
        $house = $lease->house;
        $lease->delete();
        $house->syncOccupancyStatus();

        return redirect()->route('leases.index')->with('success', 'Contrat de bail supprimé avec succès.');
    }

    private function validateLease(Request $request): array
    {
        return $request->validate([
            'house_id' => ['required', 'integer', Rule::exists('houses', 'id')],
            'client_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', ['client', 'locataire'])),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(['actif', 'termine'])],
        ]);
    }

    private function clients()
    {
        return User::whereIn('role', ['client', 'locataire'])->orderBy('name')->get();
    }

    private function availableHouses(?Lease $lease = null)
    {
        $query = House::whereNull('locataire_id')
            ->whereDoesntHave('leases', function ($query) use ($lease) {
                $query->where('status', 'actif')
                    ->when($lease?->exists, fn ($query) => $query->where('id', '!=', $lease->id));
            });

        if ($lease?->exists) {
            $query->orWhere('id', $lease->house_id);
        }

        return $query->orderBy('title')->get();
    }
}
