<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopItemTable extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Top Item Table';

    protected static ?string $description = 'Top 10 item berdasarkan data pengeluaran';

    public function table(Table $table): Table
    {
        $startDate = $this->pageFilters['startDate'] ?? now()->startOfMonth();
        $endDate = $this->pageFilters['endDate'] ?? now()->endOfMonth();

        return $table
            ->query(fn (): Builder => Item::query()
                ->whereHas('distributionItems')
                ->withSum([
                    'distributionItems as total_out' => function ($query) use ($startDate, $endDate) {
                        $query->whereHas('distribution', function ($q) use ($startDate, $endDate) {
                            $q->where('approve_status', 'approved')
                                ->whereBetween('distribution_date', [$startDate, $endDate]);
                        });
                    },
                ], 'qty')
                ->orderByDesc('total_out')
                ->limit(10)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Barang'),
                TextColumn::make('total_out')
                    ->label('Total Pengeluaran')
                    ->numeric(),
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
            ->paginated(false);
    }
}
