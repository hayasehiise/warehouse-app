<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DistributionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->whereHas('distribution', fn ($query) => $query->withoutTrashed()))
            ->columns([
                TextColumn::make('distribution.distribution_code')
                    ->label('Kode Distribusi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('distribution.distribution_date')
                    ->label('Tanggal Distribusi')
                    ->date('d-m-Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('distribution.recipient_name')
                    ->label('Penerima')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('distribution.approve_status')
                    ->badge()
                    ->label('Status')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Tertunda',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    })
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                TextColumn::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->badge()
                    ->color('info'),
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
                //
            ]);
    }
}
