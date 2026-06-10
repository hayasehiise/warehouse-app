<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TopItemChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Top Item Chart';

    protected ?string $description = 'Top 10 item berdasarkan data pengeluaran';

    protected function getData(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? now()->startOfMonth();
        $endDate = $this->pageFilters['endDate'] ?? now()->endOfMonth();

        $distributionFilter = function ($query) use ($startDate, $endDate) {
            $query->whereHas('distribution', fn ($q) => $q->where('approve_status', 'approved')->whereBetween('distribution_date', [$startDate, $endDate]));
        };

        $item = Item::query()
            ->whereHas('distributionItems', $distributionFilter)
            ->withSum([
                'distributionItems as sum_total_distribution' => $distributionFilter,
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

    protected function getOptions(): array
    {
        return [
            'animation' => [
                'y' => [
                    'duration' => 500,
                    'easing' => 'easeOutCubic',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
