<?php

namespace App\Filament\Intern\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\TextInput;

class Login extends BaseLogin
{
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('username')
                ->label('Intern ID')
                ->required()
                ->autofocus(),

            TextInput::make('password')
                ->password()
                ->required(),
        ];
    }

    
}