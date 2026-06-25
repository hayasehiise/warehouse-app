<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\Concerns\HasWizard;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Wizard\Step;

class EditUser extends EditRecord
{
    use HasWizard;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
        ];
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Account Information')
                ->description('Informasi Akun User')
                ->icon('lucide-user-round')
                ->schema([
                    UserForm::getName(),
                    UserForm::getEmail(),
                    UserForm::getUsername(),
                    UserForm::getRoles(),
                ])
                ->columns(2),
            Step::make('Personal Information')
                ->description('Informasi Pribadi')
                ->icon('lucide-user-round-search')
                ->schema([
                    UserForm::getFullName(),
                    UserForm::getEmployeeCode(),
                    UserForm::getEmployeePosition(),
                    UserForm::getEmployeeRank(),
                    UserForm::getEmployeeGroup(),
                ])
                ->columns(2),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record->load('userProfile', 'roles');
        $profile = $user->userProfile;

        $data['roles'] = $user->roles->first()->name;
        $data['userProfile'] = [
            'fullName' => $profile->fullName,
            'employee_code' => $profile->employee_code,
            'employee_position' => $profile->employee_position,
            'employee_rank' => $profile->employee_rank,
            'employee_group' => $profile->employee_group,
        ];

        return $data;
    }

    protected function afterSave(): void
    {
        $user = $this->record;
        $data = $this->form->getState();

        // 1. sync to updated role
        $user->syncRoles([$data['roles']]);

        // 2. update user and profile
        $user->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
        ]);

        $user->userProfile()->update([
            'fullName' => $data['userProfile']['fullName'],
            'employee_code' => $data['userProfile']['employee_code'],
            'employee_position' => $data['userProfile']['employee_position'],
            'employee_rank' => $data['userProfile']['employee_rank'],
            'employee_group' => $data['userProfile']['employee_group'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
