<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste des locataires</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #212529; }
        .identity { width: 100%; margin-bottom: 18px; }
        .identity td { border: 0; background: #fff; vertical-align: middle; }
        .identity-logo, .identity-flag { width: 18%; text-align: center; }
        .identity-logo img { max-width: 100px; max-height: 65px; }
        .identity-flag img { width: 90px; height: 60px; }
        .identity-name { text-align: center; color: #084298; }
        .identity-name h2 { font-size: 18px; margin: 0 0 5px; }
        .identity-name p { font-size: 10px; margin: 2px 0; }
        h1 { color: #084298; font-size: 20px; margin: 0 0 5px; text-align: center; }
        .generated { color: #6c757d; margin-bottom: 18px; text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #9ec5fe; padding: 7px; }
        th { background-color: #0d6efd; color: #fff; text-align: center; }
        tbody td { background-color: #eef6ff; }
        tbody tr:nth-child(even) td { background-color: #dbeafe; }
        .fallback-logo { color: #084298; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>
    <table class="identity">
        <tr>
            <td class="identity-logo">
                @if($siteLogoData)
                    <img src="{{ $siteLogoData }}" alt="Logo {{ $companyName }}">
                @else
                    <span class="fallback-logo">{{ $companyName }}</span>
                @endif
            </td>
            <td class="identity-name">
                <h2>{{ $companyName }}</h2>
                <p>Gestion immobilière, location et suivi des biens</p>
                <p>République démocratique du Congo</p>
            </td>
            <td class="identity-flag">
                <img src="{{ $flagData }}" alt="Drapeau de la République démocratique du Congo">
            </td>
        </tr>
    </table>

    <h1>Liste des locataires</h1>
    <div class="generated">Générée le {{ $dateGeneration }} — {{ $locataires->count() }} locataire(s)</div>

    <table>
        <thead>
            <tr>
                <th>Nom, postnom &amp; prénom</th>
                <th>Téléphone</th>
                <th>E-mail</th>
                <th>Profession</th>
                <th>Maison attribuée</th>
                <th>Date d'inscription</th>
            </tr>
        </thead>
        <tbody>
            @forelse($locataires as $locataire)
                <tr>
                    <td>{{ implode(' ', array_filter([$locataire->nom, $locataire->postnom, $locataire->prenom])) }}</td>
                    <td>{{ $locataire->telephone }}</td>
                    <td>{{ $locataire->email ?? 'Pas d\'email' }}</td>
                    <td>{{ $locataire->profession ?? 'Non renseignée' }}</td>
                    <td>{{ $locataire->maisons->pluck('title')->join(', ') ?: 'Aucune maison' }}</td>
                    <td>{{ ($locataire->user?->created_at ?? $locataire->created_at)?->format('d/m/Y') ?? 'Non renseignée' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Aucun locataire enregistré.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
