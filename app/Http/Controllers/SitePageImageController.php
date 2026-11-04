<?php

namespace App\Http\Controllers;

use App\Support\SitePageImages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SitePageImageController extends Controller
{
    public function edit(): View
    {
        $this->authorizeImageManagement();

        return view('settings.page-images', [
            'pages' => SitePageImages::all(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeImageManagement();

        $validated = $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $pages = SitePageImages::all();
        $settings = [];
        $newPaths = [];
        $previousPaths = [];

        foreach ($validated['images'] as $identifier => $image) {
            if (! $image) {
                continue;
            }

            [$page, $key] = array_pad(explode('.', $identifier, 2), 2, null);
            abort_unless(isset($pages[$page][$key]), 422);

            $path = $image->store('site-pages', 'public');
            if (! $path) {
                foreach ($newPaths as $newPath) {
                    Storage::disk('public')->delete($newPath);
                }

                throw new \RuntimeException('Impossible d’enregistrer une image. Vérifiez les permissions du stockage public.');
            }

            $newPaths[] = $path;
            $settings[$page][$key] = $path;
            if (isset($pages[$page][$key]['path'])) {
                $previousPaths[] = $pages[$page][$key]['path'];
            }
        }

        if ($settings === []) {
            return back()->withErrors(['images' => 'Choisissez au moins une image à enregistrer.']);
        }

        $existingSettings = [];
        foreach (SitePageImages::all() as $page => $images) {
            foreach ($images as $key => $image) {
                if (isset($image['path'])) {
                    $existingSettings[$page][$key] = $image['path'];
                }
            }
        }

        $settings = array_replace_recursive($existingSettings, $settings);

        try {
            SitePageImages::save($settings);
        } catch (\RuntimeException $exception) {
            Storage::disk('public')->delete($newPaths);
            throw $exception;
        }

        Storage::disk('public')->delete($previousPaths);

        return redirect()->route('site-page-images.edit')->with('success', 'Les images des pages ont été mises à jour.');
    }

    private function authorizeImageManagement(): void
    {
        abort_unless(
            in_array(auth()->user()->role, ['admin', 'gestionnaire'], true),
            403
        );
    }
}
