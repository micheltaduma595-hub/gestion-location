<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="fw-bold text-dark mb-1">Mon profil</h2>
            <p class="text-muted mb-0">Gérez vos coordonnées, votre photo et la sécurité de votre compte.</p>
        </div>
    </x-slot>

    <div class="container-fluid">
        <div class="mb-3">
            <a href="{{ route('settings.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Retour aux paramètres
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Certaines informations nécessitent votre attention.</div>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center g-4">
            <div class="col-12 col-xxl-10">
                <section class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </section>
            </div>

            <div class="col-12 col-xxl-10">
                <section class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        @include('profile.partials.update-password-form')
                    </div>
                </section>
            </div>

            <div class="col-12 col-xxl-10">
                <section class="card border border-danger-subtle shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        @include('profile.partials.delete-user-form')
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
