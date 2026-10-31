<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier une Maison - GestionLoyer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold text-primary mb-0"><i class="bi bi-house-gear me-2"></i> Modifier la Maison</h3>
                        <a href="{{ route('houses.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-left me-1"></i> Retour
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('houses.update', $house->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nom ou Numéro de la Maison</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $house->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label fw-semibold">Emplacement / Adresse</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $house->location) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label fw-semibold">Loyer Mensuel ($)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $house->price) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Statut</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="disponible" {{ old('status', $house->status) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="louee" {{ old('status', $house->status) == 'louee' ? 'selected' : '' }}>Louée</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                                <i class="bi bi-check-circle me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>