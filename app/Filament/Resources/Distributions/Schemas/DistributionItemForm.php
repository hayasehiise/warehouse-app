<?php

namespace App\Filament\Resources\Distributions\Schemas;

use App\Models\Item;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class DistributionItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_id')
                    ->label('Barang')
                    ->relationship('item', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('qty')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->rule(function (Get $get) {
                        return function (
                            string $attribute,
                            $value,
                            $fail
                        ) use ($get) {
                            $item = Item::find($get('item_id'));
                            if (! $item) {
                                return;
                            }

                            if ($item->itemStock->quantity < $value) {
                                $fail('Jumlah stok tidak cukup!');
                            }
                        };
                    }),
            ]);
    }
}
