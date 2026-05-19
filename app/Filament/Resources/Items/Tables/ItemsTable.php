<?php

namespace App\Filament\Resources\Items\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('itemCategory.name')
                    ->label('Item Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('itemStock.unit')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make()
                    ->visible(fn () => auth()->user()->can('item.deleted.view')),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record) => auth()->user()->can('item.update') && ! $record->trashed())
                    ->label('')
                    ->icon('lucide-edit')
                    ->tooltip('Edit Barang')
                    ->size('lg'),
                DeleteAction::make()
                    ->visible(fn ($record) => auth()->user()->can('item.delete') && ! $record->trashed())
                    ->label('')
                    ->icon('lucide-trash-2')
                    ->tooltip('Hapus Barang')
                    ->size('lg'),
                ForceDeleteAction::make()
                    ->visible(fn ($record) => auth()->user()->can('item.force-delete') && $record->trashed())
                    ->label('')
                    ->icon('lucide-trash-2')
                    ->tooltip('Hapus Permanen Barang')
                    ->size('lg'),
                RestoreAction::make()
                    ->visible(fn ($record) => auth()->user()->can('item.restore') && $record->trashed())
                    ->label('')
                    ->icon('lucide-rotate-ccw')
                    ->tooltip('Restore Barang')
                    ->size('lg'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->can('item.delete')),
                    ForceDeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->can('item.force-delete')),
                    RestoreBulkAction::make()
                        ->visible(fn () => auth()->user()->can('item.restore')),
                ]),
            ]);
    }
}
