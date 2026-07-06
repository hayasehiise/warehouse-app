<?php

namespace App\Filament\Resources\ItemCategories\Pages;

use App\Filament\Resources\ItemCategories\ItemCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditItemCategory extends EditRecord
{
    protected static string $resource = ItemCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('lucide-trash'),
            ForceDeleteAction::make()
                ->icon('lucide-trash'),
            RestoreAction::make()
                ->icon('lucide-rotate-ccw'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSubmitFormAction()->label('Simpan')->color('primary')->icon('lucide-save'),
            $this->getCancelFormAction()->label('Kembali')->icon('lucide-arrow-left'),
        ];
    }
}
