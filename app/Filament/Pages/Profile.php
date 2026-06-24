<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

class Profile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.profile';

    protected static ?string $title = 'Profile';

    protected static bool $shouldRegisterNavigation = false;

    public function profileInfo(Schema $schema): Schema
    {
        $user = auth()->user();

        return $schema
            ->record($user)
            ->components([
                Section::make('Informasi Akun')
                    ->description('Informasi akun pengguna')
                    ->columns(2)
                    ->components([
                        TextEntry::make('username')
                            ->label('Username'),
                        TextEntry::make('name')
                            ->label('Nama Akun'),
                        TextEntry::make('email')
                            ->label('Email'),
                    ]),
                Section::make('Informasi Karyawan')
                    ->description('Informasi karyawan pengguna')
                    ->columns(2)
                    ->components([
                        TextEntry::make('userProfile.fullName')
                            ->label('Nama Lengkap')
                            ->placeholder('-'),
                        TextEntry::make('userProfile.employee_code')
                            ->label('Kode Karyawan')
                            ->placeholder('-'),
                        TextEntry::make('userProfile.employee_rank')
                            ->label('Pangkat')
                            ->placeholder('-'),
                        TextEntry::make('userProfile.employee_position')
                            ->label('Jabatan')
                            ->placeholder('-'),
                        TextEntry::make('userProfile.employee_group')
                            ->label('Grup')
                            ->placeholder('-'),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->EditProfileAction(),
        ];
    }

    public function EditProfileAction(): Action
    {
        $user = auth()->user();

        return Action::make('edit_profile')
            ->label('Edit Profile')
            ->icon('heroicon-o-pencil')
            ->color('primary')
            ->fillForm([
                'userProfile' => [
                    'fullName' => $user->userProfile->fullName,
                    'employee_code' => $user->userProfile->employee_code,
                    'employee_rank' => $user->userProfile->employee_rank,
                    'employee_position' => $user->userProfile->employee_position,
                    'employee_group' => $user->userProfile->employee_group,
                ],
            ])
            ->form([
                Grid::make(2)
                    ->components([
                        TextInput::make('userProfile.fullName')
                            ->label('Nama Lengkap'),
                        TextInput::make('userProfile.employee_code')
                            ->label('Kode Karyawan'),
                        TextInput::make('userProfile.employee_rank')
                            ->label('Pangkat'),
                        TextInput::make('userProfile.employee_position')
                            ->label('Jabatan'),
                        TextInput::make('userProfile.employee_group')
                            ->label('Grup'),
                    ]),
            ])
            ->action(function ($data) use ($user) {
                $user->userProfile->update($data['userProfile']);
                Notification::make()
                    ->title('Profile updated successfully')
                    ->success()
                    ->send();
            });
    }
}
