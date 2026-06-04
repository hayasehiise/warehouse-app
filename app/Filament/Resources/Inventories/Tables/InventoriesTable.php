<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
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
                TextColumn::make('distribution_items_count')
                    ->label('Total Distribusi')
                    ->counts('distributionItems')
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
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('distribution')
                    ->label('Distribusi')
                    ->icon('lucide-arrow-up-right')
                    ->url(fn ($record) => route('filament.admin.resources.inventories.distribution', $record)),
            ]);
    }
}
