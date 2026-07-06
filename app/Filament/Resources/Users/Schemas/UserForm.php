<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                static::getName(),
                static::getEmail(),
                static::getUsername(),
                static::getPassword(),
                static::getRoles(),
                static::getFullName(),
                static::getEmployeeCode(),
                static::getEmployeePosition(),
                static::getEmployeeRank(),
                static::getEmployeeGroup(),
            ]);
    }

    public static function getName(): TextInput
    {
        return TextInput::make('name')
            ->label('Nama Akun')
            ->required();
    }

    public static function getEmail(): TextInput
    {
        return TextInput::make('email')
            ->label('Email')
            ->email()
            ->required();
    }

    public static function getUsername(): TextInput
    {
        return TextInput::make('username')
            ->label('Username')
            ->required();
    }

    public static function getPassword(): TextInput
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->revealable()
            ->required();
    }

    public static function getRoles(): Select
    {
        return Select::make('roles')
            ->label('Role')
            ->options(Role::pluck('name', 'name'))
            ->preload()
            ->required()
            ->helperText('Pilih Role akun');
    }

    public static function getFullName(): TextInput
    {
        return TextInput::make('userProfile.fullName')
            ->label('Nama Lengkap');
    }

    public static function getEmployeeCode(): TextInput
    {
        return TextInput::make('userProfile.employee_code')
            ->label('NIP')
            ->maxLength(21)
            ->mask('99999999 999999 9 999');
    }

    public static function getEmployeePosition(): TextInput
    {
        return TextInput::make('userProfile.employee_position')
            ->label('Jabatan');
    }

    public static function getEmployeeRank(): TextInput
    {
        return TextInput::make('userProfile.employee_rank')
            ->label('Pangkat');
    }

    public static function getEmployeeGroup(): Select
    {
        return Select::make('userProfile.employee_group')
            ->label('Golongan')
            ->options([
                'Juru' => [
                    'I/a' => 'I/a',
                    'I/b' => 'I/b',
                    'I/c' => 'I/c',
                    'I/d' => 'I/d',
                ],
                'Pengatur' => [
                    'II/a' => 'II/a',
                    'II/b' => 'II/b',
                    'II/c' => 'II/c',
                    'II/d' => 'II/d',
                ],
                'Penata' => [
                    'III/a' => 'III/a',
                    'III/b' => 'III/b',
                    'III/c' => 'III/c',
                    'III/d' => 'III/d',
                ],
                'Pembina' => [
                    'IV/a' => 'IV/a',
                    'IV/b' => 'IV/b',
                    'IV/c' => 'IV/c',
                    'IV/d' => 'IV/d',
                ],
            ])
            ->searchable();
    }
}
