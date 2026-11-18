<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold text-dark mb-0">
                <i class="bi bi-person-vcard text-primary me-2"></i>Annuaire des membres
            </h1>
            @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Retour aux utilisateurs
                </a>
            @endif
        </div>
    </x-slot>

    <div class="container-fluid">
        <p class="text-muted mb-4">Retrouvez les coordonnées et le rôle de chaque membre de l’application.</p>

        @if($members->isEmpty())
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle me-2"></i>Aucun membre n’est enregistré pour le moment.
            </div>
        @else
            <div class="row g-4">
                @foreach($members as $member)
                    @php
                        $roleBadge = match($member->role) {
                            \App\Models\User::ROLE_ADMIN => 'text-bg-danger',
                            \App\Models\User::ROLE_MANAGER => 'text-bg-primary',
                            \App\Models\User::ROLE_CLIENT => 'text-bg-secondary',
                            \App\Models\User::ROLE_TENANT => 'text-bg-warning',
                            default => 'text-bg-dark',
                        };
                    @endphp
                    <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                        <article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="bg-light d-flex justify-content-center align-items-center py-4">
                                @if($member->profile_photo_path)
                                    <img
                                        src="{{ asset('storage/' . $member->profile_photo_path) }}"
                                        alt="Photo de {{ $member->name }}"
                                        class="rounded-circle border border-3 border-white shadow-sm"
                                        style="width: 112px; height: 112px; object-fit: cover;"
                                    >
                                @else
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex justify-content-center align-items-center border border-3 border-white shadow-sm" style="width: 112px; height: 112px;">
                                        <i class="bi bi-person-fill fs-1" aria-hidden="true"></i>
                                        <span class="visually-hidden">Aucune photo de profil</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body text-center">
                                <h2 class="h5 fw-bold mb-2">{{ $member->name }}</h2>
                                <span class="badge {{ $roleBadge }} mb-3">
                                    {{ \App\Models\User::ROLES[$member->role] ?? $member->role }}
                                </span>
                                <div class="text-start border-top pt-3">
                                    <p class="mb-2 text-break">
                                        <i class="bi bi-envelope text-primary me-2"></i>{{ $member->email }}
                                    </p>
                                    <p class="mb-0">
                                        <i class="bi bi-telephone text-primary me-2"></i>{{ $member->phone ?: 'Téléphone non renseigné' }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
