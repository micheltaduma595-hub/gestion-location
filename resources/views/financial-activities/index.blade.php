<x-app-layout>
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="bi bi-calendar2-range text-primary me-2"></i>Registre annuel</h1>
                <p class="text-muted mb-0">Retrouvez les entrées et les sorties d’argent de l’année sélectionnée.</p>
            </div>
            <form method="GET" action="{{ route('annual-report.index') }}" class="d-flex align-items-center gap-2">
                <label for="year" class="form-label mb-0">Année</label>
                <select id="year" name="year" class="form-select" onchange="this.form.submit()">
                    @foreach($years as $availableYear)
                        <option value="{{ $availableYear }}" @selected($year === $availableYear)>{{ $availableYear }}</option>
                    @endforeach
                </select>
                <noscript><button class="btn btn-outline-primary" type="submit">Afficher</button></noscript>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Total des entrées en {{ $year }}</div>
                        <div class="h3 fw-bold text-success mb-0">{{ number_format($income, 2, ',', ' ') }} $</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Total des sorties en {{ $year }}</div>
                        <div class="h3 fw-bold text-danger mb-0">{{ number_format($expenses, 2, ',', ' ') }} $</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Solde net en {{ $year }}</div>
                        <div class="h3 fw-bold mb-0">{{ number_format($income - $expenses, 2, ',', ' ') }} $</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h2 class="h5 fw-bold mb-0">Ajouter une activité</h2>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Les paiements de loyers sont ajoutés automatiquement. Utilisez ce formulaire pour les autres entrées et les dépenses.</p>
                        <form method="POST" action="{{ route('annual-report.store') }}">
                            @csrf
                            <input type="hidden" name="year" value="{{ $year }}">

                            <div class="mb-3">
                                <label for="type" class="form-label">Type d’activité</label>
                                <select id="type" name="type" class="form-select" required>
                                    <option value="income" @selected(old('type') === 'income')>Entrée d’argent</option>
                                    <option value="expense" @selected(old('type') === 'expense')>Sortie d’argent</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Motif</label>
                                <input id="description" name="description" class="form-control" value="{{ old('description') }}" maxlength="255" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Montant ($)</label>
                                <input id="amount" name="amount" type="number" min="0.01" step="0.01" class="form-control" value="{{ old('amount') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="occurred_on" class="form-label">Date de l’activité</label>
                                <input id="occurred_on" name="occurred_on" type="date" class="form-control" value="{{ old('occurred_on', $year === now()->year ? now()->toDateString() : $year.'-01-01') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Mode de paiement <span class="text-muted">(facultatif)</span></label>
                                <input id="payment_method" name="payment_method" class="form-control" value="{{ old('payment_method') }}" maxlength="100">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle me-1"></i> Enregistrer l’activité
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h2 class="h5 fw-bold mb-0">Activités de {{ $year }}</h2>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Activité</th>
                                        <th>Source / mode</th>
                                        <th class="text-end">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activities as $activity)
                                        <tr>
                                            <td class="text-nowrap">{{ $activity['date']->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $activity['description'] }}</div>
                                                @if($activity['details'])
                                                    <div class="small text-muted">{{ $activity['details'] }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="small">{{ $activity['source'] }}</div>
                                                @if($activity['payment_method'])
                                                    <div class="small text-muted">{{ $activity['payment_method'] }}</div>
                                                @endif
                                            </td>
                                            <td class="text-end text-nowrap fw-semibold {{ $activity['type'] === 'income' ? 'text-success' : 'text-danger' }}">
                                                {{ $activity['type'] === 'income' ? '+' : '−' }}{{ number_format($activity['amount'], 2, ',', ' ') }} $
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Aucune activité enregistrée pour {{ $year }}.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
