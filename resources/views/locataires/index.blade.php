<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
                <i class="bi bi-person-badge-fill text-primary"></i> Gestion des Locataires
            </h2>
            <a href="{{ route('locataires.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                <i class="bi bi-person-plus-fill"></i> Nouveau locataire
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-secondary">
                        <i class="bi bi-list-nested"></i> Liste des locataires enregistrés
                    </h5>

                    @if(isset($locataires) && $locataires->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom & Prénom</th>
                                        <th>Téléphone</th>
                                        <th>Profession</th>
                                        <th>Maison Attribuée</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locataires as $locataire)
                                        <tr>
                                            <td>
                                                <strong>{{ $locataire->nom }} {{ $locataire->prenom }}</strong><br>
                                                <small class="text-muted">{{ $locataire->email ?? 'Pas d\'email' }}</small>
                                            </td>
                                            <td>{{ $locataire->telephone }}</td>
                                            <td>{{ $locataire->profession ?? 'Non renseignée' }}</td>
                                            <td>
                                                @if($locataire->maisons->count() > 0)
                                                    @foreach($locataire->maisons as $maison)
                                                        <span class="badge bg-success mb-1">
                                                            <i class="bi bi-house-door-fill"></i> {{ $maison->title }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-warning text-dark">Aucune maison</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('locataires.attribuer.form', $locataire->id) }}" class="btn btn-sm btn-outline-primary" title="Attribuer une maison">
                                                    <i class="bi bi-house-add"></i>
                                                </a>
                                                <a href="{{ route('locataires.edit', $locataire->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('locataires.destroy', $locataire->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce locataire ?');">
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
                            <i class="bi bi-info-circle-fill"></i> Aucun locataire enregistré pour le moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>