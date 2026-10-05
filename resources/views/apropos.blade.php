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
        }
        .navbar { background-color: transparent !important; }
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
                <a href="{{ route('home') }}" class="btn btn-outline-light rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i> Retour à l'accueil</a>
            </div>
        </div>
    </nav>

    <div class="container my-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 bg-white text-dark p-5 rounded-4 shadow">
                <h2 class="fw-bold mb-3 text-primary">À propos de nous</h2>
                <p class="text-secondary fs-6 lh-lg">
                    Cette application web est conçue pour moderniser et fluidifier les relations entre propriétaires/gestionnaires immobiliers et locataires. Notre mission est d'apporter transparence, rapidité et efficacité dans la gestion locative quotidienne grâce à des outils numériques adaptés.
                </p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>