<section>
    <div class="d-flex align-items-center gap-3 mb-4">
        @if($user->profile_photo_path)
            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Photo de profil actuelle" class="rounded-circle border" style="width: 76px; height: 76px; object-fit: cover;">
        @else
            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 76px; height: 76px;">
                <i class="bi bi-person fs-2"></i>
            </div>
        @endif
        <div>
            <h2 class="h5 fw-bold mb-1">Coordonnées et photo</h2>
            <p class="text-muted small mb-0">Les informations enregistrées seront utilisées pour votre compte.</p>
        </div>
    </div>

    @if(session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>Vos coordonnées ont été enregistrées.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="alert alert-warning">
            Votre adresse e-mail n’est pas encore vérifiée.
            <form method="post" action="{{ route('verification.send') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link btn-sm p-0 align-baseline">Renvoyer le lien de vérification</button>
            </form>
            @if(session('status') === 'verification-link-sent')
                <div class="small mt-1">Un nouveau lien de vérification a été envoyé.</div>
            @endif
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="profile_photo" class="form-label fw-semibold">Photo de profil</label>
            <input id="profile_photo" name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="form-control @error('profile_photo') is-invalid @enderror">
            <div class="form-text">Formats JPG, PNG ou WEBP — 8 Mo maximum.</div>
            @error('profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" maxlength="255" autocomplete="name" class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label fw-semibold">Téléphone</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" maxlength="30" autocomplete="tel" class="form-control @error('phone') is-invalid @enderror">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Adresse e-mail <span class="text-danger">*</span></label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" maxlength="255" autocomplete="email" class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i>Enregistrer mes coordonnées
        </button>
    </form>
</section>
