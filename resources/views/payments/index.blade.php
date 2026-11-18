<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-cash-stack text-primary me-2"></i>Suivi des paiements & loyers
                </h2>
                <p class="text-muted mb-0">Suivez les encaissements et consultez l’historique des loyers.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('annual-report.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-graph-up-arrow me-1"></i> Registre financier
                </a>
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Encaisser un loyer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success-subtle text-success p-3"><i class="bi bi-cash-coin fs-4"></i></div>
                        <div>
                            <div class="small text-muted">Encaissé ce mois</div>
                            <div class="h4 fw-bold text-success mb-0">{{ number_format($monthAmount, 2, ',', ' ') }} $</div>
                            <div class="small text-muted">{{ $monthCount }} paiement(s)</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary-subtle text-primary p-3"><i class="bi bi-funnel fs-4"></i></div>
                        <div>
                            <div class="small text-muted">Total de la sélection</div>
                            <div class="h4 fw-bold mb-0">{{ number_format($filteredAmount, 2, ',', ' ') }} $</div>
                            <div class="small text-muted">{{ $filteredCount }} paiement(s)</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info-subtle text-info p-3"><i class="bi bi-receipt fs-4"></i></div>
                        <div>
                            <div class="small text-muted">Modes d’encaissement</div>
                            <div class="h4 fw-bold mb-0">{{ $paymentMethods->count() }}</div>
                            <div class="small text-muted">utilisés dans l’historique</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning-subtle text-warning p-3"><i class="bi bi-calendar2-range fs-4"></i></div>
                        <div>
                            <div class="small text-muted">Finances et dépenses</div>
                            <a href="{{ route('annual-report.index') }}" class="fw-semibold text-decoration-none">Ouvrir le registre annuel <i class="bi bi-arrow-up-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h3 class="h5 fw-bold mb-1">Historique des paiements</h3>
                        <p class="small text-muted mb-0">Recherchez un reçu, un locataire, un logement ou une période couverte.</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('payments.index') }}" class="row g-2 align-items-end mb-4">
                    <div class="col-lg-4">
                        <label for="q" class="form-label">Recherche</label>
                        <input type="search" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Reçu, locataire, logement, mois">
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label for="payment_method" class="form-label">Mode</label>
                        <select id="payment_method" name="payment_method" class="form-select">
                            <option value="">Tous les modes</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method }}" @selected(request('payment_method') === $method)>{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label for="date_from" class="form-label">Du</label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="form-control">
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label for="date_to" class="form-label">Au</label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="form-control">
                    </div>
                    <div class="col-sm-6 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary flex-grow-1"><i class="bi bi-search me-1"></i>Filtrer</button>
                        @if(request()->hasAny(['q', 'payment_method', 'date_from', 'date_to']))
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary" title="Effacer les filtres" aria-label="Effacer les filtres"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Reçu / date</th>
                                <th>Locataire</th>
                                <th>Logement</th>
                                <th>Période couverte</th>
                                <th>Mode</th>
                                <th>Caissier</th>
                                <th class="text-end">Montant</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>
                                        <span class="badge text-bg-secondary">{{ $payment->reference_reçu }}</span>
                                        <div class="small text-muted mt-1">{{ $payment->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $payment->locataire?->nom ?? 'Locataire supprimé' }} {{ $payment->locataire?->prenom }}</div>
                                        <div class="small text-muted">{{ $payment->locataire?->telephone }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $payment->house?->title ?? 'Logement supprimé' }}</div>
                                        @if($payment->house)
                                            <div class="small text-muted">{{ $payment->house->address }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $payment->month_covered }}</td>
                                    <td><span class="badge text-bg-info">{{ $payment->payment_method }}</span></td>
                                    <td>{{ $payment->user?->name ?? '—' }}</td>
                                    <td class="text-end text-nowrap fw-bold text-success">{{ number_format($payment->amount, 2, ',', ' ') }} $</td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('payments.receipt', $payment) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark" title="Imprimer le reçu" aria-label="Imprimer le reçu">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-outline-primary" title="Modifier" aria-label="Modifier le paiement">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce paiement ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" aria-label="Supprimer le paiement">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="bi bi-receipt fs-2 d-block mb-2"></i>
                                        Aucun paiement ne correspond à votre recherche.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($payments->count())
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="6" class="text-end">Total de la sélection</th>
                                    <th class="text-end text-nowrap">{{ number_format($filteredAmount, 2, ',', ' ') }} $</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
                    <div class="small text-muted">
                        Affichage de {{ $payments->firstItem() ?? 0 }} à {{ $payments->lastItem() ?? 0 }} sur {{ $payments->total() }} paiement(s)
                    </div>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
