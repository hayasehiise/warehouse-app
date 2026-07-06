<?php

namespace App\Filament\Resources\Distributions\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DistributionItemTable
{
    public static function configure(Table $table, Component $livewire): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.name')
                    ->label('Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('qty')
                    ->label('Jumlah')
                    ->badge()
                    ->color('info')
                    ->numeric(),
                TextColumn::make('item.itemStock.unit')
                    ->label('Satuan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('lucide-plus')
                    ->label('Barang')
                    ->modalSubmitAction(fn (Action $action): Action => $action->label('Simpan')->color('primary')->icon('lucide-save'))
                    ->modalCancelAction(fn (Action $action): Action => $action->label('Kembali')->icon('lucide-arrow-left'))
                    ->createAnotherAction(fn (Action $action): Action => $action->label('Simpan & Tambah')->color('primary')->icon('lucide-plus'))
                    ->before(function (array $data) use ($livewire) {
                        $exist = $livewire->ownerRecord->distributionItems()->where('item_id', $data['item_id'])->exists();
                        if ($exist) {
                            Notification::make()
                                ->title('Barang sudah ada di list!')
                                ->danger()
                                ->send();
                            throw ValidationException::withMessages([
                                'item_id' => 'Barang sudah ada di list!',
                            ]);
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->hidden(fn ($record) => $record->approve_status === 'approved')
                    ->modalSubmitAction(fn (Action $action): Action => $action->label('Simpan')->color('primary')->icon('lucide-save'))
                    ->modalCancelAction(fn (Action $action): Action => $action->label('Kembali')->icon('lucide-arrow-left')),
                DeleteAction::make()
                    ->modalHeading('Hapus Data')
                    ->modalDescription(function ($record) {
                        return 'Apakah anda yakin ingin menghapus '.$record->item->name.' (Jumlah: '.number_format($record->qty, 0, ',', '.').' '.$record->item->itemStock->unit.') dari list?';
                    })
                    ->modalSubmitAction(fn (Action $action): Action => $action->label('Hapus')->color('danger')->icon('lucide-trash'))
                    ->modalCancelAction(fn (Action $action): Action => $action->label('Kembali')->icon('lucide-arrow-left')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
