<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
                <i class="bi bi-speedometer2 text-primary"></i> Tableau de Bord - Gestion Locative
            </h2>
            <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                <i class="bi bi-plus-lg"></i> Ajouter une maison
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <!-- Cartes de statistiques -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase small fw-bold opacity-75">Total Maisons</h6>
                                    <h2 class="fw-bold mb-0">0</h2>
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
                                    <h2 class="fw-bold mb-0">0</h2>
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
                                    <h2 class="fw-bold mb-0">0</h2>
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
                    <div class="alert alert-info py-3 mb-0">
                        <i class="bi bi-info-circle-fill"></i> Aucune maison enregistrée pour le moment. Vous pourrez bientôt les ajouter et les gérer ici !
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>