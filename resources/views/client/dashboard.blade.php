<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $sections[$section] }} - GestionLoyer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/compact-ui.css') }}">
    <style>
        .client-sidebar {
            --bs-offcanvas-width: 240px;
            background-color: #212529 !important;
        }

        .client-sidebar .nav-link {
            color: #adb5bd;
            border-radius: 0.5rem;
            padding: 0.55rem 0.7rem;
        }

        .client-sidebar .nav-link:hover,
        .client-sidebar .nav-link.active {
            color: #fff;
            background-color: #343a40;
        }

        .client-brand-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 0.5rem;
        }

        .client-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            color: #fff;
            text-decoration: none;
        }

        .client-brand-name {
            white-space: nowrap;
        }

        .client-content {
            min-width: 0;
        }

        @media (min-width: 992px) {
            .client-sidebar {
                flex: 0 0 240px !important;
                width: 240px !important;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-lg-flex min-vh-100">
        <nav class="client-sidebar offcanvas-lg offcanvas-start d-lg-flex flex-column p-3 shadow"
            tabindex="-1" id="clientSidebar" aria-labelledby="clientSidebarLabel">
            <div class="offcanvas-header d-lg-none px-0 pt-0">
                <a class="navbar-brand client-brand fw-bold" href="{{ route('dashboard') }}" id="clientSidebarLabel">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo du site" class="client-brand-logo">
                    @else
                        <i class="bi bi-house-door-fill text-info"></i>
                    @endif
                    <span class="client-brand-name">GestionLoyer</span>
                </a>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
            </div>

            <div class="d-none d-lg-flex align-items-center mb-4 px-2 pt-2">
                <a class="client-brand fw-bold fs-5" href="{{ route('dashboard') }}">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo du site" class="client-brand-logo">
                    @else
                        <i class="bi bi-house-door-fill text-info"></i>
                    @endif
                    <span class="client-brand-name">GestionLoyer</span>
                </a>
            </div>

            <div class="offcanvas-body d-flex flex-column p-0">
                <div class="text-uppercase text-secondary small fw-bold px-2 mb-2">Navigation principale</div>
                <ul class="navbar-nav flex-column gap-2 mb-4">
                    @foreach($sections as $key => $label)
                        <li class="nav-item">
                            <a class="nav-link {{ $section === $key ? 'active fw-bold' : '' }}"
                               href="{{ $key === 'informations' ? route('dashboard') : route('client.dashboard.section', $key) }}"
                               @if($section === $key) aria-current="page" @endif>
                                <i class="bi {{ match($key) {
                                    'informations' => 'bi-person-circle',
                                    'reservations' => 'bi-calendar-check',
                                    'locations' => 'bi-house-check',
                                    'paiements' => 'bi-receipt',
                                    default => 'bi-circle',
                                } }} me-2"></i>{{ $label }}
                            </a>
                        </li>
                    @endforeach
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house-fill me-2"></i>Voir les logements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('maintenance-requests.*') ? 'active fw-bold' : '' }}" href="{{ route('maintenance-requests.index') }}">
                            <i class="bi bi-tools me-2"></i>Réclamations / Maintenance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('settings.*') ? 'active fw-bold' : '' }}" href="{{ route('settings.index') }}">
                            <i class="bi bi-gear me-2"></i>Paramètres de l’application
                        </a>
                    </li>
                </ul>

                <div class="mt-auto pt-3 border-top border-secondary">
                    <div class="small text-white mb-3 px-2 text-truncate">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover;">
                        @else
                            <i class="bi bi-person-circle text-info me-2"></i>
                        @endif
                        {{ auth()->user()->name }}
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100">
                            <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="client-content flex-grow-1">
            <header class="d-lg-none navbar navbar-dark bg-dark shadow-sm px-3">
                <a class="navbar-brand client-brand fw-bold text-white" href="{{ route('dashboard') }}">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo du site" class="client-brand-logo">
                    @else
                        <i class="bi bi-house-door-fill text-info"></i>
                    @endif
                    <span class="client-brand-name">GestionLoyer</span>
                </a>
                <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#clientSidebar" aria-controls="clientSidebar" aria-label="Ouvrir le menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </header>

            <main class="container-fluid px-3 px-lg-4 py-4">
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>