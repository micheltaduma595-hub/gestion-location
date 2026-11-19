<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Locataire;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Affiche la liste des paiements enregistrés en caisse.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $paymentsQuery = Payment::with(['locataire', 'house', 'user'])
            ->when($validated['q'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('reference_reçu', 'like', "%{$search}%")
                        ->orWhere('month_covered', 'like', "%{$search}%")
                        ->orWhereHas('locataire', function ($query) use ($search) {
                            $query->where('nom', 'like', "%{$search}%")
                                ->orWhere('prenom', 'like', "%{$search}%");
                        })
                        ->orWhereHas('house', function ($query) use ($search) {
                            $query->where('title', 'like', "%{$search}%")
                                ->orWhere('address', 'like', "%{$search}%");
                        });
                });
            })
            ->when($validated['payment_method'] ?? null, fn ($query, $method) => $query->where('payment_method', $method))
            ->when($validated['date_from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($validated['date_to'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date));

        $filteredAmount = (clone $paymentsQuery)->sum('amount');
        $filteredCount = (clone $paymentsQuery)->count();
        $monthAmount = Payment::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount');
        $monthCount = Payment::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $payments = $paymentsQuery->latest()->paginate(15)->withQueryString();
        $paymentMethods = Payment::query()
            ->whereNotNull('payment_method')
            ->distinct()
            ->orderBy('payment_method')
            ->pluck('payment_method');

        return view('payments.index', compact(
            'payments',
            'filteredAmount',
            'filteredCount',
            'monthAmount',
            'monthCount',
            'paymentMethods',
        ));
    }

    /**
     * Affiche le formulaire d'encaissement d'un loyer.
     */
    public function create()
    {
        Locataire::syncTenantAccounts();
        $locataires = Locataire::all();
        // Récupère toutes les maisons pour qu'elles s'affichent à coup sûr
        $houses = House::all(); 
        return view('payments.create', compact('locataires', 'houses'));
    }

    /**
     * Enregistre le paiement et génère la référence du reçu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'locataire_id' => 'required|exists:locataires,id',
            'house_id' => 'required|exists:houses,id',
            'amount' => 'required|numeric|min:0.01',
            'month_covered' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);

        // Génération d'une référence unique pour le reçu (ex: REC-20261012-5842)
        $reference = 'REC-' . date('Ymd') . '-' . rand(1000, 9999);

        Payment::create([
            'locataire_id' => $request->locataire_id,
            'house_id' => $request->house_id,
            'user_id' => Auth::id(), // L'administrateur ou caissier connecté
            'amount' => $request->amount,
            'month_covered' => $request->month_covered,
            'payment_method' => $request->payment_method,
            'reference_reçu' => $reference,
        ]);

        return redirect()->route('payments.index')->with('success', 'Paiement enregistré et reçu généré avec succès.');
    }

    /**
     * Affiche le reçu de loyer détaillé (prêt à être imprimé).
     */
    public function show(Payment $payment)
    {
        return view('payments.receipt', compact('payment'));
    }

    /**
     * Affiche le formulaire de modification d'un paiement.
     */
    public function edit(Payment $payment)
    {
        Locataire::syncTenantAccounts();
        $locataires = Locataire::all();
        $houses = House::all();
        return view('payments.edit', compact('payment', 'locataires', 'houses'));
    }

    /**
     * Met à jour le paiement dans la base de données.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'locataire_id' => 'required|exists:locataires,id',
            'house_id' => 'required|exists:houses,id',
            'amount' => 'required|numeric|min:0.01',
            'month_covered' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Supprime un paiement de la caisse.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Paiement supprimé avec succès.');
    }
}