<?php

namespace App\Filament\Pages;

use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                DatePicker::make('startDate')
                    ->default(now()->startOfMonth()),
                DatePicker::make('endDate')
                    ->default(now()->endOfMonth()),
            ]);
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('printReport')
                ->label('Print Report')
                ->icon('lucide-printer')
                ->color('primary')
                ->action(function () {
                    // take latest state from filtersForm
                    $filters = $this->filtersForm->getState();
                    $startDate = $filters['startDate'];
                    $endDate = $filters['endDate'];

                    // make sure state format safe for query
                    $startCarbon = Carbon::parse($startDate)->startOfDay();
                    $endCarbon = Carbon::parse($endDate)->endOfDay();

                    // get the query here
                    $items = Item::with(['itemCategory', 'itemStock'])
                        ->orderBy('name', 'asc')
                        ->get()
                        ->map(function ($item) use ($startCarbon, $endCarbon) {
                            // sum stock total based on date transaction
                            $totalIn = $item->inventoryTransactions()
                                ->withoutTrashed()
                                ->where('type', 'IN')
                                ->where('approve_status', 'APPROVED')
                                ->sum('quantity');
                            $totalOut = $item->inventoryTransactions()
                                ->withoutTrashed()
                                ->where('type', 'OUT')
                                ->where('approve_status', 'APPROVED')
                                ->sum('quantity');
                            $totalStock = $totalIn - $totalOut;

                            // sum stock distribution based on date transaction
                            $distributed = $item->distributionItems()
                                ->whereHas('distribution', function ($query) use ($startCarbon, $endCarbon) {
                                    $query->whereBetween('distribution_date', [$startCarbon, $endCarbon])
                                        ->where('approve_status', 'approved')
                                        ->withoutTrashed();
                                })
                                ->sum('qty');

                            return [
                                'item_name' => $item->name,
                                'item_sku' => $item->sku,
                                'category_name' => $item->itemCategory->name ?? 'No Category',
                                'stock_total' => $totalStock,
                                'stock_distributed' => (int) $distributed,
                                'available_stock' => $item->itemStock->quantity ?? 0,
                            ];
                        });

                    // generate PDF
                    $pdf = Pdf::loadView('reports.stock-summary', [
                        'reportData' => $items,
                        'startDate' => $startCarbon->format('d/m/Y'),
                        'endDate' => $endCarbon->format('d/m/Y'),
                        'printedAt' => now()->format('d F Y, H:i:s'),
                    ]);

                    // download PDF
                    return response()->streamDownload(fn () => print ($pdf->output()), 'Laporan-Stock-Summary-'.now()->format('Y-m-d').'.pdf');
                }),
        ];
    }
}
