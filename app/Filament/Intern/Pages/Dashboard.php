<?php

namespace App\Filament\Intern\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.intern.pages.dashboard';
}
