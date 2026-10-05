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

    <div class="container my-5 py-4">
        <h1 class="text-center mb-5 fw-bold">Nos Services</h1>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 bg-white text-dark rounded-4 shadow h-100">
                    <div class="text-primary fs-3 mb-2"><i class="bi bi-house-add"></i></div>
                    <h4 class="fw-bold">Publication & Choix</h4>
                    <p class="text-secondary mt-2">Les gestionnaires publient les maisons disponibles. Les clients peuvent les consulter, les vérifier et effectuer une réservation en un clic.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white text-dark rounded-4 shadow h-100">
                    <div class="text-primary fs-3 mb-2"><i class="bi bi-headset"></i></div>
                    <h4 class="fw-bold">Contact Direct</h4>
                    <p class="text-secondary mt-2">Dès qu'un choix est effectué, le gestionnaire est averti et le client obtient immédiatement ses coordonnées (téléphone et email).</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white text-dark rounded-4 shadow h-100">
                    <div class="text-primary fs-3 mb-2"><i class="bi bi-bell"></i></div>
                    <h4 class="fw-bold">Alertes d'Échéance</h4>
                    <p class="text-secondary mt-2">Suivi rigoureux des paiements de loyer avec des alertes automatiques envoyées au locataire 3 jours avant et après l'échéance du bail.</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>