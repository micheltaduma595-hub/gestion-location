<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Loyer - Accueil</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, rgba(11, 28, 60, 0.9), rgba(23, 56, 104, 0.9)), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            color: #fff;
        }
        .navbar {
            background-color: transparent !important;
        }
        .hero-card {
            background: #ffffff;
            color: #333;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }
    </style>
</head>
<body>

    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div class="bg-primary text-white p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-house-door-fill fs-5"></i>
                </div>
                <span>Gestion<span class="text-info">Loyer</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link text-white active px-3" href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('services') }}"><i class="bi bi-gear me-1"></i> Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('apropos') }}"><i class="bi bi-info-circle me-1"></i> À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('contact') }}"><i class="bi bi-envelope me-1"></i> Contact</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('register') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold shadow-sm">
                                <i class="bi bi-person-plus me-1"></i> S'inscrire
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Section Héro (Carte épurée avec texte et photo de maison) -->
    <header class="container my-5 py-4">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-10">
                <div class="hero-card row g-0">
                    <!-- Colonne Texte -->
                    <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold">Immobilier & Location</span>
                        </div>
                        <h1 class="fw-bold mb-3 text-dark fs-2">Simplifiez la gestion de vos loyers</h1>
                        <p class="text-secondary mb-4">
                            Trouvez ou publiez des maisons facilement. Les clients peuvent choisir leur logement, contacter directement les gestionnaires et suivre leurs échéances de baux en toute tranquillité.
                        </p>
                        <div class="d-flex gap-2 flex-wrap">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold shadow-sm">
                                    <i class="bi bi-person-plus me-1"></i> S'inscrire
                                </a>
                            @endif
                            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                            </a>
                            <a href="{{ route('services') }}" class="btn btn-outline-dark rounded-pill px-4 fw-semibold">
                                Explorer les services
                            </a>
                        </div>
                    </div>
                    <!-- Colonne Image de maison -->
                    <div class="col-md-6 position-relative">
                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800&q=80" alt="Maison moderne" class="w-100 h-100 object-fit-cover" style="min-height: 380px;">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Pied de page -->
    <footer class="text-white text-center py-4 mt-auto border-top border-secondary opacity-75">
        <div class="container">
            <p class="mb-0 text-white-50 small">&copy; 2026 GestionLoyer - Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>