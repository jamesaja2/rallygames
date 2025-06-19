<?php

namespace App\Filament\Resources\ViewsResource\Pages;

use App\Filament\Resources\ViewsResource;
use Filament\Resources\Pages\Page;

class Login extends Page
{
    protected static string $resource = ViewsResource::class;

    protected static string $view = 'filament.resources.views-resource.pages.login';
}
