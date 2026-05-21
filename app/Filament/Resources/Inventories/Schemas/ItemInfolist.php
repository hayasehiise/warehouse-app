<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama Barang :'),
                TextEntry::make('sku')
                    ->label('SKU :'),
                TextEntry::make('itemCategory.name')
                    ->label('Kategori :'),
                TextEntry::make('itemStock.quantity')
                    ->label('Quantity :')
                    ->badge(),
                TextEntry::make('itemStock.unit')
                    ->label('Unit :'),
                TextEntry::make('description')
                    ->label('Deskripsi :'),
            ]);
    }
}
