<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Login as AuthLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class Login extends AuthLogin
{
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->autofocus(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }
}
