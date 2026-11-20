<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
                <i class="bi bi-person-vcard-fill text-primary"></i> Informations du locataire
            </h2>
            <a href="{{ route('locataires.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="container">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4">{{ implode(' ', array_filter([$locataire->nom, $locataire->postnom, $locataire->prenom])) }}</h3>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nom</dt>
                    <dd class="col-sm-8">{{ $locataire->nom }}</dd>

                    <dt class="col-sm-4">Postnom</dt>
                    <dd class="col-sm-8">{{ $locataire->postnom ?: 'Non renseigné' }}</dd>

                    <dt class="col-sm-4">Prénom</dt>
                    <dd class="col-sm-8">{{ $locataire->prenom }}</dd>

                    <dt class="col-sm-4">Adresse e-mail</dt>
                    <dd class="col-sm-8">{{ $locataire->email ?: 'Non renseignée' }}</dd>

                    <dt class="col-sm-4">Téléphone</dt>
                    <dd class="col-sm-8">{{ $locataire->telephone ?: 'Non renseigné' }}</dd>

                    <dt class="col-sm-4">Profession</dt>
                    <dd class="col-sm-8">{{ $locataire->profession ?: 'Non renseignée' }}</dd>

                    <dt class="col-sm-4">Date d'inscription</dt>
                    <dd class="col-sm-8">{{ ($locataire->user?->created_at ?? $locataire->created_at)?->format('d/m/Y') ?? 'Inconnue' }}</dd>

                    <dt class="col-sm-4">Maisons attribuées</dt>
                    <dd class="col-sm-8">
                        @forelse($locataire->maisons as $maison)
                            <span class="badge text-bg-success me-1">{{ $maison->title }}</span>
                        @empty
                            Aucune maison
                        @endforelse
                    </dd>
                </dl>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('locataires.edit', $locataire) }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-pencil-square me-1"></i> Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
