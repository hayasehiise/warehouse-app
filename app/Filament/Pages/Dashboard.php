<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BaseDashboard
{
    public function getHeaderWidgets(): array
    {
        return [
            FilamentInfoWidget::class,
        ];
    }
}
