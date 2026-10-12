<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-cash-stack text-info"></i> Gestion de la Caisse & Paiements</h2>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Encaisser un loyer
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>N° Reçu</th>
                                <th>Locataire</th>
                                <th>Maison</th>
                                <th>Montant</th>
                                <th>Mois couvert</th>
                                <th>Mode</th>
                                <th>Caissier</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $payment->reference_reçu }}</span></td>
                                    <td>{{ $payment->locataire->nom ?? 'N/A' }} {{ $payment->locataire->prenom ?? '' }}</td>
                                    <td>{{ $payment->locataire->house->title ?? 'Maison #' . $payment->house_id }}</td>
                                    <td class="fw-bold text-success">{{ number_format($payment->amount, 2) }} $</td>
                                    <td>{{ $payment->month_covered }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $payment->payment_method }}</span></td>
                                    <td>{{ $payment->user->name ?? 'Admin' }}</td>
                                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <!-- Bouton Reçu -->
                                            <a href="{{ route('payments.receipt', $payment->id) }}" target="_blank" class="btn btn-sm btn-outline-dark" title="Imprimer le reçu">
                                                <i class="bi bi-printer"></i>
                                            </a>

                                            <!-- Bouton Modifier -->
                                            <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- Bouton Supprimer -->
                                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce paiement ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Aucun paiement enregistré pour le moment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>