<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
                <i class="bi bi-people-fill text-primary"></i> Gestion des Utilisateurs
            </h2>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                <i class="bi bi-person-plus-fill"></i> Nouvel utilisateur
            </a>
            <a href="{{ route('members.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm ms-2">
                <i class="bi bi-person-vcard"></i> Annuaire des membres
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
                        <i class="bi bi-list-nested"></i> Liste des utilisateurs enregistrés
                    </h5>

                    @if($pendingRoleCount > 0)
                        <div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
                            <i class="bi bi-person-exclamation fs-5"></i>
                            <div>
                                <div class="fw-bold">
                                    {{ $pendingRoleCount }} nouveau(x) compte(s) à traiter
                                </div>
                                <div>Les nouveaux inscrits sont enregistrés comme locataires. Vérifiez leur profil et attribuez-leur le rôle approprié.</div>
                            </div>
                        </div>
                    @endif

                    @if(isset($users) && $users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <a href="{{ route('users.show', $user) }}" class="fw-bold text-decoration-none">
                                                    {{ $user->name }}
                                                </a>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
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
                            <i class="bi bi-info-circle-fill"></i> Aucun utilisateur enregistré pour le moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>