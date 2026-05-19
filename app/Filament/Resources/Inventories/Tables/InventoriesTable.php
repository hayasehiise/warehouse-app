<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('itemStock.quantity')
                    ->label('Stock Tersedia')
                    ->badge()
                    ->color(function ($record) {
                        $stock = $record->itemStock->quantity;
                        if ($stock <= 0) {
                            return 'danger';
                        }
                        if ($stock <= 10) {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->sortable(),
                TextColumn::make('inventory_transactions_count')
                    ->label('Total Transaksi')
                    ->counts('inventoryTransactions')
                    ->badge(),
                TextColumn::make('stock_status')
                    ->label('Status Stock')
                    ->state(function ($record) {
                        $stock = $record->itemStock->quantity;
                        if ($stock <= 0) {
                            return 'Stock Habis';
                        }
                        if ($stock <= 10) {
                            return 'Stock Rendah';
                        }

                        return 'Stock Tersedia';
                    })
                    ->badge()
                    ->color(function ($record) {
                        $stock = $record->itemStock->quantity;
                        if ($stock <= 0) {
                            return 'danger';
                        }
                        if ($stock <= 10) {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->sortable(),
            ]);
    }
}
