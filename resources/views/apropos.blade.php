<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Loyer - À propos</title>
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
        
        /* Carte de contenu élégante et moderne */
        .about-card {
            background: #ffffff;
            color: #333;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .about-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1.5rem auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div class="bg-primary text-white p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-house-door-fill fs-5"></i>
                </div>
                <span>Gestion<span class="text-info">Loyer</span></span>
            </a>
            <div class="ms-auto">
                <a href="{{ route('home') }}" class="btn btn-outline-light rounded-pill px-4 shadow-sm fw-semibold"><i class="bi bi-arrow-left me-1"></i> Retour à l'accueil</a>
            </div>
        </div>
    </nav>

    <div class="container my-5 py-4 flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="row justify-content-center w-100">
            <div class="col-lg-8 col-md-10">
                <div class="about-card p-5 text-center">
                    <div class="about-icon bg-primary-subtle text-primary">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-3">Notre Vision</span>
                    <h2 class="fw-bold mb-4 text-dark display-6">À propos de nous</h2>
                    <p class="text-secondary fs-6 lh-lg mb-0 text-start px-md-4">
                        Cette application web est conçue pour moderniser et fluidifier les relations entre propriétaires/gestionnaires immobiliers et locataires. Notre mission est d'apporter transparence, rapidité et efficacité dans la gestion locative quotidienne grâce à des outils numériques adaptés.
                    </p>
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