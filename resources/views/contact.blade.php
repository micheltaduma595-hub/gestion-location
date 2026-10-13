<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Loyer - Contact</title>
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
        /* Navbar transparente positionnée en haut */
        .navbar {
            background-color: transparent !important;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
        }
        
        /* Carte de formulaire élégante */
        .contact-card {
            background: #ffffff;
            color: #333;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            border-color: #0d6efd;
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
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('services') }}"><i class="bi bi-gear me-1"></i> Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('apropos') }}"><i class="bi bi-info-circle me-1"></i> À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active px-3" href="{{ route('contact') }}"><i class="bi bi-envelope me-1"></i> Contact</a>
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

    <!-- Contenu Principal -->
    <div class="container my-5 py-4 flex-grow-1" style="margin-top: 50px;">
        <div class="text-center mb-4 pt-4">
            <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">Support & Information</span>
            <h1 class="display-5 fw-bold text-white">Contactez-nous</h1>
            <p class="text-white-50 mx-auto mt-2" style="max-width: 600px;">
                Une question, une suggestion ou besoin d'assistance ? Envoyez-nous un message et nous vous répondrons dans les plus brefs délais.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="contact-card p-4 p-md-5">
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-dark">Nom complet</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control border-start-0" id="name" name="name" placeholder="Votre nom complet" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-dark">Adresse Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="nom@exemple.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label fw-semibold text-dark">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Écrivez votre message ici..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-send-fill"></i> Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="text-white text-center py-4 mt-auto border-top border-secondary opacity-75">
        <div class="container">
            <p class="mb-0 text-white-50 small">&copy; 2026 GestionLoyer - Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>