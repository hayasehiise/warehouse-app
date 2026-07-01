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
