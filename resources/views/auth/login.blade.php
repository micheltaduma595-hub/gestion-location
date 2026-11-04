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

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-secondary">Adresse Email</label>
            <input id="email" type="email" class="form-control rounded-3" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nom@exemple.com">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold text-secondary">Mot de passe</label>
            <input id="password" type="password" class="form-control rounded-3" name="password" required autocomplete="current-password" placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm d-block ms-auto mt-4">
            Se connecter
        </button>
    </form>

    <div class="text-center mt-4 pt-3 border-top">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 rounded-pill py-2 fw-semibold text-decoration-none shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Retour à l'accueil pour s'inscrire
        </a>
    </div>
</x-guest-layout>