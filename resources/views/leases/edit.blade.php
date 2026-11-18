<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Modifier le contrat de bail</h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('leases.update', $lease) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('leases.partials.form')
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('leases.index') }}" class="btn btn-outline-secondary">Retour</a>
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
