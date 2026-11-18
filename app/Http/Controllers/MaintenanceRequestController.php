<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaintenanceRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isManager = in_array($user->role, ['gestionnaire', 'admin'], true);
        abort_unless($isManager || in_array($user->role, ['client', 'locataire'], true), 403);

        $requests = MaintenanceRequest::with(['user', 'house'])
            ->when(! $isManager, fn ($query) => $query->where('user_id', $user->id))
            ->when($isManager && in_array($request->query('status'), array_keys(MaintenanceRequest::STATUSES), true),
                fn ($query) => $query->where('status', $request->query('status')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('maintenance-requests.index', [
            'requests' => $requests,
            'isManager' => $isManager,
            'counts' => MaintenanceRequest::query()
                ->when(! $isManager, fn ($query) => $query->where('user_id', $user->id))
                ->selectRaw('status, count(*) as aggregate')
                ->groupBy('status')
                ->pluck('aggregate', 'status'),
            'statuses' => MaintenanceRequest::STATUSES,
        ]);
    }

    public function create(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['client', 'locataire'], true), 403);

        $houses = $request->user()->leases()
            ->where('status', 'actif')
            ->with('house')
            ->get()
            ->pluck('house')
            ->filter()
            ->unique('id')
            ->values();

        $locataire = Locataire::where('email', $request->user()->email)->first();
        if ($locataire) {
            $houses = $houses->concat($locataire->maisons()->get())->unique('id')->values();
        }

        return view('maintenance-requests.create', [
            'houses' => $houses,
            'categories' => MaintenanceRequest::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['client', 'locataire'], true), 403);

        $validated = $request->validate([
            'house_id' => ['nullable', 'integer', Rule::exists('houses', 'id')],
            'category' => ['required', Rule::in(MaintenanceRequest::CATEGORIES)],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
        ]);

        $user = $request->user();
        if ($validated['house_id'] ?? null) {
            $houseIds = $user->leases()
                ->where('status', 'actif')
                ->pluck('house_id')
                ->all();
            $locataire = Locataire::where('email', $user->email)->first();
            if ($locataire) {
                $houseIds = array_merge($houseIds, $locataire->maisons()->pluck('houses.id')->all());
            }

            abort_unless(in_array((int) $validated['house_id'], $houseIds, true), 403);
        }

        MaintenanceRequest::create([
            ...$validated,
            'user_id' => $user->id,
        ]);

        return redirect()->route('maintenance-requests.index')
            ->with('success', 'Votre réclamation a été envoyée au gestionnaire.');
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        abort_unless(in_array($request->user()->role, ['gestionnaire', 'admin'], true), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(MaintenanceRequest::STATUSES))],
            'manager_response' => ['nullable', 'string', 'max:5000'],
        ]);

        $maintenanceRequest->update($validated);

        return redirect()->route('maintenance-requests.index')
            ->with('success', 'Le suivi de la réclamation a été mis à jour.');
    }

    public function destroy(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        abort_unless(in_array($request->user()->role, ['gestionnaire', 'admin'], true), 403);
        abort_unless($maintenanceRequest->status === 'resolue', 422, 'Seules les réclamations résolues peuvent être supprimées.');

        $maintenanceRequest->delete();

        return redirect()->route('maintenance-requests.index')
            ->with('success', 'La réclamation résolue a été supprimée.');
    }
}
