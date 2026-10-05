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
        <h2 class="text-center mb-4 fw-bold">Contactez-nous</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 bg-white text-dark p-5 rounded-4 shadow">
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nom complet</label>
                        <input type="text" class="form-control" id="name" placeholder="Votre nom">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Adresse Email</label>
                        <input type="email" class="form-control" id="email" placeholder="nom@exemple.com">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" id="message" rows="4" placeholder="Votre message..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill">Envoyer le message</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>