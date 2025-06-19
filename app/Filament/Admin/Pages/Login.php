<?php 

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Login extends Page
{
    protected static string $view = 'filament.admin.pages.login';

    protected static bool $shouldRegisterNavigation = false;

    public function mount() // ❗hapus ": void"
    {
        redirect()->to('/auth/google/redirect');
    }
}
