<?php

namespace App\Filament\Resources\Distributions\Pages;

use App\Filament\Resources\Distributions\DistributionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDistribution extends CreateRecord
{
    protected static string $resource = DistributionResource::class;

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.distributions.view', [
            'record' => $this->record,
        ]);
    }
}
