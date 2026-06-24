<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Livewire\Attributes\Url;

class Profile extends Page
{
    protected string $view = 'filament.pages.profile';

    protected static ?string $title = 'Profile';

    protected static bool $shouldRegisterNavigation = false;

    public ?string $targetUserId = null;

    #[Url]
    public ?string $userId = null;

    public function mount(): void
    {
        if ($this->userId !== null) {
            abort_unless(auth()->user()->hasRole('admin'), 403);
            $this->targetUserId = $this->userId;
        } else {
            $this->targetUserId = auth()->user()->userProfile->public_id;
        }
    }

    private function getTargetUser(): User
    {
        return User::with('userProfile')->whereHas('userProfile', fn ($query) => $query->where('public_id', $this->targetUserId))->firstOrFail();
    }

    public static function getUserUrl(string $userId): string
    {
        return static::getUrl(['userId' => $userId]);
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->EditProfileAction(),
        ];
    }

    public function EditProfileAction(): Action
    {
        $user = $this->getTargetUser();

        return Action::make('edit_profile')
            ->label('Edit Profile')
            ->icon('heroicon-o-pencil')
            ->color('primary')
            ->visible(fn () => auth()->user()->hasRole('admin') || auth()->user()->id === $user->id)
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
                $this->dispatch('profile-updated');
            });
    }
}
