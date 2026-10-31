<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 fw-bold text-dark mb-0">
            <i class="bi bi-image text-primary me-2"></i>Logo du site
        </h1>
    </x-slot>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <h2 class="h5 fw-bold mb-2">Personnalisez votre logo</h2>
                <p class="text-muted mb-4">Choisissez une image sur votre ordinateur. Vous pourrez la remplacer à tout moment. Formats acceptés : JPG, PNG ou WEBP (2 Mo maximum).</p>

                <div class="mb-4">
                    <div class="text-muted small fw-semibold mb-2">Aperçu actuel</div>
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo actuel du site" class="border rounded-3 p-2" style="max-width: 260px; max-height: 120px; object-fit: contain;">
                    @else
                        <div class="d-inline-flex align-items-center gap-2 bg-dark text-white rounded-3 px-3 py-2">
                            <i class="bi bi-house-door-fill text-info"></i>
                            <span class="fw-bold">Gestion<span class="text-info">Loyer</span></span>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('site-logo.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="logo" class="form-label fw-semibold">Choisir une image depuis votre ordinateur</label>
                        <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-upload me-1"></i> Enregistrer le logo
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
