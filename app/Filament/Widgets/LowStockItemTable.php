<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Item;
use Filament\Tables\Columns\TextColumn;


class LowStockItemTable extends TableWidget
{
    protected static ?string $pollingInterval = '5s';

    protected static ?string $heading = 'Low Stock Item Table';

    protected static ?string $description = 'Low stock item berdasarkan data pengeluaran';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Item::query()
                ->with(['itemStock', 'itemCategory'])
                ->whereHas('itemStock', fn ($query) => $query->where('quantity', '<=', 10))
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Barang'),
                TextColumn::make('itemCategory.name')
                    ->label('Kategori'),
                TextColumn::make('itemStock.quantity')
                    ->label('Total Stock'),
                TextColumn::make('stock_status')
                    ->label('Status')
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
                    ->state(function ($record) {
                        $stock = $record->itemStock->quantity;
                        if ($stock <= 0) {
                            return 'Stock Habis';
                        }
                        if ($stock <= 10) {
                            return 'Stock Rendah';
                        }

                        return 'Stock Tersedia';
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ])
            ->paginated(['10', '15', '20']);
    }
}
