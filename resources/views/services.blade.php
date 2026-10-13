<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Loyer - Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0b1c3c 0%, #173868 50%, #1e4b87 100%);
            min-height: 100vh;
            color: #fff;
            display: flex;
            flex-direction: column;
        }
        .navbar { background-color: transparent !important; }
        
        /* Style des cartes articles avec images de fond et texte superposé */
        .service-article-card {
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 340px;
            position: relative;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
        }

        .service-article-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        /* Voile sombre pour que le texte soit parfaitement lisible sur l'image */
        .card-overlay {
            background: linear-gradient(to top, rgba(11, 28, 60, 0.95) 25%, rgba(11, 28, 60, 0.3) 90%);
            width: 100%;
            padding: 2rem;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Barre de navigation transparente harmonisée -->
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
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active px-3" href="{{ route('services') }}"><i class="bi bi-gear me-1"></i> Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('apropos') }}"><i class="bi bi-info-circle me-1"></i> À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('contact') }}"><i class="bi bi-envelope me-1"></i> Contact</a>
                    </li>

                    @auth
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-info rounded-pill px-4 fw-semibold text-dark shadow-sm">
                                <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
                            </a>
                        </li>
                    @else
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

    <div class="container my-5 py-4 flex-grow-1">
        <div class="text-center mb-5">
            <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">Prestations</span>
            <h1 class="display-5 fw-bold text-white">Nos Services</h1>
            <p class="text-white-50 mx-auto mt-2" style="max-width: 600px;">
                Des fonctionnalités pensées pour simplifier la gestion quotidienne de vos biens immobiliers et locations.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Article 1 : Publication & Choix -->
            <div class="col-md-4">
                <div class="service-article-card" style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80');">
                    <div class="card-overlay">
                        <div class="text-info mb-1"><i class="bi bi-house-add fs-4"></i></div>
                        <h4 class="fw-bold text-white mb-2 fs-5">Publication & Choix</h4>
                        <p class="text-white-50 small mb-0 lh-base">Les gestionnaires publient les maisons disponibles. Les clients peuvent les consulter, les vérifier et effectuer une réservation en un clic.</p>
                    </div>
                </div>
            </div>

            <!-- Article 2 : Contact Direct -->
            <div class="col-md-4">
                <div class="service-article-card" style="background-image: url('https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=800&q=80');">
                    <div class="card-overlay">
                        <div class="text-success mb-1"><i class="bi bi-headset fs-4"></i></div>
                        <h4 class="fw-bold text-white mb-2 fs-5">Contact Direct</h4>
                        <p class="text-white-50 small mb-0 lh-base">Dès qu'un choix est effectué, le gestionnaire est averti et le client obtient immédiatement ses coordonnées (téléphone et email).</p>
                    </div>
                </div>
            </div>

            <!-- Article 3 : Alertes d'Échéance -->
            <div class="col-md-4">
                <div class="service-article-card" style="background-image: url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80');">
                    <div class="card-overlay">
                        <div class="text-warning mb-1"><i class="bi bi-bell fs-4"></i></div>
                        <h4 class="fw-bold text-white mb-2 fs-5">Alertes d'Échéance</h4>
                        <p class="text-white-50 small mb-0 lh-base">Suivi rigoureux des paiements de loyer avec des alertes automatiques envoyées au locataire 3 jours avant et après l'échéance du bail.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-white text-center py-4 mt-auto border-top border-secondary opacity-75">
        <div class="container">
            <p class="mb-0 text-white-50 small">&copy; 2026 GestionLoyer - Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>