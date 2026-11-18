<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>Contrats de bail
                </h2>
                <p class="text-muted mb-0">Consultez et gérez les contrats de location.</p>
            </div>
            <a href="{{ route('leases.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-circle me-1"></i> Nouveau contrat
            </a>
        </div>
    </x-slot>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary-subtle text-primary p-3"><i class="bi bi-files fs-4"></i></div>
                        <div>
                            <div class="text-muted small">Tous les contrats</div>
                            <div class="fs-4 fw-bold">{{ $totalCount }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success-subtle text-success p-3"><i class="bi bi-check-circle fs-4"></i></div>
                        <div>
                            <div class="text-muted small">Contrats actifs</div>
                            <div class="fs-4 fw-bold">{{ $activeCount }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-secondary-subtle text-secondary p-3"><i class="bi bi-archive fs-4"></i></div>
                        <div>
                            <div class="text-muted small">Contrats terminés</div>
                            <div class="fs-4 fw-bold">{{ $endedCount }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('leases.index') }}" class="row g-2 mb-4">
                    <div class="col-md">
                        <label for="q" class="visually-hidden">Rechercher</label>
                        <input type="search" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Rechercher un logement ou un locataire">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="visually-hidden">Statut</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="actif" @selected(request('status') === 'actif')>Actif</option>
                            <option value="termine" @selected(request('status') === 'termine')>Terminé</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search me-1"></i>Filtrer</button>
                        @if(request()->hasAny(['q', 'status']))
                            <a href="{{ route('leases.index') }}" class="btn btn-outline-secondary">Effacer</a>
                        @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Logement</th>
                                <th>Locataire</th>
                                <th>Période du contrat</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leases as $lease)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $lease->house?->title ?? 'Logement supprimé' }}</div>
                                        @if($lease->house)
                                            <div class="small text-muted">{{ $lease->house->address }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $lease->client?->name ?? 'Client supprimé' }}</div>
                                        @if($lease->client)
                                            <div class="small text-muted">{{ $lease->client->email }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span>{{ $lease->start_date->format('d/m/Y') }}</span>
                                        <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                        <span>{{ $lease->end_date->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        @if($lease->status === 'actif')
                                            <span class="badge rounded-pill text-bg-success">Actif</span>
                                        @else
                                            <span class="badge rounded-pill text-bg-secondary">Terminé</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('leases.edit', $lease) }}" class="btn btn-sm btn-outline-primary" title="Modifier" aria-label="Modifier le contrat">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('leases.destroy', $lease) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce contrat ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" aria-label="Supprimer le contrat">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-file-earmark-x fs-2 d-block mb-2"></i>
                                        Aucun contrat de bail ne correspond à votre recherche.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">{{ $leases->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
