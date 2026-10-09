<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
                <i class="bi bi-speedometer2 text-primary"></i> Tableau de Bord - Gestion Locative
            </h2>
            <a href="{{ route('houses.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                <i class="bi bi-plus-lg"></i> Ajouter une maison
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Cartes de statistiques -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase small fw-bold opacity-75">Total Maisons</h6>
                                    <h2 class="fw-bold mb-0">{{ $totalHouses ?? 0 }}</h2>
                                </div>
                                <div class="fs-1 opacity-50"><i class="bi bi-houses"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase small fw-bold opacity-75">Disponibles</h6>
                                    <h2 class="fw-bold mb-0">{{ $availableHouses ?? 0 }}</h2>
                                </div>
                                <div class="fs-1 opacity-50"><i class="bi bi-house-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase small fw-bold opacity-75">Locataires</h6>
                                    <h2 class="fw-bold mb-0">{{ $totalTenants ?? 0 }}</h2>
                                </div>
                                <div class="fs-1 opacity-50"><i class="bi bi-people"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section de la liste des maisons -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-secondary">
                        <i class="bi bi-house-door"></i> Vos dernières maisons publiées
                    </h5>

                    @if(isset($houses) && $houses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Titre</th>
                                        <th>Adresse</th>
                                        <th>Prix</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($houses as $house)
                                        <tr>
                                            <td>
                                                @if($house->image)
                                                    <img src="{{ asset('storage/' . $house->image) }}" alt="House Image" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="badge bg-secondary">Aucune</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $house->title }}</strong></td>
                                            <td>{{ $house->address }}</td>
                                            <td><span class="text-success fw-bold">{{ $house->price }} $</span> / mois</td>
                                            <td>
                                                @if($house->status == 'disponible')
                                                    <span class="badge bg-success">Disponible</span>
                                                @else
                                                    <span class="badge bg-danger">Louée</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('houses.edit', $house->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('houses.destroy', $house->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette maison ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info py-3 mb-0 text-center">
                            <i class="bi bi-info-circle-fill"></i> Aucune maison enregistrée pour le moment. Vous pouvez en ajouter une en cliquant sur le bouton <strong>"+ Ajouter une maison"</strong> en haut à droite !
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>