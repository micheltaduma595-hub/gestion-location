<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class SitePageImages
{
    private const DEFAULTS = [
        'services' => [
            'publication' => [
                'label' => 'Publication et choix',
                'url' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80',
            ],
            'contact' => [
                'label' => 'Contact direct',
                'url' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=800&q=80',
            ],
            'alertes' => [
                'label' => 'Alertes d’échéance',
                'url' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80',
            ],
        ],
        'apropos' => [
            'mission' => [
                'label' => 'Notre mission',
                'url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
            ],
            'securite' => [
                'label' => 'Transparence et sécurité',
                'url' => 'https://images.unsplash.com/photo-1542744094-3a31246264d0?auto=format&fit=crop&w=800&q=80',
            ],
            'innovation' => [
                'label' => 'Innovation continue',
                'url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80',
            ],
        ],
    ];

    public static function forPage(string $page): array
    {
        $settings = self::settings();
        $images = self::DEFAULTS[$page] ?? [];

        foreach ($images as $key => &$image) {
            $path = $settings[$page][$key] ?? null;
            if ($path) {
                $image['url'] = asset('storage/'.$path);
                $image['path'] = $path;
            }
        }

        return $images;
    }

    public static function all(): array
    {
        $pages = [];

        foreach (array_keys(self::DEFAULTS) as $page) {
            $pages[$page] = self::forPage($page);
        }

        return $pages;
    }

    public static function save(array $settings): void
    {
        if (! Storage::disk('local')->put('site-page-images.json', json_encode($settings, JSON_THROW_ON_ERROR))) {
            throw new \RuntimeException('Impossible d’enregistrer les images des pages.');
        }
    }

    private static function settings(): array
    {
        $disk = Storage::disk('local');
        $file = 'site-page-images.json';

        if (! $disk->exists($file)) {
            return [];
        }

        $settings = json_decode($disk->get($file), true, flags: JSON_THROW_ON_ERROR);

        return is_array($settings) ? $settings : [];
    }
}
