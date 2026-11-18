<section>
    <h2 class="h5 fw-bold mb-1">Modifier le mot de passe</h2>
    <p class="text-muted small mb-4">Choisissez un mot de passe long et difficile à deviner pour protéger votre compte.</p>

    @if(session('status') === 'password-updated')
        <div class="alert alert-success">Votre mot de passe a été modifié.</div>
    @endif

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-semibold">Mot de passe actuel</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
            @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label fw-semibold">Nouveau mot de passe</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
            @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label fw-semibold">Confirmer le nouveau mot de passe</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-shield-lock me-1"></i>Enregistrer le nouveau mot de passe
        </button>
    </form>
</section>
