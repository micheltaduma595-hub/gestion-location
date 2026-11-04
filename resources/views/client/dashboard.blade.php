<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $sections[$section] }} - GestionLoyer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="bi bi-house-door-fill text-info me-2"></i>Gestion<span class="text-info">Loyer</span>
            </a>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $sections[$section] }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach($sections as $key => $label)
                            <li>
                                <a class="dropdown-item {{ $section === $key ? 'active' : '' }}"
                                   href="{{ $key === 'informations' ? route('dashboard') : route('client.dashboard.section', $key) }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">Voir les logements</a>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="mb-4">
            <h1 class="h2 fw-bold">Mon espace client</h1>
            <p class="text-muted mb-0">Bonjour {{ auth()->user()->name }}.</p>
        </div>

        @if($section === 'informations')
        <section class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-3"><i class="bi bi-person-circle text-primary me-2"></i>Mes informations</h2>
                <div class="row">
                    <div class="col-md-6 mb-2"><span class="text-muted">Nom :</span> {{ auth()->user()->name }}</div>
                    <div class="col-md-6 mb-2"><span class="text-muted">Adresse e-mail :</span> {{ auth()->user()->email }}</div>
                    @if(auth()->user()->phone)
                        <div class="col-md-6"><span class="text-muted">Téléphone :</span> {{ auth()->user()->phone }}</div>
                    @endif
                </div>
            </div>
        </section>
        @endif

        @if($section === 'reservations')
        <section class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 fw-bold mb-0"><i class="bi bi-calendar-check text-primary me-2"></i>Mes réservations</h2>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm">Trouver un logement</a>
                </div>
                @forelse($reservations as $reservation)
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 border-top py-3">
                        <div>
                            <div class="fw-semibold">{{ $reservation->house?->title ?? 'Logement supprimé' }}</div>
                            @if($reservation->house)
                                <div class="small text-muted">{{ $reservation->house->address }}</div>
                            @endif
                        </div>
                        <div class="text-md-end">
                            <span class="badge text-bg-info">{{ ucfirst(str_replace('_', ' ', $reservation->status)) }}</span>
                            <div class="small text-muted mt-1">Demande du {{ $reservation->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted border-top pt-3 mb-0">Vous n’avez pas encore de réservation.</p>
                @endforelse
            </div>
        </section>
        @endif

        @if($section === 'locations')
        <section class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-3"><i class="bi bi-file-earmark-text text-primary me-2"></i>Mes locations</h2>
                @foreach($leases as $lease)
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 border-top py-3">
                        <div>
                            <div class="fw-semibold">{{ $lease->house?->title ?? 'Logement supprimé' }}</div>
                            @if($lease->house)
                                <div class="small text-muted">{{ $lease->house->address }}</div>
                            @endif
                        </div>
                        <div class="text-md-end">
                            <span class="badge text-bg-success">{{ ucfirst($lease->status) }}</span>
                            <div class="small text-muted mt-1">{{ $lease->start_date }} – {{ $lease->end_date }}</div>
                        </div>
                    </div>
                @endforeach
                @foreach($assignedHouses as $house)
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 border-top py-3">
                        <div>
                            <div class="fw-semibold">{{ $house->title }}</div>
                            <div class="small text-muted">{{ $house->address }}</div>
                            <div class="small text-muted">Attribué par votre gestionnaire</div>
                        </div>
                        <div class="text-md-end">
                            <span class="badge text-bg-success">{{ $house->status === 'louee' ? 'Louée' : 'Disponible' }}</span>
                            <div class="small text-muted mt-1">{{ number_format($house->price, 2) }} $ / mois</div>
                        </div>
                    </div>
                @endforeach
                @if($leases->isEmpty() && $assignedHouses->isEmpty())
                    <p class="text-muted border-top pt-3 mb-0">Aucune location n’est associée à votre compte pour le moment.</p>
                @endif
            </div>
        </section>
        @endif

        @if($section === 'paiements')
        <section class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-3"><i class="bi bi-receipt text-primary me-2"></i>Mon rapport de paiements</h2>
                @forelse($payments as $payment)
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 border-top py-3">
                        <div>
                            <div class="fw-semibold">{{ $payment->house?->title ?? 'Logement supprimé' }}</div>
                            <div class="small text-muted">{{ $payment->month_covered }} · {{ $payment->payment_method }}</div>
                            <div class="small text-muted">Reçu : {{ $payment->reference_reçu }}</div>
                        </div>
                        <div class="text-md-end">
                            <span class="fw-semibold text-success">{{ number_format($payment->amount, 2) }} $</span>
                            <div class="small text-muted mt-1">{{ $payment->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted border-top pt-3 mb-0">Aucun paiement n’est encore enregistré pour votre compte.</p>
                @endforelse
            </div>
        </section>
        @endif
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>