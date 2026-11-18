<?php

namespace App\Http\Controllers;

use App\Support\SiteLogo;
use App\Support\SitePageImages;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $isAdministrator = in_array($request->user()->role, ['admin', 'gestionnaire'], true);

        $pages = $isAdministrator ? SitePageImages::all() : [];
        $customImagesCount = collect($pages)
            ->flatten(1)
            ->filter(fn (array $image) => isset($image['path']))
            ->count();

        return view('settings.index', [
            'isAdministrator' => $isAdministrator,
            'siteLogo' => $isAdministrator ? SiteLogo::path() : null,
            'customImagesCount' => $customImagesCount,
            'totalImagesCount' => collect($pages)->flatten(1)->count(),
        ]);
    }
}
