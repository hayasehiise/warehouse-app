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
                    ->visible(fn ($record) => in_array($record->approve_status, ['pending']) && ! $record->trashed())
                    ->authorize('approval')
                    ->form([
                        TextArea::make('approved_note')
                            ->label('Catatan Approve')
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
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
                        TextArea::make('approved_note')
                            ->label('Catatan Reject')
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $record->reject($data['approved_note']);

                        Notification::make()
                            ->title('Distribusi berhasil direject')
                            ->success()
                            ->send();

                        $this->redirect(route('filament.admin.resources.distributions.view', $record));
                    }),
                DeleteAction::make()
                    ->visible(fn ($record) => ! $record->trashed())
                    ->icon('lucide-trash'),
                ForceDeleteAction::make()
                    ->visible(fn ($record) => $record->trashed())
                    ->icon('lucide-trash-2'),
                RestoreAction::make()
                    ->visible(fn ($record) => $record->trashed())
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
