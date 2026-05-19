<?php

namespace App\Filament\Resources\ItemCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ItemCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Category Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->hidden(fn ($record) => $record->trashed())
                    ->label('')
                    ->tooltip('Edit')
                    ->size(Size::Large),
                DeleteAction::make()
                    ->hidden(fn ($record) => $record->trashed())
                    ->label('')
                    ->tooltip('Delete')
                    ->size(Size::Large),
                RestoreAction::make()
                    ->hidden(fn ($record) => ! $record->trashed())
                    ->label('')
                    ->tooltip('Restore')
                    ->size(Size::Large),
                ForceDeleteAction::make()
                    ->hidden(fn ($record) => ! $record->trashed())
                    ->label('')
                    ->tooltip('Delete Permanently')
                    ->size(Size::Large),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption(10)
            ->paginated([
                10,
                25,
                50,
                100,
            ]);
    }
}
