<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\Actions\Action;

class Login extends BaseLogin
{
    public function getFormSchema(): array
    {
        return []; // Kosongkan form login bawaan
    }

    protected function getFooterActions(): array
    {
        return [
            Action::make('Login with Google')
                ->url('/auth/google/redirect')
                ->button()
                ->color('primary'),
        ];
    }
}
