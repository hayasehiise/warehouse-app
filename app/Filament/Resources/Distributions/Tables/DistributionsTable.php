<?php

namespace App\Filament\Resources\Distributions\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
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
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->visible(fn ($record) => ! $record->trashed()),
                    DeleteAction::make()
                        ->visible(fn ($record) => (in_array($record->approve_status, ['pending', 'rejected']) || auth()->user()->hasAnyRole(['admin', 'supervisor'])) && ! $record->trashed()),
                    RestoreAction::make()
                        ->visible(fn ($record) => $record->trashed() && auth()->user()->hasAnyRole(['admin', 'supervisor'])),
                    ForceDeleteAction::make()
                        ->visible(fn ($record) => $record->trashed() && auth()->user()->hasAnyRole(['admin', 'supervisor'])),
                ])
                    ->icon('lucide-ellipsis-vertical')
                    ->tooltip('Tindakan'),
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
