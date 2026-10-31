<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Support\SiteLogo;

class SiteLogoController extends Controller
{
    public function edit(): View
    {
        $this->authorizeLogoManagement();

        return view('settings.logo', [
            'siteLogo' => SiteLogo::path(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeLogoManagement();

        $logo = $request->file('logo');

        if (! $logo instanceof UploadedFile || ! $logo->isValid()) {
            return back()->withErrors([
                'logo' => $this->uploadErrorMessage($logo),
            ]);
        }

        $validated = $request->validate([
            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $previousLogo = SiteLogo::path();
        $uploadedLogo = $validated['logo'];
        $temporaryPath = $uploadedLogo->getRealPath();

        if (! $temporaryPath || ! is_file($temporaryPath) || ! is_readable($temporaryPath)) {
            return back()->withErrors([
                'logo' => 'Le fichier temporaire reçu est introuvable. Vérifiez le dossier temporaire des téléversements PHP et réessayez.',
            ]);
        }

        $directory = Storage::disk('public')->path('logos');
        File::ensureDirectoryExists($directory);
        $fileName = $uploadedLogo->hashName();
        $uploadedLogo->move($directory, $fileName);
        $newLogo = 'logos/' . $fileName;

        try {
            SiteLogo::savePath($newLogo);
        } catch (\RuntimeException $exception) {
            Storage::disk('public')->delete($newLogo);
            throw $exception;
        }

        if ($previousLogo && $previousLogo !== $newLogo) {
            Storage::disk('public')->delete($previousLogo);
        }

        return redirect()->route('site-logo.edit')->with('success', 'Le logo du site a été mis à jour.');
    }

    private function uploadErrorMessage(?UploadedFile $logo): string
    {
        return match ($logo?->getError()) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale autorisée par PHP. Choisissez une image plus petite ou augmentez les limites de téléversement PHP.',
            UPLOAD_ERR_PARTIAL => 'Le téléversement du logo a été interrompu. Veuillez réessayer.',
            UPLOAD_ERR_NO_FILE => 'Aucun fichier n’a été reçu. Choisissez une image puis réessayez.',
            UPLOAD_ERR_NO_TMP_DIR => 'Le dossier temporaire des téléversements PHP est introuvable. Vérifiez la configuration de PHP.',
            UPLOAD_ERR_CANT_WRITE => 'PHP n’a pas pu écrire le fichier téléversé. Vérifiez les permissions du dossier temporaire.',
            UPLOAD_ERR_EXTENSION => 'Une extension PHP a interrompu le téléversement du logo.',
            default => 'Le téléversement a échoué avant que le fichier puisse être enregistré. Vérifiez les limites et le dossier temporaire de PHP, puis réessayez.',
        };
    }

    private function authorizeLogoManagement(): void
    {
        abort_unless(
            in_array(auth()->user()->role, ['admin', 'gestionnaire'], true),
            403
        );
    }
}
