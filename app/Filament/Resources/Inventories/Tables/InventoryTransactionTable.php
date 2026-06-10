<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextArea;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class InventoryTransactionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->label('Transaksi Gudang')
                    ->icon('lucide-plus')
                    ->modalSubmitAction(fn (Action $action): Action => $action->label('Simpan')->icon('lucide-save'))
                    ->modalCancelAction(fn (Action $action): Action => $action->label('Kembali')->icon('lucide-arrow-left'))
                    ->createAnotherAction(fn (Action $action): Action => $action->label('Simpan & Tambah')->icon('lucide-plus'))
                    ->modalHeading('Buat Transaksi Gudang')
                    ->after(function ($record) {
                        return redirect(route('filament.admin.resources.inventories.view', [
                            'record' => $record->item,
                        ]));
                    }),
            ])
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->date()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe Transaksi')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'IN' => 'Masuk',
                        'OUT' => 'Keluar',
                        default => $state,
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'IN' => 'success',
                            'OUT' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'GOOD' => 'Baik',
                        'DAMAGED' => 'Rusak',
                        'EXPIRED' => 'Kadaluarsa',
                        'MISSING' => 'Hilang',
                        default => $state,
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'GOOD' => 'success',
                            'DAMAGED' => 'danger',
                            'EXPIRED' => 'danger',
                            'MISSING' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->badge()
                    ->color(function ($state, $record) {
                        return match ($record->type) {
                            'IN' => 'success',
                            'OUT' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->sortable(),
                TextColumn::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->sortable(),
                TextColumn::make('approve_status')
                    ->label('Status Approval')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'PENDING' => 'Menunggu Approval',
                        'REJECTED' => 'Ditolak',
                        'APPROVED' => 'Disetujui',
                        default => $state,
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'PENDING' => 'warning',
                            'REJECTED' => 'danger',
                            'APPROVED' => 'success',
                            default => 'gray',
                        };
                    })
                    ->sortable(),
                TextColumn::make('approvedBy.name')
                    ->label('Disetujui Oleh')
                    ->default('Menunggu Approval')
                    ->sortable(),
                TextColumn::make('approved_note')
                    ->label('Catatan Approval')
                    ->default('Menunggu Approval')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                Filter::make('transaction_filter')
                    ->label('Filters')
                    ->form([
                        Grid::make(1)
                            ->schema([
                                // Type Selection
                                Select::make('type')
                                    ->label('Type')
                                    ->placeholder('All')
                                    ->options([
                                        'IN' => 'Masuk',
                                        'OUT' => 'Keluar',
                                    ])
                                    ->native(false),
                                // Status Selection
                                Select::make('approve_status')
                                    ->label('Approval')
                                    ->placeholder('All')
                                    ->options([
                                        'PENDING' => 'Pending',
                                        'REJECTED' => 'Rejected',
                                        'APPROVED' => 'Approved',
                                    ])
                                    ->native(false),
                                // Item Status Selection
                                Select::make('status')
                                    ->label('Status')
                                    ->placeholder('All')
                                    ->options([
                                        'GOOD' => 'Baik',
                                        'DAMAGED' => 'Rusak',
                                        'EXPIRED' => 'Kadaluarsa',
                                        'MISSING' => 'Hilang',
                                    ])
                                    ->native(false),
                            ])
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('start')
                                    ->label('From')
                                    ->displayFormat('d M Y'),
                                DatePicker::make('end')
                                    ->label('To')
                                    ->displayFormat('d M Y'),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['type'] ?? null, fn ($q, $type) => $q->where('type', $type))
                            ->when($data['approve_status'] ?? null, fn ($q, $approve_status) => $q->where('approve_status', $approve_status))
                            ->when($data['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
                            ->when($data['start'] ?? null, fn ($q, $start) => $q->whereDate('transaction_date', '>=', $start))
                            ->when($data['end'] ?? null, fn ($q, $end) => $q->whereDate('transaction_date', '<=', $end));
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->approve_status === 'PENDING')
                        ->authorize('approval')
                        ->form([
                            TextArea::make('approved_note')
                                ->label('Catatan Approval')
                                ->rows(2)
                                ->required(),
                        ])
                        ->action(function ($record, $data) {
                            $record->approve($data['approved_note']);
                        })
                        ->after(function ($record) {
                            return redirect(route('filament.admin.resources.inventories.view', [
                                'record' => $record->item,
                            ]));
                        }),
                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => $record->approve_status === 'PENDING')
                        ->authorize('approval')
                        ->form([
                            TextArea::make('approved_note')
                                ->label('Catatan Penolakan')
                                ->rows(2)
                                ->required(),
                        ])
                        ->action(function ($record, $data) {
                            $record->reject($data['approved_note']);
                        })
                        ->after(function ($record) {
                            return redirect(route('filament.admin.resources.inventories.view', [
                                'record' => $record->item,
                            ]));
                        }),
                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->approve_status === 'PENDING' && ! $record->trashed())
                        ->color('danger')
                        ->icon('lucide-trash')
                        ->after(function ($record) {
                            return redirect(route('filament.admin.resources.inventories.view', [
                                'record' => $record->item,
                            ]));
                        }),
                    RestoreAction::make()
                        ->visible(fn ($record) => $record->trashed())
                        ->color('success')
                        ->icon('lucide-rotate-ccw')
                        ->after(function ($record) {
                            return redirect(route('filament.admin.resources.inventories.view', [
                                'record' => $record->item,
                            ]));
                        }),
                    ForceDeleteAction::make()
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->trashed())
                        ->after(function ($record) {
                            return redirect(route('filament.admin.resources.inventories.view', [
                                'record' => $record->item,
                            ]));
                        }),
                ]),
            ]);
    }
}
