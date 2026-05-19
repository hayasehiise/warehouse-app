<?php

namespace App\Filament\Resources\Distributions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DistributionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('distribution_date')
                    ->label('Tanggal Distribusi')
                    ->required(),
                TextInput::make('recipient_name')
                    ->label('Penerima')
                    ->required(),
                Textarea::make('note')
                    ->label('Catatan')
                    ->nullable(),

            ]);
    }
}
