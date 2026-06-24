<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;

class Profile extends Page
{
    protected string $view = 'filament.pages.profile';

    protected static ?string $title = 'Profile';

    protected static bool $shouldRegisterNavigation = false;

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
                            ->label('Kode Karyawan')
                            ->mask('99999999 999999 9 999'),
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
