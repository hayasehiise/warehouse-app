<?php

namespace App\Filament\Resources\Distributions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DistributionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('distribution_code')
                    ->label('Kode Distribusi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('distribution_date')
                    ->label('Tanggal Distribusi')
                    ->date('d-m-Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('recipient_name')
                    ->label('Penerima')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('note')
                    ->label('Catatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('approve_status')
                    ->badge()
                    ->label('Status')
                    ->sortable()
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->colors([
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    ]),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
