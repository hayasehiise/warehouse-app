<?php

namespace App\Filament\Resources\Items\Pages;

use App\Filament\Resources\Items\ItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Simpan')->icon('lucide-save'),
            $this->getCreateAnotherFormAction()->label('Simpan & Buat Baru')->icon('lucide-plus')->color('primary'),
            $this->getCancelFormAction()->label('Batal')->icon('lucide-x'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->itemStockUnit = $data['unit'];
        $this->itemStockQuantity = 0;
        unset($data['unit']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->itemStock()->create([
            'unit' => $this->itemStockUnit,
            'quantity' => $this->itemStockQuantity,
        ]);
    }
}
