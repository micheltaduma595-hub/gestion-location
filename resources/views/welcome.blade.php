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
            background-color: #f8f9fa;
        }
        /* Section Héro Plein Écran avec image de fond et voile sombre */
        .hero-section {
            background: linear-gradient(rgba(11, 28, 60, 0.75), rgba(23, 56, 104, 0.75)), url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            min-height: 80vh;
            display: flex;
            align-items: center;
            position: relative;
            color: #fff;
            padding-top: 100px;
        }
        .navbar {
            background-color: transparent !important;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
        }
        /* Style professionnel pour les cartes de maisons */
        .house-card {
            background: #ffffff;
            color: #333;
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .house-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        /* Hauteur fixe et homogène pour toutes les images de maisons */
        .house-img-container {
            height: 210px;
            overflow: hidden;
            background-color: #eaeaea;
        }
        .house-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .house-card:hover .house-img-container img {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <!-- Barre de navigation transparente -->
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

                    @auth
                        <!-- Si l'utilisateur est connecté, on affiche un lien vers son tableau de bord -->
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-info rounded-pill px-4 fw-semibold text-dark shadow-sm">
                                <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
                            </a>
                        </li>
                    @else
                        <!-- Si c'est un visiteur, on affiche Inscription et Connexion -->
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
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Section Héro Plein Écran -->
    <header class="hero-section">
        <div class="container text-center py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold fs-6 shadow-sm">Immobilier & Location</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-4 text-white">Simplifiez la gestion de vos loyers</h1>
                    <p class="lead text-white-50 mx-auto" style="max-width: 700px;">
                        Trouvez ou publiez des maisons facilement. Les clients peuvent choisir leur logement, contacter directement les gestionnaires et suivre leurs échéances de baux en toute tranquillité.
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Section Catalogue des Maisons Disponibles -->
    <main class="container my-5 py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark"><i class="bi bi-houses text-primary me-2"></i> Nos Maisons Disponibles</h2>
            <p class="text-muted">Découvrez les derniers logements mis en location et trouvez celui qui vous correspond.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($houses as $house)
                <div class="col-md-4 col-sm-6">
                    <div class="house-card h-100 d-flex flex-column">
                        <!-- Conteneur d'image avec dimensions fixes et propres -->
                        <div class="house-img-container">
                            @if($house->image)
                                <img src="{{ asset('storage/' . $house->image) }}" alt="{{ $house->title }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?auto=format&fit=crop&w=600&q=80" alt="Maison par défaut">
                            @endif
                        </div>
                        
                        <div class="p-4 d-flex flex-column flex-grow-1">
                            <h4 class="fw-bold text-dark mb-2 fs-5">{{ $house->title }}</h4>
                            <p class="text-muted small mb-4"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $house->address }}</p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <span class="fw-bold text-success fs-5">{{ number_format($house->price, 2) }} $</span>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">/ mois</small>
                                </div>

                                <!-- Gestion dynamique du bouton selon l'authentification -->
                                @auth
                                    <form action="{{ route('houses.client.choisir', $house->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold shadow-sm">
                                            Choisir ce bien
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">Louer ce bien</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-4 bg-white shadow-sm rounded-4 d-inline-block border">
                        <i class="bi bi-house-slash fs-1 text-muted mb-2 d-block"></i>
                        <p class="text-muted mb-0">Aucune maison disponible pour le moment. Revenez un peu plus tard !</p>
                    </div>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Pied de page -->
    <footer class="bg-dark text-white text-center py-4 mt-auto border-top">
        <div class="container">
            <p class="mb-0 text-white-50 small">&copy; 2026 GestionLoyer - Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>