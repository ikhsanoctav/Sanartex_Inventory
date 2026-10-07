<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockInTransaction;
use App\Models\StockOutTransaction;
use App\Models\User;
use App\Services\RblEvaluatorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil data produk & status RBL (3 Zona: Kritis, Normal, Berlebih)
        $allProducts = Product::orderBy('status_stok', 'asc')->orderBy('nama', 'asc')->get();

        $kritisProducts = $allProducts->where('status_stok', 'KRITIS');
        $normalProducts = $allProducts->where('status_stok', 'NORMAL');
        $berlebihProducts = $allProducts->where('status_stok', 'BERLEBIH');

        // Metrik buffer
        $totalSKU = $allProducts->count();
        $kritisCount = $kritisProducts->count();
        $normalCount = $normalProducts->count();
        $berlebihCount = $berlebihProducts->count();
        
        // Health score buffer (%)
        $healthScore = $totalSKU > 0 ? round(($normalCount / $totalSKU) * 100, 1) : 100;

        // 2. Hitung metrik keuangan & stok fisik
        $totalValuasi = $allProducts->sum(function ($p) {
            return $p->stok_aktual * ($p->harga_beli_per_satuan ?? 0);
        });
        
        $totalStokFisik = $allProducts->sum('stok_aktual');
        $totalKapasitasMaks = $allProducts->sum('batas_maksimum');
        $utilisasiGudang = $totalKapasitasMaks > 0 ? round(($totalStokFisik / $totalKapasitasMaks) * 100, 1) : 0;

        $totalPcs = $allProducts->where('satuan', 'Pcs')->sum('stok_aktual');
        $totalLusin = $allProducts->where('satuan', 'Lusin')->sum('stok_aktual');
        $totalBox = $allProducts->where('satuan', 'Box')->sum('stok_aktual');
        $totalRoll = $totalPcs; // Fallback
        $totalYard = $totalLusin; // Fallback
        $totalMeter = 0;
        $totalKg = 0;

        // 3. Kinerja Mutasi Bulan Ini (Inbound & Outbound)
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');
        
        $startOfLastMonth = now()->subMonth()->startOfMonth()->format('Y-m-d');
        $endOfLastMonth = now()->subMonth()->endOfMonth()->format('Y-m-d');

        $inboundThisMonthQty = (int) StockInTransaction::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('jumlah');
        $inboundThisMonthCount = StockInTransaction::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->count();
        $inboundLastMonthQty = (int) StockInTransaction::whereBetween('tanggal', [$startOfLastMonth, $endOfLastMonth])->sum('jumlah');

        $outboundThisMonthQty = (int) StockOutTransaction::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('jumlah');
        $outboundThisMonthCount = StockOutTransaction::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->count();
        $outboundLastMonthQty = (int) StockOutTransaction::whereBetween('tanggal', [$startOfLastMonth, $endOfLastMonth])->sum('jumlah');

        $inboundGrowth = $inboundLastMonthQty > 0 
            ? round((($inboundThisMonthQty - $inboundLastMonthQty) / $inboundLastMonthQty) * 100, 1) 
            : 0;
            
        $outboundGrowth = $outboundLastMonthQty > 0 
            ? round((($outboundThisMonthQty - $outboundLastMonthQty) / $outboundLastMonthQty) * 100, 1) 
            : 0;

        // 4. Data Tren Multi-Periode (Harian, Mingguan, 3 Bulan/Triwulan, 6 Bulan/Semester, Bulanan/12 Bulan)
        $trendDatasets = $this->getTrendDatasets();
        $monthsLabels = $trendDatasets['semester']['labels'];
        $chartInbound = $trendDatasets['semester']['inbound'];
        $chartOutbound = $trendDatasets['semester']['outbound'];

        // 5. Komposisi Kategori Kain (Donut Chart & Distribution)
        $categoryBreakdown = $allProducts->groupBy('kategori')->map(function ($items, $kategori) use ($totalValuasi) {
            $catStock = $items->sum('stok_aktual');
            $catValuation = $items->sum(function ($p) {
                return $p->stok_aktual * ($p->harga_beli_per_satuan ?? 0);
            });
            $percentage = $totalValuasi > 0 ? round(($catValuation / $totalValuasi) * 100, 1) : 0;
            
            return [
                'name' => $kategori ?: 'Lainnya',
                'sku_count' => $items->count(),
                'total_stock' => $catStock,
                'valuation' => $catValuation,
                'percentage' => $percentage,
            ];
        })->sortByDesc('valuation')->values();

        // 6. Fast-Moving Fabrics (Top 5 Kain Paling Banyak Keluar / Dipakai Produksi)
        $fastMovingProducts = Product::withSum('stockOutTransactions', 'jumlah')
            ->orderByDesc('stock_out_transactions_sum_jumlah')
            ->take(5)
            ->get()
            ->map(function ($p) {
                $outQty = (int) ($p->stock_out_transactions_sum_jumlah ?? 0);
                $turnoverPercent = $p->batas_maksimum > 0 
                    ? min(100, round(($outQty / $p->batas_maksimum) * 100)) 
                    : 50;
                return [
                    'product' => $p,
                    'out_qty' => $outQty,
                    'turnover_percent' => $turnoverPercent,
                ];
            });

        // 7. Zonasi Rak Gudang & Okupansi
        $warehouseRacks = $allProducts->groupBy(function ($p) {
            return $p->lokasi_rak ?: 'Area Buffer Utama';
        })->map(function ($items, $rack) {
            return [
                'rack' => $rack,
                'count' => $items->count(),
                'total_stock' => $items->sum('stok_aktual'),
                'has_kritis' => $items->where('status_stok', 'KRITIS')->count() > 0,
            ];
        })->take(8);

        // 8. Transaksi Masuk & Keluar Terkini
        $recentInbound = StockInTransaction::with(['product', 'user'])
            ->latest('tanggal')
            ->latest('id')
            ->take(6)
            ->get();

        $recentOutbound = StockOutTransaction::with(['product', 'user'])
            ->latest('tanggal')
            ->latest('id')
            ->take(6)
            ->get();

        // 9. Rekomendasi Reorder Buffer (Stok Kritis & Menipis)
        $reorderSuggestions = $allProducts->filter(function ($p) {
            return $p->status_stok === 'KRITIS' || ($p->stok_aktual <= ($p->reorder_point ?? $p->batas_minimum));
        })->map(function ($p) {
            $suggestedQty = RblEvaluatorService::calculateSuggestedOrder($p);
            $estimatedCost = $suggestedQty * ($p->harga_beli_per_satuan ?? 0);
            return [
                'product' => $p,
                'suggested_qty' => $suggestedQty,
                'estimated_cost' => $estimatedCost,
                'urgency' => $p->status_stok === 'KRITIS' ? 'HIGH' : 'MEDIUM',
            ];
        })->values();

        $totalReorderBudget = $reorderSuggestions->sum('estimated_cost');
        $criticalReorderCount = $reorderSuggestions->where('urgency', 'HIGH')->count();

        // 10. Rekapan Supplier Utama (Khusus Purchasing)
        $topSuppliers = $allProducts->groupBy('supplier_utama')->filter(function ($group, $key) {
            return !empty($key);
        })->map(function ($items, $supplier) {
            return [
                'supplier' => $supplier,
                'sku_count' => $items->count(),
                'avg_lead_time' => round($items->avg('lead_time_days') ?? 0),
                'total_stock' => $items->sum('stok_aktual'),
                'has_kritis' => $items->where('status_stok', 'KRITIS')->count() > 0,
            ];
        })->values();

        // 11. Aktivitas Harian Spesifik (Khusus Admin Gudang)
        $todayStr = now()->format('Y-m-d');
        $todayInboundCount = StockInTransaction::where('tanggal', $todayStr)->count();
        $todayInboundQty = (int) StockInTransaction::where('tanggal', $todayStr)->sum('jumlah');
        $todayOutboundCount = StockOutTransaction::where('tanggal', $todayStr)->count();
        $todayOutboundQty = (int) StockOutTransaction::where('tanggal', $todayStr)->sum('jumlah');

        // 12. Item Butuh Perhatian Khusus (Khusus Kepala Gudang)
        $attentionProducts = $allProducts->whereIn('status_stok', ['KRITIS', 'BERLEBIH'])->values();

        // 13. Total Pengguna Sistem
        $totalUsers = User::count();

        // 14. Ringkasan Finansial Multi-Periode (Khusus Purchasing: Harian, Mingguan, Bulanan, Tahunan)
        $purchasingFinancials = $this->getPurchasingFinancialSummary();

        // 15. Nota Pembelian & Faktur Pengadaan Terkini (Khusus Purchasing)
        $recentPurchaseNotes = StockInTransaction::with(['product', 'user'])
            ->latest('tanggal')
            ->latest('id')
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'allProducts',
            'kritisProducts',
            'normalProducts',
            'berlebihProducts',
            'totalSKU',
            'kritisCount',
            'normalCount',
            'berlebihCount',
            'healthScore',
            'totalValuasi',
            'totalStokFisik',
            'totalKapasitasMaks',
            'utilisasiGudang',
            'totalRoll',
            'totalYard',
            'totalPcs',
            'totalLusin',
            'totalBox',
            'totalMeter',
            'totalKg',
            'inboundThisMonthQty',
            'inboundThisMonthCount',
            'inboundGrowth',
            'outboundThisMonthQty',
            'outboundThisMonthCount',
            'outboundGrowth',
            'monthsLabels',
            'chartInbound',
            'chartOutbound',
            'trendDatasets',
            'categoryBreakdown',
            'fastMovingProducts',
            'warehouseRacks',
            'recentInbound',
            'recentOutbound',
            'reorderSuggestions',
            'totalReorderBudget',
            'criticalReorderCount',
            'topSuppliers',
            'todayInboundCount',
            'todayInboundQty',
            'todayOutboundCount',
            'todayOutboundQty',
            'attentionProducts',
            'totalUsers',
            'purchasingFinancials',
            'recentPurchaseNotes'
        ));
    }

    /**
     * Generator multi-periode dataset untuk analisis tren mutasi stok
     * Mendukung opsi: Harian (14 hari), Mingguan (8 mgg), Triwulan (3 bln), Semester (6 bln), Bulanan (12 bln)
     */
    private function getTrendDatasets(): array
    {
        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        // 1. Harian (14 Hari Terakhir)
        $dailyLabels = [];
        $dailyIn = [];
        $dailyOut = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $dailyLabels[] = $date->format('d') . ' ' . $monthNames[$date->month];
            $dailyIn[] = (int) StockInTransaction::where('tanggal', $dateStr)->sum('jumlah');
            $dailyOut[] = (int) StockOutTransaction::where('tanggal', $dateStr)->sum('jumlah');
        }

        // 2. Mingguan (8 Minggu Terakhir)
        $weeklyLabels = [];
        $weeklyIn = [];
        $weeklyOut = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekDate = now()->subWeeks($i);
            $startOfWeek = $weekDate->copy()->startOfWeek();
            $endOfWeek = $weekDate->copy()->endOfWeek();
            $weeklyLabels[] = $startOfWeek->format('d/m') . ' - ' . $endOfWeek->format('d/m');
            $weeklyIn[] = (int) StockInTransaction::whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])->sum('jumlah');
            $weeklyOut[] = (int) StockOutTransaction::whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])->sum('jumlah');
        }

        // 3. 3 Bulan (Triwulan)
        $triwulanLabels = [];
        $triwulanIn = [];
        $triwulanOut = [];
        for ($i = 2; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $s = $date->copy()->startOfMonth()->format('Y-m-d');
            $e = $date->copy()->endOfMonth()->format('Y-m-d');
            $triwulanLabels[] = $monthNames[$date->month] . ' ' . $date->format('y');
            $triwulanIn[] = (int) StockInTransaction::whereBetween('tanggal', [$s, $e])->sum('jumlah');
            $triwulanOut[] = (int) StockOutTransaction::whereBetween('tanggal', [$s, $e])->sum('jumlah');
        }

        // 4. 6 Bulan (Semester)
        $semesterLabels = [];
        $semesterIn = [];
        $semesterOut = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $s = $date->copy()->startOfMonth()->format('Y-m-d');
            $e = $date->copy()->endOfMonth()->format('Y-m-d');
            $semesterLabels[] = $monthNames[$date->month] . ' ' . $date->format('y');
            $semesterIn[] = (int) StockInTransaction::whereBetween('tanggal', [$s, $e])->sum('jumlah');
            $semesterOut[] = (int) StockOutTransaction::whereBetween('tanggal', [$s, $e])->sum('jumlah');
        }

        // 5. Bulanan (12 Bulan / 1 Tahun)
        $bulananLabels = [];
        $bulananIn = [];
        $bulananOut = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $s = $date->copy()->startOfMonth()->format('Y-m-d');
            $e = $date->copy()->endOfMonth()->format('Y-m-d');
            $bulananLabels[] = $monthNames[$date->month] . ' ' . $date->format('y');
            $bulananIn[] = (int) StockInTransaction::whereBetween('tanggal', [$s, $e])->sum('jumlah');
            $bulananOut[] = (int) StockOutTransaction::whereBetween('tanggal', [$s, $e])->sum('jumlah');
        }

        return [
            'harian' => [
                'key' => 'harian',
                'title' => '14 Hari Terakhir',
                'short_title' => '14 Hari',
                'labels' => $dailyLabels,
                'inbound' => $dailyIn,
                'outbound' => $dailyOut,
                'total_in' => array_sum($dailyIn),
                'total_out' => array_sum($dailyOut),
                'net' => array_sum($dailyIn) - array_sum($dailyOut),
            ],
            'mingguan' => [
                'key' => 'mingguan',
                'title' => '8 Minggu Terakhir',
                'short_title' => '8 Minggu',
                'labels' => $weeklyLabels,
                'inbound' => $weeklyIn,
                'outbound' => $weeklyOut,
                'total_in' => array_sum($weeklyIn),
                'total_out' => array_sum($weeklyOut),
                'net' => array_sum($weeklyIn) - array_sum($weeklyOut),
            ],
            'triwulan' => [
                'key' => 'triwulan',
                'title' => '3 Bulan (Triwulan)',
                'short_title' => '3 Bulan',
                'labels' => $triwulanLabels,
                'inbound' => $triwulanIn,
                'outbound' => $triwulanOut,
                'total_in' => array_sum($triwulanIn),
                'total_out' => array_sum($triwulanOut),
                'net' => array_sum($triwulanIn) - array_sum($triwulanOut),
            ],
            'semester' => [
                'key' => 'semester',
                'title' => '6 Bulan (Semester)',
                'short_title' => '6 Bulan',
                'labels' => $semesterLabels,
                'inbound' => $semesterIn,
                'outbound' => $semesterOut,
                'total_in' => array_sum($semesterIn),
                'total_out' => array_sum($semesterOut),
                'net' => array_sum($semesterIn) - array_sum($semesterOut),
            ],
            'bulanan' => [
                'key' => 'bulanan',
                'title' => 'Bulanan (12 Bulan / 1 Tahun)',
                'short_title' => '12 Bulan',
                'labels' => $bulananLabels,
                'inbound' => $bulananIn,
                'outbound' => $bulananOut,
                'total_in' => array_sum($bulananIn),
                'total_out' => array_sum($bulananOut),
                'net' => array_sum($bulananIn) - array_sum($bulananOut),
            ],
        ];
    }

    /**
     * Hitung ringkasan finansial dan perputaran barang untuk tim Purchasing
     * Mencakup periode: Harian, Mingguan, Bulanan, dan Tahunan
     */
    private function getPurchasingFinancialSummary(): array
    {
        $todayStr = now()->format('Y-m-d');
        
        $startOfWeek = now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = now()->endOfWeek()->format('Y-m-d');
        
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');
        
        $startOfYear = now()->startOfYear()->format('Y-m-d');
        $endOfYear = now()->endOfYear()->format('Y-m-d');

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return [
            'harian' => array_merge(
                $this->getFinancialMetricForRange($todayStr, $todayStr),
                [
                    'key' => 'harian',
                    'title' => 'Hari Ini',
                    'period_label' => now()->format('d') . ' ' . $monthNames[now()->month] . ' ' . now()->format('Y'),
                    'badge' => '24 Jam Terakhir',
                    'icon' => 'calendar-day',
                ]
            ),
            'mingguan' => array_merge(
                $this->getFinancialMetricForRange($startOfWeek, $endOfWeek),
                [
                    'key' => 'mingguan',
                    'title' => 'Minggu Ini',
                    'period_label' => now()->startOfWeek()->format('d M') . ' - ' . now()->endOfWeek()->format('d M Y'),
                    'badge' => '7 Hari Berjalan',
                    'icon' => 'calendar-week',
                ]
            ),
            'bulanan' => array_merge(
                $this->getFinancialMetricForRange($startOfMonth, $endOfMonth),
                [
                    'key' => 'bulanan',
                    'title' => 'Bulan Ini',
                    'period_label' => $monthNames[now()->month] . ' ' . now()->format('Y'),
                    'badge' => 'Bulan Berjalan',
                    'icon' => 'calendar-alt',
                ]
            ),
            'tahunan' => array_merge(
                $this->getFinancialMetricForRange($startOfYear, $endOfYear),
                [
                    'key' => 'tahunan',
                    'title' => 'Tahun Ini',
                    'period_label' => 'Tahun Anggaran ' . now()->format('Y'),
                    'badge' => 'Tahun Berjalan',
                    'icon' => 'chart-line',
                ]
            ),
        ];
    }

    /**
     * Hitung nominal belanja masuk (Inbound) dan pendapatan distribusi keluar (Outbound)
     * berdasarkan range tanggal
     */
    private function getFinancialMetricForRange(string $startDate, string $endDate): array
    {
        $inbound = StockInTransaction::join('products', 'stock_in_transactions.product_id', '=', 'products.id')
            ->whereBetween('stock_in_transactions.tanggal', [$startDate, $endDate])
            ->selectRaw('
                COALESCE(SUM(stock_in_transactions.jumlah * COALESCE(products.harga_beli_per_satuan, 0)), 0) as total_nominal,
                COALESCE(SUM(stock_in_transactions.jumlah), 0) as total_qty,
                COUNT(stock_in_transactions.id) as total_count
            ')
            ->first();

        $outbound = StockOutTransaction::join('products', 'stock_out_transactions.product_id', '=', 'products.id')
            ->whereBetween('stock_out_transactions.tanggal', [$startDate, $endDate])
            ->selectRaw('
                COALESCE(SUM(stock_out_transactions.jumlah * COALESCE(products.harga_beli_per_satuan, 0)), 0) as total_nominal,
                COALESCE(SUM(stock_out_transactions.jumlah), 0) as total_qty,
                COUNT(stock_out_transactions.id) as total_count
            ')
            ->first();

        $inNominal = (float) ($inbound->total_nominal ?? 0);
        $inQty = (int) ($inbound->total_qty ?? 0);
        $inCount = (int) ($inbound->total_count ?? 0);

        $outNominal = (float) ($outbound->total_nominal ?? 0);
        $outQty = (int) ($outbound->total_qty ?? 0);
        $outCount = (int) ($outbound->total_count ?? 0);

        $avgInbound = $inCount > 0 ? round($inNominal / $inCount) : 0;
        $avgOutbound = $outCount > 0 ? round($outNominal / $outCount) : 0;

        return [
            'inbound_nominal' => $inNominal,
            'inbound_qty' => $inQty,
            'inbound_count' => $inCount,
            'avg_inbound' => $avgInbound,
            'outbound_nominal' => $outNominal,
            'outbound_qty' => $outQty,
            'outbound_count' => $outCount,
            'avg_outbound' => $avgOutbound,
            'total_perputaran' => $inNominal + $outNominal,
            'net_pengadaan' => $inNominal,
            'net_pendapatan' => $outNominal,
            'selisih' => $outNominal - $inNominal,
        ];
    }

    /**
     * API JSON Feed untuk live notifikasi topbar
     */
    public function notificationsFeed(Request $request)
    {
        $data = \App\Services\NotificationService::getNotifications();
        return response()->json($data);
    }
}

