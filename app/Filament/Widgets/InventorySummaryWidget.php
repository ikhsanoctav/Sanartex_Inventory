<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\Widget;

class InventorySummaryWidget extends Widget
{
    protected string $view = 'filament.widgets.inventory-summary-widget';
    protected static ?int $sort = 1;
    
    protected ?string $pollingInterval = '10s';
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $normalProducts = Product::where('status_stok', 'NORMAL')->get();
        $kritisProducts = Product::where('status_stok', 'KRITIS')->get();
        $berlebihProducts = Product::where('status_stok', 'BERLEBIH')->get();

        return [
            'normalCount' => $normalProducts->count(),
            'kritisCount' => $kritisProducts->count(),
            'berlebihCount' => $berlebihProducts->count(),
            'normalProducts' => $normalProducts,
            'kritisProducts' => $kritisProducts,
            'berlebihProducts' => $berlebihProducts,
        ];
    }
}
