<?php

namespace App\Http\Controllers;

use App\Models\FinancialActivity;
use App\Models\House;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['admin', 'gestionnaire'], true), 403);

        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);
        $year = (int) ($validated['year'] ?? now()->year);

        $monthly = collect(range(1, 12))->map(function (int $month) use ($year) {
            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            $activityDates = [$start->toDateString(), $end->toDateString()];
            $activities = FinancialActivity::query()
                ->whereBetween('occurred_on', $activityDates)
                ->select('type', DB::raw('SUM(amount) as total'))
                ->groupBy('type')
                ->pluck('total', 'type');

            return [
                'label' => $start->locale('fr')->translatedFormat('M'),
                'rent' => (float) Payment::whereBetween('created_at', [$start, $end])->sum('amount'),
                'other_income' => (float) ($activities->get('income', 0)),
                'expenses' => (float) ($activities->get('expense', 0)),
            ];
        });

        $rentIncome = (float) Payment::whereYear('created_at', $year)->sum('amount');
        $otherIncome = (float) FinancialActivity::whereYear('occurred_on', $year)
            ->where('type', 'income')
            ->sum('amount');
        $expenses = (float) FinancialActivity::whereYear('occurred_on', $year)
            ->where('type', 'expense')
            ->sum('amount');
        $income = $rentIncome + $otherIncome;
        $totalHouses = House::count();
        $rentedHouses = House::where('status', 'louee')->count();
        $availableHouses = House::where('status', 'disponible')->count();

        $paymentMethods = Payment::query()
            ->whereYear('created_at', $year)
            ->select('payment_method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as payments_count'))
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $topHouses = DB::table('houses')
            ->join('payments', 'payments.house_id', '=', 'houses.id')
            ->whereYear('payments.created_at', $year)
            ->select('houses.id', 'houses.title', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('houses.id', 'houses.title')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $reservationStatuses = Reservation::query()
            ->whereYear('created_at', $year)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $maintenanceStatuses = MaintenanceRequest::query()
            ->whereYear('created_at', $year)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $years = collect(range(now()->year, now()->year - 5))
            ->push($year)
            ->unique()
            ->sortDesc()
            ->values();
        $chartMax = max(1, (float) $monthly->max(fn ($month) => max(
            $month['rent'] + $month['other_income'],
            $month['expenses'],
        )));

        return view('reports.index', compact(
            'year',
            'years',
            'monthly',
            'chartMax',
            'rentIncome',
            'otherIncome',
            'income',
            'expenses',
            'totalHouses',
            'rentedHouses',
            'availableHouses',
            'paymentMethods',
            'topHouses',
            'reservationStatuses',
            'maintenanceStatuses',
        ));
    }
}
