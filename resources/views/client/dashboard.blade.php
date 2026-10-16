<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Espace Locataire - GestionLoyer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <!-- Navbar client -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Gestion<span class="text-info">Loyer</span> - Espace Locataire</a>
            <div class="ms-auto">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2>Bienvenue, {{ auth()->user()->name }}</h2>
                <p class="text-muted">Voici le rapport de votre location.</p>
            </div>
        </div>

        @if(isset($house) && $house)
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 p-4">
                        <h4 class="fw-bold text-primary mb-3"><i class="bi bi-house-check me-2"></i> Ma Maison Attitrée</h4>
                        <p><strong>Titre :</strong> {{ $house->title }}</p>
                        <p><strong>Adresse :</strong> {{ $house->address }}</p>
                        <p><strong>Loyer mensuel :</strong> <span class="text-success fw-bold">{{ number_format($house->price, 2) }} $</span></p>
                    </div>
                </div>
            </div>
        @else
            <!-- Si le locataire n'a pas encore de maison assignée (rapport vide) -->
            <div class="alert alert-info text-center p-5 rounded-4 shadow-sm">
                <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                <h4 class="alert-heading fw-bold">Votre rapport est vide</h4>
                <p class="mb-3">Vous n'avez pas encore de maison ou de loyer attribué pour le moment.</p>
                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 fw-semibold">Voir les maisons disponibles</a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>