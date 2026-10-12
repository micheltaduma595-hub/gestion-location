<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Loyer - {{ $payment->reference_reçu }}</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .receipt-card { max-width: 750px; margin: 40px auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            .receipt-card { box-shadow: none; margin: 0; padding: 20px; width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Boutons d'action (masqués à l'impression) -->
        <div class="text-center mb-4 no-print">
            <button onclick="window.print()" class="btn btn-dark btn-lg px-4 me-2">
                <i class="bi bi-printer"></i> Imprimer le Reçu
            </button>
            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                <i class="bi bi-arrow-left"></i> Retour à la Caisse
            </a>
        </div>

        <!-- Carte du Reçu -->
        <div class="receipt-card border">
            <!-- En-tête du Reçu -->
            <div class="row border-bottom pb-4 mb-4 align-items-center">
                <div class="col-6">
                    <h3 class="fw-bold text-dark mb-0">
                        <i class="bi bi-house-door-fill text-info"></i> Gestion<span class="text-info">Loyer</span>
                    </h3>
                    <p class="text-muted small mb-0">Quittance de Loyer Officielle</p>
                </div>
                <div class="col-6 text-end">
                    <h5 class="text-primary fw-bold mb-1">REÇU DE PAIEMENT</h5>
                    <p class="mb-0 text-muted"><strong>N° :</strong> {{ $payment->reference_reçu }}</p>
                    <p class="mb-0 text-muted"><strong>Date :</strong> {{ $payment->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Informations du Locataire et de la Propriété -->
            <div class="row mb-4">
                <div class="col-6">
                    <h6 class="text-uppercase text-muted fw-bold small">Informations Locataire :</h6>
                    <p class="mb-1"><strong>Nom :</strong> {{ $payment->locataire->nom ?? '' }} {{ $payment->locataire->prenom ?? '' }}</p>
                    <p class="mb-1"><strong>Téléphone :</strong> {{ $payment->locataire->telephone ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <h6 class="text-uppercase text-muted fw-bold small">Logement concerné :</h6>
                    <p class="mb-1"><strong>Maison :</strong> {{ $payment->house->title ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Adresse :</strong> {{ $payment->house->address ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Tableau des détails du versement -->
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th>Période / Mois couverte</th>
                        <th>Mode de règlement</th>
                        <th class="text-end">Montant payé</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">{{ $payment->month_covered }}</td>
                        <td><span class="badge bg-secondary">{{ $payment->payment_method }}</span></td>
                        <td class="text-end fw-bold text-success fs-5">{{ number_format($payment->amount, 2) }} $</td>
                    </tr>
                </tbody>
            </table>

            <!-- Signature et Cachet -->
            <div class="row mt-5 pt-4 border-top">
                <div class="col-6">
                    <p class="text-muted small mb-1">Caissier(ère) / Gestionnaire :</p>
                    <p class="fw-bold mb-0">{{ $payment->user->name ?? 'Administrateur' }}</p>
                </div>
                <div class="col-6 text-end">
                    <p class="text-muted small mb-1">Cachet et Signature :</p>
                    <div style="height: 60px;"></div>
                    <span class="border-top border-dark pt-1 d-inline-block px-4 text-muted small">Validation Caisse</span>
                </div>
            </div>

            <div class="text-center mt-5 text-muted small no-print">
                <hr>
                <p class="mb-0">Ce reçu atteste du règlement du loyer pour la période mentionnée ci-dessus. Merci de votre confiance !</p>
            </div>
        </div>
    </div>

</body>
</html>