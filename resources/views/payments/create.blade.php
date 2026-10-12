<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-cash-coin text-info"></i> Encaisser un Loyer</h4>
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

                        <form action="{{ route('payments.store') }}" method="POST">
                            @csrf

                            <!-- Sélection du Locataire -->
                            <div class="mb-3">
                                <label for="locataire_id" class="form-label fw-bold">Locataire <span class="text-danger">*</span></label>
                                <select name="locataire_id" id="locataire_id" class="form-select" required>
                                    <option value="">-- Sélectionner un locataire --</option>
                                    @foreach($locataires as $locataire)
                                        <option value="{{ $locataire->id }}">
                                            {{ $locataire->nom }} {{ $locataire->prenom }} (Tél: {{ $locataire->telephone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sélection de la Maison -->
                            <div class="mb-3">
                                <label for="house_id" class="form-label fw-bold">Maison concernée <span class="text-danger">*</span></label>
                                <select name="house_id" id="house_id" class="form-select" required>
                                    <option value="">-- Sélectionner une maison louée --</option>
                                    @foreach($houses as $house)
                                        <option value="{{ $house->id }}">
                                            {{ $house->title }} - {{ $house->price }} $ / mois ({{ $house->address }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Montant -->
                            <div class="mb-3">
                                <label for="amount" class="form-label fw-bold">Montant perçu ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="Ex: 45.00" required>
                            </div>

                            <!-- Mois / Période couverte -->
                            <div class="mb-3">
                                <label for="month_covered" class="form-label fw-bold">Mois ou Période couverte <span class="text-danger">*</span></label>
                                <input type="text" name="month_covered" id="month_covered" class="form-control" placeholder="Ex: Octobre 2026" required>
                            </div>

                            <!-- Mode de paiement -->
                            <div class="mb-4">
                                <label for="payment_method" class="form-label fw-bold">Mode de paiement <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="Espèces">Espèces</option>
                                    <option value="Airtel Money">Airtel Money</option>
                                    <option value="Virement Bancaire">Virement Bancaire</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle-fill"></i> Valider le paiement et générer le reçu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>