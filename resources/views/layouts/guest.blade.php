<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Gestion Loyer - Connexion</title>

        <!-- Bootstrap 5 CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        
        <style>
            body {
                background: linear-gradient(135deg, #0b1c3c 0%, #173868 50%, #1e4b87 100%);
                min-height: 100vh;
                color: #fff;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .auth-card {
                background: #ffffff;
                color: #333;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            }
        </style>
    </head>
    <body>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="text-center mb-4">
                        <a href="{{ route('home') }}" class="text-decoration-none fw-bold fs-3 text-white d-inline-flex align-items-center gap-2">
                            <div class="bg-primary text-white p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-house-door-fill fs-4"></i>
                            </div>
                            <span>Gestion<span class="text-info">Loyer</span></span>
                        </a>
                    </div>

                    <div class="auth-card p-4 p-md-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>