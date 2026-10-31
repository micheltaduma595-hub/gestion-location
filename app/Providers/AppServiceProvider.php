<?php

namespace App\Providers;

use App\Support\SiteLogo;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'layouts.app',
            'layouts.guest',
            'client.dashboard',
            'welcome',
            'services',
            'apropos',
            'contact',
            'payments.receipt',
        ], function ($view): void {
            $view->with('siteLogo', SiteLogo::path());
        });
    }
}
