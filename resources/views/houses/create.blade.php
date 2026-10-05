<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Publier une maison - GestionLoyer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body p-4">
                        <h2 class="fw-bold mb-4">Publier une nouvelle maison</h2>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('houses.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Titre de l'annonce</label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="Ex: Magnifique villa moderne">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Prix ($ / mois)</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" required placeholder="Ex: 350">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Adresse / Quartier</label>
                                    <input type="text" class="form-control" id="address" name="address" required placeholder="Ex: Butembo, Matonge">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description détaillée</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Nombre de chambres, salon, cuisine..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Photo de la maison</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">Retour à l'accueil</a>
                                <button type="submit" class="btn btn-dark px-4">Publier la maison</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>