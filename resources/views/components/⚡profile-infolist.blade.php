<?php

use Livewire\Component;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Models\User;

new class extends Component implements HasSchemas {
    use InteractsWithSchemas;

    public ?User $user = null;
    public ?string $publicId = null;

    public function mount(?string $userId): void
    {
        $this->publicId = $userId;
        $this->loadUser();
    }

    public function loadUser(): void
    {
        $this->user = User::with('userProfile')->whereHas('userProfile', fn($query) => $query->where('public_id', $this->publicId))->firstOrFail();
    }

    public function getListeners(): array
    {
        return [
            'profile-updated' => 'loadUser',
        ];
    }

    public function profileInfo(Schema $schema): Schema
    {
        return $schema->record($this->user)->components([
            Section::make('Informasi Akun')
                ->description('Informasi akun pengguna')
                ->columns(2)
                ->components([TextEntry::make('username')->label('Username'), TextEntry::make('name')->label('Nama Akun'), TextEntry::make('email')->label('Email')]),
            Section::make('Informasi Karyawan')
                ->description('Informasi karyawan pengguna')
                ->columns(2)
                ->components([TextEntry::make('userProfile.fullName')->label('Nama Lengkap')->placeholder('-'), TextEntry::make('userProfile.employee_code')->label('NIP')->placeholder('-'), TextEntry::make('userProfile.employee_rank')->label('Pangkat')->placeholder('-'), TextEntry::make('userProfile.employee_position')->label('Jabatan')->placeholder('-'), TextEntry::make('userProfile.employee_group')->label('Golongan')->placeholder('-')]),
        ]);
    }
};
?>

<div>
    {{ $this->profileInfo }}
</div>
