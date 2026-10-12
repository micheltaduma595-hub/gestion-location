<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-pencil-square text-info"></i> Modifier le Paiement (Reçu : {{ $payment->reference_reçu }})</h4>
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Sélection du Locataire -->
                            <div class="mb-3">
                                <label for="locataire_id" class="form-label fw-bold">Locataire <span class="text-danger">*</span></label>
                                <select name="locataire_id" id="locataire_id" class="form-select" required>
                                    <option value="">-- Sélectionner un locataire --</option>
                                    @foreach($locataires as $locataire)
                                        <option value="{{ $locataire->id }}" {{ $payment->locataire_id == $locataire->id ? 'selected' : '' }}>
                                            {{ $locataire->nom }} {{ $locataire->prenom }} (Tél: {{ $locataire->telephone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sélection de la Maison -->
                            <div class="mb-3">
                                <label for="house_id" class="form-label fw-bold">Maison concernée <span class="text-danger">*</span></label>
                                <select name="house_id" id="house_id" class="form-select" required>
                                    <option value="">-- Sélectionner une maison --</option>
                                    @foreach($houses as $house)
                                        <option value="{{ $house->id }}" {{ $payment->house_id == $house->id ? 'selected' : '' }}>
                                            {{ $house->title }} - {{ $house->price }} $ / mois ({{ $house->address }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Montant -->
                            <div class="mb-3">
                                <label for="amount" class="form-label fw-bold">Montant perçu ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ $payment->amount }}" required>
                            </div>

                            <!-- Mois / Période couverte -->
                            <div class="mb-3">
                                <label for="month_covered" class="form-label fw-bold">Mois ou Période couverte <span class="text-danger">*</span></label>
                                <input type="text" name="month_covered" id="month_covered" class="form-control" value="{{ $payment->month_covered }}" required>
                            </div>

                            <!-- Mode de paiement -->
                            <div class="mb-4">
                                <label for="payment_method" class="form-label fw-bold">Mode de paiement <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="Espèces" {{ $payment->payment_method == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                    <option value="Airtel Money" {{ $payment->payment_method == 'Airtel Money' ? 'selected' : '' }}>Airtel Money</option>
                                    <option value="Virement Bancaire" {{ $payment->payment_method == 'Virement Bancaire' ? 'selected' : '' }}>Virement Bancaire</option>
                                    <option value="Autre" {{ $payment->payment_method == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save"></i> Mettre à jour le paiement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>