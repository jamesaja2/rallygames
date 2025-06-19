<?php

namespace App\Providers;
use Filament\Panel;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerRenderHook(
                'auth.login.form.after',
                fn (): string => view('auth.login-google')->render(),
            );
        });
    }
}


