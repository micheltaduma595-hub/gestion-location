<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-tools text-primary me-2"></i>Réclamations / Maintenance
                </h2>
                <p class="text-muted mb-0">
                    {{ $isManager ? 'Consultez les demandes des locataires et mettez à jour leur suivi.' : 'Déclarez un problème et suivez le traitement de vos demandes.' }}
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @unless($isManager)
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Tableau de bord
                    </a>
                    <a href="{{ route('maintenance-requests.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Signaler un problème
                    </a>
                @endunless
            </div>
        </div>
    </x-slot>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-4">
            @foreach($statuses as $key => $label)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-circle {{ $key === 'ouverte' ? 'bg-warning-subtle text-warning' : ($key === 'en_cours' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success') }} p-3">
                                <i class="bi {{ $key === 'ouverte' ? 'bi-inbox' : ($key === 'en_cours' ? 'bi-wrench-adjustable' : 'bi-check-circle') }} fs-4"></i>
                            </div>
                            <div>
                                <div class="small text-muted">{{ $label }}</div>
                                <div class="h4 fw-bold mb-0">{{ $counts->get($key, 0) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($isManager)
            <form method="GET" action="{{ route('maintenance-requests.index') }}" class="row g-2 align-items-end mb-3">
                <div class="col-sm-6 col-md-4">
                    <label for="status" class="form-label">Filtrer par statut</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filtrer</button>
                    @if(request()->filled('status'))
                        <a href="{{ route('maintenance-requests.index') }}" class="btn btn-outline-secondary">Effacer</a>
                    @endif
                </div>
            </form>
        @endif

        <div class="row g-3">
            @forelse($requests as $maintenanceRequest)
                <div class="col-12">
                    <article class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <span class="badge {{ $maintenanceRequest->status === 'ouverte' ? 'text-bg-warning' : ($maintenanceRequest->status === 'en_cours' ? 'text-bg-primary' : 'text-bg-success') }}">
                                            {{ $statuses[$maintenanceRequest->status] }}
                                        </span>
                                        <span class="badge text-bg-light border">{{ $maintenanceRequest->category }}</span>
                                        <span class="small text-muted">Demande du {{ $maintenanceRequest->created_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    <h3 class="h5 fw-bold mb-1">{{ $maintenanceRequest->subject }}</h3>
                                    <div class="small text-muted mb-3">
                                        <i class="bi bi-house-door me-1"></i>{{ $maintenanceRequest->house?->title ?? 'Logement non précisé' }}
                                        @if($isManager)
                                            <span class="mx-1">·</span><i class="bi bi-person me-1"></i>{{ $maintenanceRequest->user?->name ?? 'Compte supprimé' }}
                                            @if($maintenanceRequest->user?->email)
                                                <span class="mx-1">·</span>{{ $maintenanceRequest->user->email }}
                                            @endif
                                        @endif
                                    </div>
                                    <p class="mb-0 text-break">{{ $maintenanceRequest->description }}</p>
                                    @if($maintenanceRequest->manager_response)
                                        <div class="alert alert-light border mt-3 mb-0">
                                            <div class="fw-semibold"><i class="bi bi-chat-left-text me-1"></i>Réponse du gestionnaire</div>
                                            <div>{{ $maintenanceRequest->manager_response }}</div>
                                        </div>
                                    @endif
                                </div>

                                @if($isManager)
                                    <form action="{{ route('maintenance-requests.update', $maintenanceRequest) }}" method="POST" class="border rounded-3 bg-light p-3" style="min-width: min(100%, 300px)">
                                        @csrf
                                        @method('PATCH')
                                        <label for="status-{{ $maintenanceRequest->id }}" class="form-label fw-semibold">Mettre à jour le suivi</label>
                                        <select id="status-{{ $maintenanceRequest->id }}" name="status" class="form-select mb-2" required>
                                            @foreach($statuses as $key => $label)
                                                <option value="{{ $key }}" @selected($maintenanceRequest->status === $key)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <label for="response-{{ $maintenanceRequest->id }}" class="form-label small">Réponse au locataire</label>
                                        <textarea id="response-{{ $maintenanceRequest->id }}" name="manager_response" rows="3" maxlength="5000" class="form-control mb-2" placeholder="Ajouter une réponse ou un commentaire">{{ old('manager_response', $maintenanceRequest->manager_response) }}</textarea>
                                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-save me-1"></i>Enregistrer</button>
                                    </form>
                                    @if($maintenanceRequest->status === 'resolue')
                                        <form action="{{ route('maintenance-requests.destroy', $maintenanceRequest) }}" method="POST" class="mt-2" onsubmit="return confirm('Supprimer définitivement cette réclamation résolue ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash me-1"></i>Supprimer la réclamation</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-clipboard-check fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">
                                {{ $isManager ? 'Aucune réclamation ne correspond à ce filtre.' : 'Vous n’avez pas encore envoyé de réclamation.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $requests->links() }}</div>
    </div>
</x-app-layout>
