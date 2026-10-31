<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class SiteLogo
{
    public static function path(): ?string
    {
        $disk = Storage::disk('local');
        $settingsFile = 'site-logo.txt';

        if (! $disk->exists($settingsFile)) {
            return null;
        }

        $path = trim($disk->get($settingsFile));

        return $path !== '' ? $path : null;
    }

    public static function savePath(string $path): void
    {
        if (! Storage::disk('local')->put('site-logo.txt', $path)) {
            throw new \RuntimeException('Impossible d’enregistrer le chemin du logo.');
        }
    }
}
