<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Profile extends Page
{
    protected string $view = 'filament.pages.profile';

    protected static ?string $title = 'Profile';

    protected static bool $shouldRegisterNavigation = false;

    public function mount(): void
    {
        //
    }

    private function getTargetUser(): User
    {
        return auth()->user()->load('userProfile');
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->EditProfileAction(),
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => url()->previous()),
        ];
    }

    public function EditProfileAction(): Action
    {
        $user = $this->getTargetUser();

        return Action::make('edit_profile')
            ->label('Edit Profile')
            ->icon('heroicon-o-pencil')
            ->color('primary')
            ->fillForm([
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name,
                'userProfile' => [
                    'fullName' => $user->userProfile->fullName,
                    'employee_code' => $user->userProfile->employee_code,
                    'employee_rank' => $user->userProfile->employee_rank,
                    'employee_position' => $user->userProfile->employee_position,
                    'employee_group' => $user->userProfile->employee_group,
                ],
            ])
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->schema([
                Wizard::make([
                    Step::make('Account Information')
                        ->description('Informasi Akun User')
                        ->icon('lucide-user-round')
                        ->schema([
                            TextInput::make('username')
                                ->label('Username')
                                ->disabled(),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->disabled(),
                            TextInput::make('name')
                                ->label('Nama')
                                ->required(),
                        ])->columns(2),
                    Step::make('Profile Information')
                        ->description('Informasi Profile User')
                        ->icon('lucide-user-round')
                        ->schema([
                            TextInput::make('userProfile.fullName')
                                ->label('Nama Lengkap'),
                            TextInput::make('userProfile.employee_code')
                                ->label('NIP')
                                ->maxLength(21)
                                ->mask('99999999 999999 9 999'),
                            TextInput::make('userProfile.employee_rank')
                                ->label('Pangkat'),
                            TextInput::make('userProfile.employee_position')
                                ->label('Jabatan'),
                            Select::make('userProfile.employee_group')
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
                                ->searchable(),
                        ])->columns(2),
                ])
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                            <x-filament::button
                                type="submit"
                                size="md"
                                icon="lucide-save"
                                icon-position="before"
                            >
                                Simpan
                            </x-filament::button>
                    BLADE)))
                    ->nextAction(fn (Action $action) => $action
                        ->label('Next')
                        ->icon('lucide-arrow-right')
                        ->color('primary')
                    )
                    ->previousAction(fn (Action $action) => $action
                        ->label('Back')
                        ->icon('lucide-arrow-left')
                        ->color('gray')
                    ),
            ])
            ->action(function ($data) use ($user) {
                $user->update([
                    'name' => $data['name'],
                ]);
                $user->userProfile()->update([
                    'fullName' => $data['userProfile']['fullName'],
                    'employee_code' => $data['userProfile']['employee_code'],
                    'employee_rank' => $data['userProfile']['employee_rank'],
                    'employee_position' => $data['userProfile']['employee_position'],
                    'employee_group' => $data['userProfile']['employee_group'],
                ]);
                Notification::make()
                    ->title('Profile updated successfully')
                    ->success()
                    ->send();
                $this->dispatch('profile-updated');
            });
    }
}
