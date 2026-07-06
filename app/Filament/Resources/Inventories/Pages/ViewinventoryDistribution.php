<?php

namespace App\Filament\Resources\Inventories\Pages;

use App\Filament\Resources\Inventories\InventoryResource;
use App\Filament\Resources\Inventories\RelationManagers\DistributionItemsRelationManager;
use App\Filament\Resources\Inventories\Schemas\ItemInfolist;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewinventoryDistribution extends ViewRecord
{
    protected static string $resource = InventoryResource::class;

    protected static ?string $title = 'Lihat Distribusi Stock';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('lucide-arrow-left')
                ->color('gray')
                ->url(route('filament.admin.resources.inventories.index')),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return ItemInfolist::configure($schema);
    }

    public function getRelationManagers(): array
    {
        return [
            DistributionItemsRelationManager::class,
        ];
    }
}
