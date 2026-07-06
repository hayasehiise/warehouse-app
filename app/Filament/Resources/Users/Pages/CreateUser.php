<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Wizard\Step;

class CreateUser extends CreateRecord
{
    use HasWizard;

    protected static string $resource = UserResource::class;

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
                    UserForm::getPassword(),
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

    protected function afterCreate(): void
    {
        $user = $this->record;
        $data = $this->form->getState();

        // 1. assign role after create user
        $user->assignRole($data['roles']);

        // 2. create user profile
        $profile = $data['userProfile'];
        $user->userProfile()->create([
            'user_id' => $user->id,
            'fullName' => $profile['fullName'],
            'employee_code' => $profile['employee_code'],
            'employee_position' => $profile['employee_position'],
            'employee_rank' => $profile['employee_rank'],
            'employee_group' => $profile['employee_group'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
