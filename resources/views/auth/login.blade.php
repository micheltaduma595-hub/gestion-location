<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success py-2 mb-3 small">
            {{ session('status') }}
        </div>
    @endif

    <!-- Affichage des erreurs -->
    @if ($errors->any())
        <div class="alert alert-danger py-2 mb-3">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Message d'attention bien clair et mis en valeur -->
    <div class="alert alert-primary border-0 shadow-sm rounded-4 p-3 mb-4 text-center">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
            <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
            <h5 class="fw-bold text-primary mb-0">Attention / Important</h5>
        </div>
        <p class="small text-dark mb-0 mt-2">
            Si vous possédez déjà un compte, veuillez vous connecter ci-dessous. <br>
            <strong>Sinon, veuillez retourner à la page d'accueil pour créer votre compte et vous inscrire !</strong>
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-secondary">Adresse Email</label>
            <input id="email" type="email" class="form-control rounded-3" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nom@exemple.com">
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold text-secondary">Mot de passe</label>
            <input id="password" type="password" class="form-control rounded-3" name="password" required autocomplete="current-password" placeholder="••••••••">
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label text-secondary small" for="remember_me">Se souvenir de moi</label>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-4">
            @if (Route::has('password.request'))
                <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif

            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm">
                Se connecter
            </button>
        </div>
    </form>

    <!-- Bouton de retour vers la page d'accueil pour s'inscrire -->
    <div class="text-center mt-4 pt-3 border-top">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 rounded-pill py-2 fw-semibold text-decoration-none shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Retour à l'accueil pour s'inscrire
        </a>
    </div>
</x-guest-layout>