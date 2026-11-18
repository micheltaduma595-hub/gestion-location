<section>
    <h2 class="h5 fw-bold text-danger mb-1">Supprimer mon compte</h2>
    <p class="text-muted small mb-3">Cette action est définitive. Votre compte et vos données associées seront supprimés.</p>

    <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#confirm-account-deletion" aria-expanded="{{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }}" aria-controls="confirm-account-deletion">
        <i class="bi bi-trash me-1"></i>Supprimer mon compte
    </button>

    <div class="collapse {{ $errors->userDeletion->isNotEmpty() ? 'show' : '' }} mt-3" id="confirm-account-deletion">
        <div class="card card-body border-danger-subtle">
            <p class="mb-3">Pour confirmer la suppression, saisissez votre mot de passe actuel.</p>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="mb-3">
                    <label for="delete_account_password" class="form-label fw-semibold">Mot de passe actuel</label>
                    <input id="delete_account_password" name="password" type="password" autocomplete="current-password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" required>
                    @error('password', 'userDeletion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>Confirmer la suppression définitive
                </button>
            </form>
        </div>
    </div>
</section>
