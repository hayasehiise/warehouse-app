<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Widgets\ChartWidget;

class TopItemChart extends ChartWidget
{
    protected ?string $heading = 'Top Item Chart';

    protected function getData(): array
    {
        $item = Item::query()
            ->whereHas('distributionItems')
            ->withSum([
                'distributionItems as sum_total_distribution' => fn ($query) => $query->whereHas('distribution', fn ($q) => $q->where('approve_status', 'approved')),
            ], 'qty')
            ->orderByDesc('sum_total_distribution')
            ->limit(10)
            ->get();

        $per_item_colors = [
            'rgba(14, 153, 246, 0.5)',
            'rgba(255, 99, 132, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(255, 159, 64, 0.5)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran',
                    'data' => $item->map(fn ($i) => $i->sum_total_distribution),
                    'backgroundColor' => $per_item_colors,
                    'borderColor' => $per_item_colors,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $item->map(fn ($i) => $i->name),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
