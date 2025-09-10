<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        
    Filament::serving(function () {
        Filament::auth(function (\App\Models\User $user) {
            return true; // Mengizinkan semua user login ke Filament
        });
    });

    }
}
