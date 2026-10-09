<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
                <i class="bi bi-pencil-square text-primary"></i> Modifier l'utilisateur : {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold">Nom complet</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">Adresse Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-bold">Numéro de téléphone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Ex: +243XXXXXXXXX">
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label fw-bold">Rôle dans le système</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>Client / Locataire</option>
                                        <option value="gestionnaire" {{ old('role', $user->role) == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    </select>
                                </div>

                                <hr class="my-4">
                                <p class="text-muted small">Laissez les champs du mot de passe vides si vous ne souhaitez pas le modifier.</p>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-bold">Nouveau mot de passe (optionnel)</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Laisser vide pour ne pas changer">
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirmer le nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Répétez le nouveau mot de passe">
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                        <i class="bi bi-check-lg me-1"></i> Mettre à jour les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>