<?php

namespace App\Filament\Intern\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form; // <-- Make sure this line is here!

class Login extends BaseLogin
{
    // Override the form to use your custom fields
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('username')
                    ->label('Intern ID / Username')
                    ->required()
                    ->autofocus(),
                
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    // Tell Filament to authenticate using the 'username' database column
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    
}