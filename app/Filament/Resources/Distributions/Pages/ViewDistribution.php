<?php

namespace App\Filament\Resources\Distributions\Pages;

use App\Filament\Resources\Distributions\DistributionResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\TextArea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

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
                    ->visible(fn ($record) => in_array($record->approve_status, ['pending', 'rejected']) && auth()->user()->hasAnyRole(['admin', 'supervisor']) && ! $record->trashed())
                    ->form([
                        TextArea::make('approved_note')
                            ->label('Catatan Approve')
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'approve_status' => 'approved',
                            'approved_note' => $data['approved_note'],
                        ]);

                        Notification::make()
                            ->title('Distribusi berhasil diapprove')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn ($record) => in_array($record->approve_status, ['pending']) && auth()->user()->hasAnyRole(['admin', 'supervisor']) && ! $record->trashed())
                    ->form([
                        TextArea::make('approved_note')
                            ->label('Catatan Reject')
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'approve_status' => 'rejected',
                            'approved_note' => $data['approved_note'],
                        ]);

                        Notification::make()
                            ->title('Distribusi berhasil direject')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make()
                    ->visible(fn ($record) => ! $record->trashed() && auth()->user()->hasAnyRole(['admin', 'supervisor']))
                    ->icon('lucide-trash'),
                ForceDeleteAction::make()
                    ->visible(fn ($record) => $record->trashed() && auth()->user()->hasAnyRole(['admin', 'supervisor']))
                    ->icon('lucide-trash-2'),
                RestoreAction::make()
                    ->visible(fn ($record) => $record->trashed() && auth()->user()->hasAnyRole(['admin', 'supervisor']))
                    ->color('success')
                    ->icon('lucide-rotate-ccw'),
            ])
                ->buttonGroup(),
            Action::make('back')
                ->label('Kembali')
                ->color('gray')
                ->icon('heroicon-o-arrow-left')
                ->url(route('filament.admin.resources.distributions.index')),
        ];
    }
}
