<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
            <i class="bi bi-person-plus-fill text-primary"></i> Ajouter un nouveau locataire
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <form action="{{ route('locataires.store') }}" method="POST">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nom" class="form-label fw-bold">Nom</label>
                                        <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                                        @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="prenom" class="form-label fw-bold">Prénom</label>
                                        <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                        @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="telephone" class="form-label fw-bold">Téléphone</label>
                                        <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                        @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-bold">Email (Optionnel)</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="profession" class="form-label fw-bold">Profession (Optionnel)</label>
                                    <input type="text" class="form-control @error('profession') is-invalid @enderror" id="profession" name="profession" value="{{ old('profession') }}">
                                    @error('profession') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('locataires.index') }}" class="btn btn-secondary rounded-pill px-4">Retour</a>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>