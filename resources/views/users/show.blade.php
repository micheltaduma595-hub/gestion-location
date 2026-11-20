<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
                <i class="bi bi-person-vcard-fill text-primary"></i> Informations de l’utilisateur
            </h2>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="container">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4">{{ $user->name }}</h3>
                @if($user->role === \App\Models\User::ROLE_TENANT && is_null($user->role_reviewed_at))
                    <div class="alert alert-warning">Nouveau — rôle à attribuer</div>
                @endif
                @if($user->login_locked_at)
                    <div class="alert alert-danger">Compte bloqué</div>
                    <form action="{{ route('users.send-password-reset', $user) }}" method="POST" class="mb-4" onsubmit="return confirm('Envoyer un lien de réinitialisation à {{ $user->email }} ?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-envelope-arrow-up me-1"></i> Rétablir l’accès
                        </button>
                    </form>
                @endif
                <dl class="row mb-0">
                    <dt class="col-sm-4">Adresse e-mail</dt>
                    <dd class="col-sm-8">{{ $user->email }}</dd>

                    <dt class="col-sm-4">Téléphone</dt>
                    <dd class="col-sm-8">{{ $user->phone ?: 'Non renseigné' }}</dd>

                    <dt class="col-sm-4">Profession</dt>
                    <dd class="col-sm-8">{{ $user->locataire?->profession ?: 'Non renseignée' }}</dd>

                    <dt class="col-sm-4">Date d'inscription</dt>
                    <dd class="col-sm-8">{{ $user->created_at?->format('d/m/Y') ?? 'Inconnue' }}</dd>

                    <dt class="col-sm-4">Rôle</dt>
                    <dd class="col-sm-8">{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</dd>
                </dl>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-pencil-square me-1"></i> Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
