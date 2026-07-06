<?php

namespace App\Filament\Resources\Items\Pages;

use App\Filament\Resources\Items\ItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('lucide-trash'),
            ForceDeleteAction::make()
                ->icon('lucide-trash-2'),
            RestoreAction::make()
                ->icon('lucide-rotate-ccw'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Simpan')->icon('lucide-save'),
            $this->getCancelFormAction()->label('Batal')->icon('lucide-x'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->itemStockUnit = $data['unit'];
        unset($data['unit']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->itemStock()->update([
            'unit' => $this->itemStockUnit,
        ]);
    }
}
