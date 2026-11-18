<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-gear text-primary me-2"></i>Paramètres de l’application
            </h2>
            <p class="text-muted mb-0">
                {{ $isAdministrator ? 'Gérez votre profil et l’apparence de votre espace de gestion locative.' : 'Gérez les informations et la sécurité de votre compte.' }}
            </p>
        </div>
    </x-slot>

    <div class="container-fluid">
        <div class="row g-4">
            @if($isAdministrator)
            <div class="col-md-6 col-xl-4">
                <section class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="rounded-circle bg-primary-subtle text-primary p-3"><i class="bi bi-image fs-4"></i></span>
                            <div>
                                <h3 class="h5 fw-bold mb-1">Logo du site</h3>
                                <div class="small text-muted">{{ $siteLogo ? 'Un logo personnalisé est actif.' : 'Logo standard GestionLoyer.' }}</div>
                            </div>
                        </div>
                        <div class="border rounded-3 bg-light d-flex align-items-center justify-content-center p-3 mb-3" style="min-height: 120px">
                            @if($siteLogo)
                                <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo actuel du site" style="max-width: 100%; max-height: 90px; object-fit: contain;">
                            @else
                                <div class="bg-dark text-white rounded-3 px-3 py-2 fw-bold">
                                    <i class="bi bi-house-door-fill text-info me-1"></i>Gestion<span class="text-info">Loyer</span>
                                </div>
                            @endif
                        </div>
                        <p class="text-muted small flex-grow-1">Personnalisez le logo affiché dans l’espace de gestion et sur le site.</p>
                        <a href="{{ route('site-logo.edit') }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square me-1"></i>Gérer le logo
                        </a>
                    </div>
                </section>
            </div>

            <div class="{{ $isAdministrator ? 'col-md-6 col-xl-4' : 'col-md-8 col-xl-6' }}">
                <section class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="rounded-circle bg-info-subtle text-info p-3"><i class="bi bi-images fs-4"></i></span>
                            <div>
                                <h3 class="h5 fw-bold mb-1">Images des pages</h3>
                                <div class="small text-muted">{{ $customImagesCount }} image(s) personnalisée(s) sur {{ $totalImagesCount }}</div>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="rounded-3 bg-light border p-3 text-center h-100">
                                    <i class="bi bi-briefcase fs-3 text-primary d-block mb-1"></i>
                                    <span class="small fw-semibold">Services</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rounded-3 bg-light border p-3 text-center h-100">
                                    <i class="bi bi-info-circle fs-3 text-primary d-block mb-1"></i>
                                    <span class="small fw-semibold">À propos</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1">Mettez à jour les images présentées sur les pages publiques du site.</p>
                        <a href="{{ route('site-page-images.edit') }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square me-1"></i>Gérer les images
                        </a>
                    </div>
                </section>
            </div>
            @endif

            <div class="col-md-6 col-xl-4">
                <section class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if(auth()->user()->profile_photo_path)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Photo de profil" class="rounded-circle border" style="width: 64px; height: 64px; object-fit: cover;">
                            @else
                                <span class="rounded-circle bg-success-subtle text-success p-3"><i class="bi bi-person-gear fs-4"></i></span>
                            @endif
                            <div>
                                <h3 class="h5 fw-bold mb-1">Mon compte</h3>
                                <div class="small text-muted">{{ auth()->user()->name }}</div>
                            </div>
                        </div>
                        <div class="rounded-3 bg-light border p-3 mb-3">
                            <div class="small text-muted">Adresse e-mail</div>
                            <div class="fw-semibold text-break">{{ auth()->user()->email }}</div>
                            @if(auth()->user()->phone)
                                <div class="small text-muted mt-2">Téléphone : {{ auth()->user()->phone }}</div>
                            @endif
                            <div class="small text-muted mt-2">Rôle : {{ ['admin' => 'Administrateur', 'gestionnaire' => 'Gestionnaire', 'client' => 'Client', 'locataire' => 'Locataire'][auth()->user()->role] ?? auth()->user()->role }}</div>
                        </div>
                        <p class="text-muted small flex-grow-1">Modifiez vos informations personnelles, votre photo et les paramètres de sécurité de votre compte.</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                            <i class="bi bi-person-lines-fill me-1"></i>Gérer mon profil
                        </a>
                    </div>
                </section>
            </div>
        </div>

        @if($isAdministrator)
            <div class="alert alert-light border mt-4 mb-0">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Les rapports financiers et statistiques sont accessibles depuis
                <a href="{{ route('reports.index') }}" class="alert-link">Rapports et statistiques</a>.
            </div>
        @endif
    </div>
</x-app-layout>
