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
    </head>
    <body class="bg-light">

        <!-- Barre de navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/dashboard') }}">
                    <i class="bi bi-house-door-fill text-info"></i>
                    <span>Gestion<span class="text-info">Loyer</span></span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <!-- Liens de navigation principaux pour le Gestionnaire / Admin -->
                    <ul class="navbar-nav align-items-center gap-2">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'text-info fw-bold' : 'text-white' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('houses.*') ? 'text-info fw-bold' : 'text-white' }}" href="{{ route('houses.index') }}">
                                <i class="bi bi-house-fill"></i> Maisons
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('locataires.*') ? 'text-info fw-bold' : 'text-white' }}" href="{{ route('locataires.index') }}">
                                <i class="bi bi-people-fill"></i> Locataires
                            </a>
                        </li>

                        <!-- Lien Utilisateurs (Visible uniquement pour l'administrateur) -->
                        @if(auth()->user() && auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'text-info fw-bold' : 'text-white' }}" href="{{ route('users.index') }}">
                                    <i class="bi bi-person-badge-fill"></i> Utilisateurs
                                </a>
                            </li>
                        @endif
                    </ul>

                    <!-- Partie droite : Profil et Déconnexion -->
                    <ul class="navbar-nav align-items-center gap-3 mb-0">
                        <li class="nav-item text-white small">
                            <i class="bi bi-person-circle text-info"></i> {{ Auth::user()->name ?? 'Utilisateur' }}
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- En-tête de page optionnel -->
        @isset($header)
            <header class="bg-white shadow-sm mb-4 py-3">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Contenu principal -->
        <main class="container py-4">
            {{ $slot }}
        </main>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>