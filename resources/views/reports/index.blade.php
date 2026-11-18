<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-bar-chart-line text-primary me-2"></i>Rapports et statistiques
                </h2>
                <p class="text-muted mb-0">Vue d’ensemble financière et opérationnelle de votre gestion locative.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <form method="GET" action="{{ route('reports.index') }}" class="d-flex align-items-center gap-2">
                    <label for="year" class="form-label mb-0">Année</label>
                    <select id="year" name="year" class="form-select" onchange="this.form.submit()">
                        @foreach($years as $availableYear)
                            <option value="{{ $availableYear }}" @selected($year === $availableYear)>{{ $availableYear }}</option>
                        @endforeach
                    </select>
                    <noscript><button class="btn btn-outline-primary" type="submit">Afficher</button></noscript>
                </form>
                <a href="{{ route('annual-report.index', ['year' => $year]) }}" class="btn btn-outline-primary">
                    <i class="bi bi-journal-text me-1"></i> Registre financier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small text-muted">Revenus totaux · {{ $year }}</div>
                                <div class="h4 fw-bold text-success mb-1">{{ number_format($income, 2, ',', ' ') }} $</div>
                                <div class="small text-muted">Loyers : {{ number_format($rentIncome, 2, ',', ' ') }} $</div>
                            </div>
                            <span class="rounded-circle bg-success-subtle text-success p-3"><i class="bi bi-graph-up-arrow fs-5"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small text-muted">Dépenses · {{ $year }}</div>
                                <div class="h4 fw-bold text-danger mb-1">{{ number_format($expenses, 2, ',', ' ') }} $</div>
                                <div class="small text-muted">Sorties enregistrées</div>
                            </div>
                            <span class="rounded-circle bg-danger-subtle text-danger p-3"><i class="bi bi-graph-down-arrow fs-5"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small text-muted">Solde net · {{ $year }}</div>
                                <div class="h4 fw-bold mb-1 {{ $income - $expenses >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($income - $expenses, 2, ',', ' ') }} $</div>
                                <div class="small text-muted">Revenus moins dépenses</div>
                            </div>
                            <span class="rounded-circle bg-primary-subtle text-primary p-3"><i class="bi bi-wallet2 fs-5"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small text-muted">Occupation des logements</div>
                                <div class="h4 fw-bold mb-1">{{ $totalHouses > 0 ? number_format($rentedHouses / $totalHouses * 100, 1, ',', ' ') : '0,0' }} %</div>
                                <div class="small text-muted">{{ $rentedHouses }} loué(s) · {{ $availableHouses }} disponible(s)</div>
                            </div>
                            <span class="rounded-circle bg-info-subtle text-info p-3"><i class="bi bi-houses fs-5"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <section class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h3 class="h5 fw-bold mb-1">Évolution financière mensuelle</h3>
                        <p class="small text-muted mb-0">Loyers et autres revenus comparés aux dépenses enregistrées en {{ $year }}.</p>
                    </div>
                    <div class="card-body px-4 pt-4">
                        <div class="d-flex flex-wrap gap-3 small mb-3">
                            <span><i class="bi bi-square-fill text-success me-1"></i> Loyers</span>
                            <span><i class="bi bi-square-fill text-primary me-1"></i> Autres revenus</span>
                            <span><i class="bi bi-square-fill text-danger me-1"></i> Dépenses</span>
                        </div>
                        <div class="d-flex align-items-end justify-content-between gap-2 border-bottom" style="height: 230px">
                            @foreach($monthly as $month)
                                @php
                                    $rentHeight = $month['rent'] > 0 ? max(3, $month['rent'] / $chartMax * 100) : 0;
                                    $otherHeight = $month['other_income'] > 0 ? max(3, $month['other_income'] / $chartMax * 100) : 0;
                                    $expenseHeight = $month['expenses'] > 0 ? max(3, $month['expenses'] / $chartMax * 100) : 0;
                                @endphp
                                <div class="d-flex flex-column align-items-center flex-grow-1 h-100 justify-content-end" title="{{ $month['label'] }} — Loyers : {{ number_format($month['rent'], 2, ',', ' ') }} $, autres revenus : {{ number_format($month['other_income'], 2, ',', ' ') }} $, dépenses : {{ number_format($month['expenses'], 2, ',', ' ') }} $">
                                    <div class="d-flex align-items-end justify-content-center gap-1 w-100 flex-grow-1">
                                        <div class="bg-success rounded-top" style="width: 22%; height: {{ $rentHeight }}%"></div>
                                        <div class="bg-primary rounded-top" style="width: 22%; height: {{ $otherHeight }}%"></div>
                                        <div class="bg-danger rounded-top" style="width: 22%; height: {{ $expenseHeight }}%"></div>
                                    </div>
                                    <span class="small text-muted py-2">{{ $month['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-xl-4">
                <section class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h3 class="h5 fw-bold mb-1">Modes de paiement</h3>
                        <p class="small text-muted mb-0">Répartition des loyers encaissés en {{ $year }}.</p>
                    </div>
                    <div class="card-body px-4">
                        @forelse($paymentMethods as $method)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between gap-2 small mb-1">
                                    <span class="fw-semibold">{{ $method->payment_method }}</span>
                                    <span>{{ number_format($method->total, 2, ',', ' ') }} $ <span class="text-muted">({{ $method->payments_count }})</span></span>
                                </div>
                                <div class="progress" role="progressbar" aria-label="Part des paiements {{ $method->payment_method }}" aria-valuenow="{{ $rentIncome > 0 ? round($method->total / $rentIncome * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100" style="height: 8px">
                                    <div class="progress-bar" style="width: {{ $rentIncome > 0 ? $method->total / $rentIncome * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0 py-4 text-center">Aucun paiement de loyer enregistré en {{ $year }}.</p>
                        @endforelse
                        <div class="border-top pt-3 mt-3">
                            <div class="small text-muted">Autres revenus saisis</div>
                            <div class="fw-bold">{{ number_format($otherIncome, 2, ',', ' ') }} $</div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-7">
                <section class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h3 class="h5 fw-bold mb-1">Logements avec le plus de loyers encaissés</h3>
                        <p class="small text-muted mb-0">Top 5 en {{ $year }}.</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr><th class="ps-4">Logement</th><th class="text-end pe-4">Loyers encaissés</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topHouses as $house)
                                        <tr>
                                            <td class="ps-4">{{ $house->title }}</td>
                                            <td class="text-end pe-4 fw-semibold text-success">{{ number_format($house->total, 2, ',', ' ') }} $</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center text-muted py-4">Aucun loyer encaissé en {{ $year }}.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-xl-5">
                <section class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h3 class="h5 fw-bold mb-1">Activité opérationnelle · {{ $year }}</h3>
                        <p class="small text-muted mb-0">Réservations et demandes de maintenance.</p>
                    </div>
                    <div class="card-body px-4">
                        <h4 class="small text-uppercase text-muted fw-bold">Réservations</h4>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach(['en_attente' => 'En attente', 'validee' => 'Validées', 'annulee' => 'Annulées'] as $status => $label)
                                <span class="badge rounded-pill text-bg-light border text-dark">{{ $label }} : {{ $reservationStatuses->get($status, 0) }}</span>
                            @endforeach
                        </div>
                        <h4 class="small text-uppercase text-muted fw-bold">Réclamations / maintenance</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['ouverte' => 'Ouvertes', 'en_cours' => 'En cours', 'resolue' => 'Résolues'] as $status => $label)
                                <span class="badge rounded-pill text-bg-light border text-dark">{{ $label }} : {{ $maintenanceStatuses->get($status, 0) }}</span>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
