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
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                Filter::make('approve_status')
                    ->label('Status')
                    ->form([
                        Select::make('approve_status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Tertunda',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['approve_status'], fn (Builder $query, $status) => $query->where('approve_status', $status));
                    })
                    ->indicateUsing(function (array $data) {
                        if ($data['approve_status']) {
                            return match ($data['approve_status']) {
                                'pending' => 'Tertunda',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            };
                        }
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->visible(fn ($record) => ! $record->trashed()),
                    DeleteAction::make()
                        ->visible(fn ($record) => ! $record->trashed()),
                    RestoreAction::make()
                        ->visible(fn ($record) => $record->trashed()),
                    ForceDeleteAction::make()
                        ->visible(fn ($record) => $record->trashed()),
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
