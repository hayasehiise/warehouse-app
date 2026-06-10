<?php

namespace App\Filament\Resources\Distributions\Pages;

use App\Filament\Resources\Distributions\DistributionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDistribution extends CreateRecord
{
    protected static string $resource = DistributionResource::class;

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.distributions.view', [
            'record' => $this->record,
        ]);
    }

    public function getTitle(): string
    {
        return 'Buat Pengeluaran';
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Simpan')->color('primary')->icon('lucide-save'),
            $this->getCreateAnotherFormAction()->label('Simpan & Buat Baru')->color('primary')->icon('lucide-plus'),
            $this->getCancelFormAction()->label('Kembali')->icon('lucide-arrow-left'),
        ];
    }
}
