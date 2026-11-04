<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 fw-bold text-dark mb-0">
            <i class="bi bi-images text-primary me-2"></i>Images des pages
        </h1>
    </x-slot>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <p class="text-muted mb-4">Remplacez les images des cartes des pages « Services » et « À propos ». Vous pouvez modifier une ou plusieurs images à la fois. Formats acceptés : JPG, PNG ou WEBP (4 Mo maximum par image).</p>

                <form method="POST" action="{{ route('site-page-images.update') }}" enctype="multipart/form-data">
                    @csrf

                    @foreach($pages as $page => $images)
                        <h2 class="h5 fw-bold mt-4 mb-3">{{ $page === 'services' ? 'Page Services' : 'Page À propos' }}</h2>
                        <div class="row g-4">
                            @foreach($images as $key => $image)
                                <div class="col-md-4">
                                    <div class="border rounded-3 p-3 h-100">
                                        <label for="image-{{ $page }}-{{ $key }}" class="form-label fw-semibold">{{ $image['label'] }}</label>
                                        <img src="{{ $image['url'] }}" alt="{{ $image['label'] }}" class="img-fluid rounded-3 mb-3 w-100" style="height: 170px; object-fit: cover;">
                                        <input
                                            type="file"
                                            id="image-{{ $page }}-{{ $key }}"
                                            name="images[{{ $page }}.{{ $key }}]"
                                            class="form-control"
                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary rounded-pill px-4 mt-4">
                        <i class="bi bi-upload me-1"></i> Enregistrer les images choisies
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
