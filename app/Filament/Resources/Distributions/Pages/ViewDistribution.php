<?php

namespace App\Filament\Resources\Distributions\Pages;

use App\Filament\Resources\Distributions\DistributionResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Distribution;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewDistribution extends ViewRecord
{
    protected static string $resource = DistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record) => in_array($record->approve_status, ['pending']) && ! $record->trashed())
                    ->authorize('approval')
                    ->form([
                        Textarea::make('approved_note')
                            ->label('Catatan Approve')
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        if (! $record->distributionItems()->exists()) {
                            Notification::make()
                                ->title('Approve Ditolak')
                                ->body('Anda harus menambahkan item distribusi sebelum melakukan approve')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->approve($data['approved_note']);

                        Notification::make()
                            ->title('Distribusi berhasil diapprove')
                            ->success()
                            ->send();

                        $this->redirect(route('filament.admin.resources.distributions.view', $record));
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn ($record) => in_array($record->approve_status, ['pending']) && ! $record->trashed())
                    ->authorize('approval')
                    ->form([
                        Textarea::make('approved_note')
                            ->label('Catatan Reject')
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        if (! $record->distributionItems()->exists()) {
                            Notification::make()
                                ->title('Reject Ditolak')
                                ->body('Anda harus menambahkan item distribusi sebelum melakukan reject')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->reject($data['approved_note']);

                        Notification::make()
                            ->title('Distribusi berhasil direject')
                            ->success()
                            ->send();

                        $this->redirect(route('filament.admin.resources.distributions.view', $record));
                    }),
            ])->buttonGroup(),
            Action::make('print_spmb')
                    ->label('Print SPMB')
                    ->color('primary')
                    ->icon('lucide-printer')
                    ->visible(fn ($record) => ! $record->trashed() && $record->approve_status === 'approved')
                    ->action(function (Distribution $record) {
                        $record->load(['distributionItems', 'distributionItems.item.itemCategory', 'approvedBy', 'createdBy', 'createdBy.userProfile', 'approvedBy.userProfile']);

                        $pdf = Pdf::loadView('reports.spmb', [
                            'distribution' => $record,
                            'koordinator_name' => 'YANI YULIAWATI, S.Sos., M.M',
                            'kuasa_name' => 'HASTUTY, S.E, M.M'
                        ])
                        ->setPaper('a4', 'portrait')
                        ->setOption('isHtml5ParserEnabled', true)
                        ->setOption('isRemoteEnabled', true);
                        return response()->streamDownload(fn () => print($pdf->output()), 'SPMB-'. $record->distribution_code .'.pdf');
                    }),
            DeleteAction::make()
                ->visible(fn ($record) => ! $record->trashed() && (auth()->id() == $record->created_by || auth()->user()->hasAnyRole(['admin', 'supervisor'])))
                ->modalDescription('Apakah anda yakin ingin menghapus data?')
                ->icon('lucide-trash'),
            ForceDeleteAction::make()
                ->visible(fn ($record) => $record->trashed())
                ->icon('lucide-trash-2'),
            RestoreAction::make()
                ->visible(fn ($record) => $record->trashed())
                ->color('success')
                ->icon('lucide-rotate-ccw'),
            Action::make('back')
                ->label('Kembali')
                ->color('gray')
                ->icon('heroicon-o-arrow-left')
                ->url(route('filament.admin.resources.distributions.index')),
        ];
    }
}
