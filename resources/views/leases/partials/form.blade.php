@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="fw-semibold mb-1">Le contrat n'a pas pu être enregistré.</div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($clients->isEmpty() || $houses->isEmpty())
    <div class="alert alert-warning">
        @if($clients->isEmpty())
            Aucun compte client n'est disponible. Ajoutez un compte avec le rôle client avant de créer un contrat.
        @endif
        @if($houses->isEmpty())
            Aucun logement n'est disponible pour un nouveau contrat actif.
        @endif
    </div>
@endif

<div class="mb-3">
    <label for="house_id" class="form-label fw-semibold">Logement <span class="text-danger">*</span></label>
    <select name="house_id" id="house_id" class="form-select @error('house_id') is-invalid @enderror" required>
        <option value="">Sélectionner un logement</option>
        @foreach($houses as $house)
            <option value="{{ $house->id }}" @selected((string) old('house_id', $lease->house_id) === (string) $house->id)>
                {{ $house->title }} — {{ $house->address }}
            </option>
        @endforeach
    </select>
    @error('house_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="client_id" class="form-label fw-semibold">Locataire (compte client) <span class="text-danger">*</span></label>
    <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
        <option value="">Sélectionner un locataire</option>
        @foreach($clients as $client)
            <option value="{{ $client->id }}" @selected((string) old('client_id', $lease->client_id) === (string) $client->id)>
                {{ $client->name }} — {{ $client->email }}
            </option>
        @endforeach
    </select>
    @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="start_date" class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $lease->start_date?->format('Y-m-d')) }}" class="form-control @error('start_date') is-invalid @enderror" required>
        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="end_date" class="form-label fw-semibold">Date de fin <span class="text-danger">*</span></label>
        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $lease->end_date?->format('Y-m-d')) }}" class="form-control @error('end_date') is-invalid @enderror" required>
        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div>
    <label for="status" class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
        <option value="actif" @selected(old('status', $lease->status) === 'actif')>Actif</option>
        <option value="termine" @selected(old('status', $lease->status) === 'termine')>Terminé</option>
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
