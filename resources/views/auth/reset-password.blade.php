<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <p class="text-secondary mb-4">Saisissez votre adresse courriel et choisissez un nouveau mot de passe.</p>

        @if ($errors->any())
            <div class="alert alert-danger py-2 mb-3" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div>
            <label for="email" class="form-label fw-semibold">Adresse courriel</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="nom@exemple.com">
        </div>

        <div class="mt-3">
            <label for="password" class="form-label fw-semibold">Nouveau mot de passe</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
        </div>

        <div class="mt-3">
            <label for="password_confirmation" class="form-label fw-semibold">Confirmer le nouveau mot de passe</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4">
            <a href="{{ route('login') }}" class="small">Retour à la connexion</a>
            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">Enregistrer le nouveau mot de passe</button>
        </div>
    </form>
</x-guest-layout>
