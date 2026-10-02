<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Filament\Widgets\Widget;

class ProductStatsWidget extends Widget
{
    protected int | string | array $columnSpan = 'full';
    
    protected string $view = 'filament.resources.products.widgets.product-stats-widget';

    public function getViewData(): array
    {
        $totalSkuProducts = Product::all();
        $normalProducts = Product::where('status_stok', 'NORMAL')->get();
        $kritisProducts = Product::where('status_stok', 'KRITIS')->get();
        $berlebihProducts = Product::where('status_stok', 'BERLEBIH')->get();

        return [
            'totalSku' => $totalSkuProducts->count(),
            'normal' => $normalProducts->count(),
            'kritis' => $kritisProducts->count(),
            'berlebih' => $berlebihProducts->count(),
            'totalSkuProducts' => $totalSkuProducts,
            'normalProducts' => $normalProducts,
            'kritisProducts' => $kritisProducts,
            'berlebihProducts' => $berlebihProducts,
        ];
    }
}
