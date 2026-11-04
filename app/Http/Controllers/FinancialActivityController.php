<?php

namespace App\Http\Controllers;

use App\Models\FinancialActivity;
use App\Models\Payment;
use Illuminate\Http\Request;

class FinancialActivityController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeManager();

        $validated = $request->validate([
            'year' => 'nullable|integer|min:2000|max:2100',
        ]);
        $year = $validated['year'] ?? now()->year;

        $payments = Payment::with(['house', 'locataire'])
            ->whereYear('created_at', $year)
            ->latest()
            ->get();
        $manualActivities = FinancialActivity::with('user')
            ->whereYear('occurred_on', $year)
            ->latest('occurred_on')
            ->latest()
            ->get();

        $activities = $payments->map(fn (Payment $payment) => [
            'date' => $payment->created_at,
            'type' => 'income',
            'description' => 'Loyer - '.($payment->house?->title ?? 'Logement supprimé'),
            'details' => trim(($payment->locataire?->nom ?? '').' '.($payment->locataire?->prenom ?? '')),
            'amount' => (float) $payment->amount,
            'payment_method' => $payment->payment_method,
            'source' => 'Paiement de loyer',
        ])->concat($manualActivities->map(fn (FinancialActivity $activity) => [
            'date' => $activity->occurred_on,
            'type' => $activity->type,
            'description' => $activity->description,
            'details' => $activity->user?->name ? 'Enregistré par '.$activity->user->name : '',
            'amount' => (float) $activity->amount,
            'payment_method' => $activity->payment_method,
            'source' => 'Saisie manuelle',
        ]))->sortByDesc('date')->values();

        $income = (float) $payments->sum('amount')
            + (float) $manualActivities->where('type', 'income')->sum('amount');
        $expenses = (float) $manualActivities->where('type', 'expense')->sum('amount');
        $years = collect(range(now()->year, now()->year - 5))
            ->push((int) $year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('financial-activities.index', compact(
            'activities',
            'year',
            'years',
            'income',
            'expenses',
        ));
    }

    public function store(Request $request)
    {
        $this->authorizeManager();

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'occurred_on' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:2000|max:2100',
        ]);

        FinancialActivity::create([
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'occurred_on' => $validated['occurred_on'],
            'payment_method' => $validated['payment_method'] ?? null,
        ]);

        return redirect()
            ->route('annual-report.index', ['year' => $validated['year'] ?? now()->year])
            ->with('success', 'L’activité a été enregistrée.');
    }

    private function authorizeManager(): void
    {
        abort_unless(auth()->user()->role === 'gestionnaire', 403);
    }
}
