<?php

namespace App\Filament\Resources\Inventories;

use App\Filament\Resources\Inventories\Pages\ListInventories;
use App\Filament\Resources\Inventories\Pages\ViewInventory;
use App\Filament\Resources\Inventories\Pages\ViewinventoryDistribution;
use App\Filament\Resources\Inventories\RelationManagers\InventoryTransactionsRelationManager;
use App\Filament\Resources\Inventories\Schemas\ItemInfolist;
use App\Filament\Resources\Inventories\Tables\InventoriesTable;
use App\Models\Item;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static string|BackedEnum|null $navigationIcon = 'lucide-archive';

    protected static ?string $navigationLabel = 'Transaksi Gudang';

    protected static ?string $modelLabel = 'Transaksi Gudang';

    protected static ?string $pluralModelLabel = 'Transaksi Gudang';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi Barang';
    }

    public static function table(Table $table): Table
    {
        return InventoriesTable::configure($table)
            ->recordUrl(fn ($record) => InventoryResource::getUrl('view', [
                'record' => $record,
            ]));
    }

    public static function getRelations(): array
    {
        return [
            InventoryTransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventories::route('/'),
            'view' => ViewInventory::route('/{record}'),
            'distribution' => ViewinventoryDistribution::route('/{record}/distribution'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ItemInfolist::configure($schema);
    }
}
