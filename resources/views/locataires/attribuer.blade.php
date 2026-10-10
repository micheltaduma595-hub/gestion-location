<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight fw-bold text-dark mb-0">
            <i class="bi bi-house-add text-primary"></i> Assigner une maison à {{ $locataire->nom }} {{ $locataire->prenom }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <form action="{{ route('locataires.attribuer', $locataire->id) }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label for="house_id" class="form-label fw-bold">Sélectionner une maison disponible</label>
                                    <select name="house_id" id="house_id" class="form-select @error('house_id') is-invalid @enderror" required>
                                        <option value="">-- Choisir une maison --</option>
                                        @foreach($maisonsDisponibles as $maison)
                                            <option value="{{ $maison->id }}">
                                                {{ $maison->title }} - {{ $maison->address }} ({{ number_format($maison->price, 0, ',', ' ') }} $ / mois)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('house_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('locataires.index') }}" class="btn btn-secondary rounded-pill px-4">Annuler</a>
                                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Attribuer la maison</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>