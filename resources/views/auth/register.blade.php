<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h3 class="fw-bold text-center mb-4 text-dark">
                            <i class="bi bi-person-plus-fill text-primary"></i> Inscription d'un utilisateur
                        </h3>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Nom complet -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Nom complet</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Numéro de téléphone -->
                            <div class="mb-3">
                                <label for="telephone" class="form-label fw-bold">Numéro de téléphone</label>
                                <input id="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" name="telephone" value="{{ old('telephone') }}" placeholder="+243..." required>
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Adresse Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Adresse Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mot de passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Mot de passe</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmation du mot de passe -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-bold">Confirmer le mot de passe</label>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <a class="text-decoration-none small text-muted" href="{{ route('login') }}">
                                    Déjà enregistré ? Se connecter
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm fw-bold">
                                <i class="bi bi-check-circle me-1"></i> S'inscrire
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>