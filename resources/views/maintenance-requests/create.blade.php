<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-tools text-primary me-2"></i>Signaler un problème</h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <p class="text-muted">Décrivez le problème rencontré dans votre logement. Le gestionnaire pourra suivre votre demande et vous répondre ici.</p>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('maintenance-requests.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="house_id" class="form-label fw-semibold">Logement concerné</label>
                                <select id="house_id" name="house_id" class="form-select @error('house_id') is-invalid @enderror">
                                    <option value="">Non précisé</option>
                                    @foreach($houses as $house)
                                        <option value="{{ $house->id }}" @selected((string) old('house_id') === (string) $house->id)>
                                            {{ $house->title }} — {{ $house->address }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('house_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @if($houses->isEmpty())
                                    <div class="form-text">Aucun logement n’est encore associé à votre compte.</div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label fw-semibold">Type de problème <span class="text-danger">*</span></label>
                                <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="">Choisir une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label fw-semibold">Objet <span class="text-danger">*</span></label>
                                <input id="subject" name="subject" value="{{ old('subject') }}" maxlength="255" class="form-control @error('subject') is-invalid @enderror" required>
                                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">Description du problème <span class="text-danger">*</span></label>
                                <textarea id="description" name="description" rows="6" maxlength="5000" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('maintenance-requests.index') }}" class="btn btn-outline-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Envoyer la réclamation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
