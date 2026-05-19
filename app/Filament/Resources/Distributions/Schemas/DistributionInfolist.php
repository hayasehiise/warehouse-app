<?php

namespace App\Filament\Resources\Distributions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DistributionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('distribution_code')
                    ->label('Kode Distribusi'),
                TextEntry::make('distribution_date')
                    ->label('Tanggal Distribusi'),
                TextEntry::make('recipient_name')
                    ->label('Penerima'),
                TextEntry::make('note')
                    ->label('Catatan'),
                TextEntry::make('approve_status')
                    ->label('Status')
                    ->state(function ($record) {
                        return match ($record->approve_status) {
                            'pending' => 'Belum diapprove',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                        };
                    })
                    ->badge()
                    ->color(fn ($record) => match ($record->approve_status) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                TextEntry::make('approved_note')
                    ->label('Catatan Approve')
                    ->state(fn ($record) => $record->approve_status === 'pending' ? 'Belum diapprove' : $record->approved_note),
                TextEntry::make('approved_at')
                    ->label('Tanggal Approve')
                    ->state(fn ($record) => $record->approve_status === 'pending' ? 'Belum diapprove' : $record->approved_at),
                TextEntry::make('approvedBy.name')
                    ->label('Disetujui Oleh')
                    ->state(fn ($record) => $record->approve_status === 'pending' ? 'Belum diapprove' : $record->approvedBy->name),
                TextEntry::make('createdBy.name')
                    ->label('Dibuat Oleh'),
                TextEntry::make('created_at')
                    ->label('Tanggal Dibuat'),
            ]);
    }
}
