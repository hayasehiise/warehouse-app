<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_category_id')
                    ->label('Item Category')
                    ->relationship('itemCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('unit')
                    ->label('Unit')
                    ->afterStateHydrated(function ($component, $record) {
                        $component->state($record?->itemStock->unit ?? '');
                    })
                    ->required(),
                TextArea::make('description')
                    ->label('Description'),
            ]);
    }
}
