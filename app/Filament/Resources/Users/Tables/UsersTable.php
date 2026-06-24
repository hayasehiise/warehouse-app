<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Pages\Profile;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Akun'),
                TextColumn::make('username')
                    ->label('Username'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'primary',
                        'supervisor' => 'info',
                        'staff' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrator',
                        'supervisor' => 'Pegawai Penanggung Jawab',
                        'staff' => 'Staf Administrasi',
                        default => 'Pengguna',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'admin' => 'lucide-user-cog',
                        'supervisor' => 'lucide-user-star',
                        'staff' => 'lucide-user',
                        default => 'lucide-shield-question-mark',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('profile_edit')
                    ->label('Edit Profile')
                    ->icon('lucide-user-pen')
                    ->color('primary')
                    ->hidden(fn (User $record): bool => $record->userProfile->public_id == auth()->user()->userProfile->public_id)
                    ->url(fn (User $record): string => Profile::getUrl(['userId' => $record->userProfile->public_id])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
