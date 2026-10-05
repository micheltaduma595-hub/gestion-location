<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $house->title }} - GestionLoyer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="mb-4">
            <a href="{{ route('welcome') }}" class="btn btn-outline-dark btn-sm">&larr; Retour à l'accueil</a>
        </div>

        <div class="row g-5">
            <div class="col-md-7">
                @if($house->image)
                    <img src="{{ asset('storage/' . $house->image) }}" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 400px; object-fit: cover;" alt="Maison">
                @else
                    <div class="bg-secondary text-white rounded-4 d-flex align-items-center justify-content-center" style="height: 350px;">
                        <span>Aucune image disponible</span>
                    </div>
                @endif
                <div class="mt-4 bg-white p-4 rounded-4 shadow-sm border">
                    <h3 class="fw-bold">Description</h3>
                    <p class="text-secondary mt-2">{{ $house->description }}</p>
                </div>
            </div>

            <div class="col-md-5">
                <div class="bg-white p-4 rounded-4 shadow-sm border">
                    <h2 class="fw-bold text-dark">{{ $house->title }}</h2>
                    <p class="text-primary fw-bold fs-4 my-2">{{ number_format($house->price, 2) }} $ / mois</p>
                    <p class="text-secondary">📍 <strong>Adresse :</strong> {{ $house->address }}</p>
                    <span class="badge bg-success mb-4">Statut : {{ ucfirst($house->status) }}</span>

                    <hr>

                    <h5 class="fw-bold mb-3">Contact Direct du Gestionnaire</h5>
                    <p class="mb-1"><strong>Nom :</strong> {{ $house->user->name ?? 'Non spécifié' }}</p>
                    <p class="mb-1"><strong>Email :</strong> <a href="mailto:{{ $house->user->email ?? '' }}">{{ $house->user->email ?? 'N/A' }}</a></p>
                    <p class="mb-4"><strong>Téléphone :</strong> {{ $house->user->phone ?? 'Non renseigné' }}</p>

                    <div class="d-grid gap-2">
                        <a href="tel:{{ $house->user->phone ?? '' }}" class="btn btn-dark">Appeler le gestionnaire</a>
                        <a href="mailto:{{ $house->user->email ?? '' }}" class="btn btn-outline-primary">Envoyer un Email</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>