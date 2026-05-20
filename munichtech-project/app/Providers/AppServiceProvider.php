<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        if (empty(config('services.google.redirect'))) {
            config([
                'services.google.redirect' => rtrim((string) config('app.url'), '/') . '/auth/google/callback',
            ]);
        }
    }
}
