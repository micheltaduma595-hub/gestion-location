<x-guest-layout>
    <div class="mb-4 text-secondary">
        Indiquez l’adresse courriel de votre compte. Si elle est enregistrée, vous recevrez un lien pour choisir un nouveau mot de passe.
    </div>

    @if (session('status'))
        <div class="alert alert-success py-2 mb-3" role="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger py-2 mb-3" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email" class="form-label fw-semibold">Adresse courriel</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nom@exemple.com">
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4">
            <a href="{{ route('login') }}" class="small">Retour à la connexion</a>
            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">Envoyer le lien</button>
        </div>
    </form>
</x-guest-layout>
