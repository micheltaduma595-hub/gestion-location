<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Gestion Loyer - Tableau de bord</title>

        <!-- Bootstrap 5 CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('css/compact-ui.css') }}">
        <style>
            .app-sidebar {
                --bs-offcanvas-width: 240px;
                background-color: #212529 !important;
            }

            .app-sidebar .navbar-brand,
            .app-sidebar .app-brand-name {
                color: #fff !important;
            }

            .app-sidebar .app-brand-accent {
                color: #0dcaf0 !important;
            }

            .app-brand-logo {
                width: 48px;
                height: 48px;
                object-fit: cover;
                border-radius: 0.5rem;
            }

            .app-content {
                min-width: 0;
            }

            .app-sidebar .nav-link {
                color: #adb5bd;
                border-radius: 0.5rem;
                padding: 0.55rem 0.7rem;
            }

            .app-sidebar .nav-link:hover,
            .app-sidebar .nav-link.active {
                color: #fff;
                background-color: #343a40;
            }

            @media (min-width: 992px) {
                .app-sidebar {
                    flex: 0 0 240px !important;
                    width: 240px !important;
                }
            }
        </style>
    </head>
    <body class="bg-light">
        <div class="d-lg-flex min-vh-100">
            <nav class="app-sidebar offcanvas-lg offcanvas-start d-lg-flex flex-column p-3 shadow"
                tabindex="-1" id="appSidebar" aria-labelledby="appSidebarLabel">
                <div class="offcanvas-header d-lg-none px-0 pt-0">
                    <a class="navbar-brand fw-bold" href="{{ url('/dashboard') }}" id="appSidebarLabel">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo du site" class="app-brand-logo">
                        @else
                            <i class="bi bi-house-door-fill text-info"></i>
                            <span class="app-brand-name">Gestion</span><span class="app-brand-accent">Loyer</span>
                        @endif
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
                </div>

                <div class="d-none d-lg-flex align-items-center mb-4 px-2 pt-2">
                    <a class="fw-bold fs-5 text-decoration-none" href="{{ url('/dashboard') }}">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo du site" class="app-brand-logo">
                        @else
                            <i class="bi bi-house-door-fill text-info me-2"></i>
                            <span class="app-brand-name">Gestion</span><span class="app-brand-accent">Loyer</span>
                        @endif
                    </a>
                </div>

                <div class="offcanvas-body d-flex flex-column p-0">
                    <div class="text-uppercase text-secondary small fw-bold px-2 mb-2">Navigation principale</div>
                    <ul class="navbar-nav flex-column gap-2 mb-4">
                        @if(auth()->user() && auth()->user()->role === 'gestionnaire')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                                    <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('houses.*') ? 'active fw-bold' : '' }}" href="{{ route('houses.index') }}" @if(request()->routeIs('houses.*')) aria-current="page" @endif>
                                    <i class="bi bi-house-fill me-2"></i> Maisons
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('locataires.*') ? 'active fw-bold' : '' }}" href="{{ route('locataires.index') }}" @if(request()->routeIs('locataires.*')) aria-current="page" @endif>
                                    <i class="bi bi-people-fill me-2"></i> Locataires
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('members.*') ? 'active fw-bold' : '' }}" href="{{ route('members.index') }}" @if(request()->routeIs('members.*')) aria-current="page" @endif>
                                    <i class="bi bi-person-vcard me-2"></i> Membres
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('payments.*') ? 'active fw-bold' : '' }}" href="{{ route('payments.index') }}" @if(request()->routeIs('payments.*')) aria-current="page" @endif>
                                    <i class="bi bi-cash-stack me-2"></i> Suivi des paiements
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('annual-report.*') ? 'active fw-bold' : '' }}" href="{{ route('annual-report.index') }}" @if(request()->routeIs('annual-report.*')) aria-current="page" @endif>
                                    <i class="bi bi-calendar2-range me-2"></i> Registre annuel
                                </a>
                            </li>
                        @endif

                        @if(auth()->user() && in_array(auth()->user()->role, ['client', 'locataire'], true))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard', 'client.dashboard.section') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard', 'client.dashboard.section')) aria-current="page" @endif>
                                    <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
                                </a>
                            </li>
                        @endif

                        @if(auth()->user() && auth()->user()->role === 'gestionnaire')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active fw-bold' : '' }}" href="{{ route('reports.index') }}" @if(request()->routeIs('reports.*')) aria-current="page" @endif>
                                    <i class="bi bi-bar-chart-line me-2"></i> Rapports et statistiques
                                </a>
                            </li>
                        @endif

                        @if(auth()->user() && in_array(auth()->user()->role, ['gestionnaire', 'client', 'locataire'], true))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('maintenance-requests.*') ? 'active fw-bold' : '' }}" href="{{ route('maintenance-requests.index') }}" @if(request()->routeIs('maintenance-requests.*')) aria-current="page" @endif>
                                    <i class="bi bi-tools me-2"></i> Réclamations / Maintenance
                                </a>
                            </li>
                        @endif

                        @if(auth()->user() && auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active fw-bold' : '' }}" href="{{ route('users.index') }}" @if(request()->routeIs('users.*')) aria-current="page" @endif>
                                    <i class="bi bi-person-badge-fill me-2"></i> Utilisateurs
                                </a>
                            </li>
                        @endif

                        @if(auth()->user() && auth()->user()->role === 'gestionnaire')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('leases.*') ? 'active fw-bold' : '' }}" href="{{ route('leases.index') }}" @if(request()->routeIs('leases.*')) aria-current="page" @endif>
                                    <i class="bi bi-file-earmark-text me-2"></i> Contrats de bail
                                </a>
                            </li>
                        @endif

                        @if(auth()->user())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.*', 'site-page-images.*', 'site-logo.*') ? 'active fw-bold' : '' }}" href="{{ route('settings.index') }}" @if(request()->routeIs('settings.*', 'site-page-images.*', 'site-logo.*')) aria-current="page" @endif>
                                    <i class="bi bi-gear me-2"></i> Paramètres de l’application
                                </a>
                            </li>
                        @endif
                    </ul>

                    @auth
                        <div class="mt-auto pt-3 border-top border-secondary">
                            <div class="small text-white mb-3 px-2 text-truncate">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle text-info me-2"></i>
                                @endif
                                {{ Auth::user()->name ?? 'Utilisateur' }}
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100">
                                    <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </nav>

            <div class="app-content flex-grow-1">
                <header class="d-lg-none navbar navbar-dark bg-dark shadow-sm px-3">
                    <a class="navbar-brand fw-bold text-white" href="{{ url('/dashboard') }}">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo du site" class="app-brand-logo">
                        @else
                            <i class="bi bi-house-door-fill text-info"></i>
                            Gestion<span class="text-info">Loyer</span>
                        @endif
                    </a>
                    <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar" aria-label="Ouvrir le menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </header>

                @isset($header)
                    <header class="bg-white shadow-sm mb-4 py-3">
                        <div class="container-fluid px-3 px-lg-4">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="container-fluid px-3 px-lg-4 py-4">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>