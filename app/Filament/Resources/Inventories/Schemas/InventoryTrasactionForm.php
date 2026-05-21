<?php

namespace App\Filament\Resources\Inventories\Schemas;

use App\Models\Item;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class InventoryTrasactionForm
{
    public static function configure(Schema $schema, Item $item): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Tipe Transaksi')
                    ->options(function () use ($item) {
                        $stock = $item?->itemStock?->quantity;

                        return $stock == 0 ? [
                            'IN' => 'Masuk',
                        ] : [
                            'IN' => 'Masuk',
                            'OUT' => 'Keluar',
                        ];
                    })
                    ->live()
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options(function (Get $get) {
                        $type = $get('type');

                        return $type === 'IN' ?
                            [
                                'GOOD' => 'Baik',
                            ] : [
                                'DAMAGED' => 'Rusak',
                                'EXPIRED' => 'Kadaluarsa',
                                'MISSING' => 'Hilang',
                            ];
                    })
                    ->required(),
                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required(),
                DatePicker::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->required(),
                TextArea::make('approved_note')
                    ->label('Catatan Approval')
                    ->rows(2)
                    ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'supervisor']))
                    ->required(fn () => auth()->user()->hasAnyRole(['admin', 'supervisor'])),
            ]);
    }
}
