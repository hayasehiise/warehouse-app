<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
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
                    ->after(function () {
                        $this->dispatch('refreshView');
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
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->badge()
                    ->sortable(),
                TextColumn::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->sortable(),
                TextColumn::make('approve_status')
                    ->label('Status Approval')
                    ->badge()
                    ->sortable(),
                TextColumn::make('approvedBy.name')
                    ->label('Disetujui Oleh')
                    ->sortable(),
                TextColumn::make('approved_note')
                    ->label('Catatan Approval')
                    ->formatStateUsing(function ($state, $record) {
                        return match ($record->approve_status) {
                            'PENDING' => 'Menunggu Approval',
                            'REJECTED' => $state ?? 'Ditolak',
                            'APPROVED' => $state ?? 'Disetujui',
                            default => $state ?? '-',
                        };
                    })
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
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->approve_status === 'PENDING' && auth()->user()->hasAnyRole(['admin', 'supervisor']))
                    ->form([
                        TextArea::make('approved_note')
                            ->label('Catatan Approval')
                            ->rows(2)
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $record->approve_status = 'APPROVED';
                        $record->approved_by = auth()->id();
                        $record->approved_note = $data['approved_note'];
                        $record->save();
                    })
                    ->after(fn () => $this->dispatch('refreshView')),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->approve_status === 'PENDING' && auth()->user()->hasAnyRole(['admin', 'supervisor']))
                    ->form([
                        TextArea::make('approved_note')
                            ->label('Catatan Penolakan')
                            ->rows(2)
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $record->approve_status = 'REJECTED';
                        $record->approved_by = auth()->id();
                        $record->approved_note = $data['approved_note'];
                        $record->save();
                    })
                    ->after(fn () => $this->dispatch('refreshView')),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => auth()->user()->hasAnyRole(['admin', 'supervisor']) || (auth()->user()->hasRole('staff') && $record->approve_status === 'PENDING'))
                    ->action(fn ($record) => $record->delete())
                    ->color('danger')
                    ->icon('lucide-trash')
                    ->after(fn () => $this->dispatch('refreshView')),
                RestoreAction::make()
                    ->visible(fn ($record) => $record->trashed() && auth()->user()->hasAnyRole(['admin', 'supervisor']))
                    ->color('success')
                    ->icon('lucide-rotate-ccw')
                    ->after(fn () => $this->dispatch('refreshView')),
                ForceDeleteAction::make()
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->trashed() && auth()->user()->hasAnyRole(['admin', 'supervisor']))
                    ->after(fn () => $this->dispatch('refreshView')),
            ]);
    }
}
