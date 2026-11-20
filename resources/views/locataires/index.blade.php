<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
                <i class="bi bi-person-badge-fill text-primary"></i> Gestion des Locataires
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('locataires.export.pdf') }}" target="_blank" rel="noopener" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Exporter en PDF
                </a>
                <a href="{{ route('locataires.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="bi bi-person-plus-fill"></i> Nouveau locataire
                </a>
            </div>
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
                                        <th>Nom, Postnom & Prénom</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locataires as $locataire)
                                        <tr>
                                            <td>
                                                <a href="{{ route('locataires.show', $locataire) }}" class="fw-bold text-decoration-none">
                                                    {{ implode(' ', array_filter([$locataire->nom, $locataire->postnom, $locataire->prenom])) }}
                                                </a>
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