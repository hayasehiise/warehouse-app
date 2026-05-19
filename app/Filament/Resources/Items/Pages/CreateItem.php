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
            $this->getCreateFormAction()->label('Save'),
            $this->getCreateAnotherFormAction()->label('Save & Create Another'),
            $this->getCancelFormAction()->label('Cancel'),
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
