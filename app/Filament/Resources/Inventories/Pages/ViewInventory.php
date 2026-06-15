<?php

namespace App\Filament\Resources\Inventories\Pages;

use App\Filament\Resources\Inventories\InventoryResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Livewire\Attributes\On;

class ViewInventory extends ViewRecord
{
    protected static string $resource = InventoryResource::class;

    #[On('refreshInventory')]
    public function refreshInventory(): void
    {
        $this->record->refresh();

        $this->dispatch('$refresh');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->icon('lucide-arrow-left')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),
        ];
    }
}
