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
        
        /* Style raffiné pour les cartes de services */
        .service-card {
            background: #ffffff;
            color: #333;
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }
        .icon-box {
            width: 65px;
            height: 65px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            margin-bottom: 1.2rem;
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

    <div class="container my-5 py-4 flex-grow-1">
        <div class="text-center mb-5">
            <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">Prestations</span>
            <h1 class="display-5 fw-bold text-white">Nos Services</h1>
            <p class="text-white-50 mx-auto mt-2" style="max-width: 600px;">
                Des fonctionnalités pensées pour simplifier la gestion quotidienne de vos biens immobiliers et locations.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Service 1 -->
            <div class="col-md-4">
                <div class="service-card p-4 d-flex flex-column h-100">
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-house-add"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Publication & Choix</h4>
                    <p class="text-secondary small lh-base mt-auto mb-0">Les gestionnaires publient les maisons disponibles. Les clients peuvent les consulter, les vérifier et effectuer une réservation en un clic.</p>
                </div>
            </div>

            <!-- Service 2 -->
            <div class="col-md-4">
                <div class="service-card p-4 d-flex flex-column h-100">
                    <div class="icon-box bg-success-subtle text-success">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Contact Direct</h4>
                    <p class="text-secondary small lh-base mt-auto mb-0">Dès qu'un choix est effectué, le gestionnaire est averti et le client obtient immédiatement ses coordonnées (téléphone et email).</p>
                </div>
            </div>

            <!-- Service 3 -->
            <div class="col-md-4">
                <div class="service-card p-4 d-flex flex-column h-100">
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-bell"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Alertes d'Échéance</h4>
                    <p class="text-secondary small lh-base mt-auto mb-0">Suivi rigoureux des paiements de loyer avec des alertes automatiques envoyées au locataire 3 jours avant et après l'échéance du bail.</p>
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