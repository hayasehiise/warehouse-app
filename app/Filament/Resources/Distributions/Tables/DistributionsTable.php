<?php

namespace App\Filament\Resources\Distributions\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
                    ->date('d/m/Y')
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
                        ->visible(fn ($record) => ! $record->trashed() && (auth()->id() == $record->created_by || auth()->user()->hasAnyRole(['admin', 'supervisor'])))
                        ->modalDescription('Apakah anda yakin ingin menghapus data?'),
                    RestoreAction::make()
                        ->visible(fn ($record) => $record->trashed() && (auth()->id() == $record->created_by || auth()->user()->hasAnyRole(['admin', 'supervisor']))),
                    ForceDeleteAction::make()
                        ->visible(fn ($record) => $record->trashed() && (auth()->id() == $record->created_by || auth()->user()->hasAnyRole(['admin', 'supervisor']))),
                ])
                    ->icon('lucide-ellipsis-vertical')
                    ->tooltip('Tindakan'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    BulkAction::make('approve')
                        ->label('Approve')
                        ->icon('lucide-circle-check')
                        ->color('success')
                        ->form([
                            Textarea::make('approved_note')
                                ->label('Catatan Approve')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            // check if records have selected except pending and with another conditions
                            $invalidRecords = $records->filter(function ($record) {
                                return $record->approve_status !== 'pending' || $record->trashed();
                            });

                            // if invalid records found, return early notification
                            if ($invalidRecords->count() > 0) {
                                Notification::make()
                                    ->title('Approve Distribusi gagal')
                                    ->body('Hanya distribusi dengan status "Tertunda" yang belum dihapus yang bisa diapprove')
                                    ->danger()
                                    ->send();

                                return;
                            }
                            // approve process
                            $successCount = 0;
                            $failedCount = 0;
                            foreach ($records as $record) {
                                if ($record->approve_status !== 'pending' || $record->trashed()) {
                                    continue;
                                }

                                if (! $record->distributionItems()->exists()) {
                                    $failedCount++;

                                    continue;
                                }

                                $record->approve($data['approved_note']);
                                $successCount++;
                            }

                            if ($successCount > 0) {
                                Notification::make()
                                    ->title($successCount.' distribusi berhasil diapprove'.($failedCount > 0 ? ', '.$failedCount.' distribusi gagal diapprove' : ''))
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Distribusi gagal diapprove')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->authorize('approvalAny'),
                    BulkAction::make('reject')
                        ->label('Reject')
                        ->icon('lucide-circle-x')
                        ->color('danger')
                        ->form([
                            Textarea::make('approved_note')
                                ->label('Catatan Reject')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            // check if records have selected except pending and with another conditions
                            $invalidRecords = $records->filter(function ($record) {
                                return $record->approve_status !== 'pending' || $record->trashed();
                            });

                            // if invalid records found, return early notification
                            if ($invalidRecords->count() > 0) {
                                Notification::make()
                                    ->title('Reject Distribusi gagal')
                                    ->body('Hanya distribusi dengan status "Tertunda" yang belum dihapus yang bisa direject')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            // reject process
                            $successCount = 0;
                            $failedCount = 0;
                            foreach ($records as $record) {
                                if ($record->approve_status !== 'pending' || $record->trashed()) {
                                    continue;
                                }

                                if (! $record->distributionItems()->exists()) {
                                    $failedCount++;

                                    continue;
                                }

                                $record->reject($data['approved_note']);
                                $successCount++;
                            }

                            if ($successCount > 0) {
                                Notification::make()
                                    ->title($successCount.' distribusi berhasil direject'.($failedCount > 0 ? ', '.$failedCount.' distribusi gagal direject' : ''))
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Distribusi gagal direject')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->authorize('approvalAny'),
                ]),
            ]);
    }
}
