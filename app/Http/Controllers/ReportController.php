<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockInTransaction;
use App\Models\StockOutTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role === 'admin_gudang' && !Auth::user()->isSuperadmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses dibatasi. Laporan mutasi & valuasi persediaan hanya dapat diakses oleh Kepala Gudang, Purchasing, dan Superadmin.');
        }

        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));
        $kategori  = $request->input('kategori', 'all');
        $statusStok = $request->input('status_stok', 'all');
        $search    = $request->input('search', '');

        $productQuery = Product::query();

        if (!empty($search)) {
            $productQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%");
            });
        }

        if ($kategori !== 'all' && !empty($kategori)) {
            $productQuery->where('kategori', $kategori);
        }

        if ($statusStok !== 'all' && !empty($statusStok)) {
            $productQuery->where('status_stok', $statusStok);
        }

        $allMatchingProducts = (clone $productQuery)->get();
        $totalValuasi = $allMatchingProducts->sum(function ($p) {
            return $p->stok_aktual * ($p->harga_beli_per_satuan ?? 0);
        });

        $matchingProductIds = $allMatchingProducts->pluck('id');
        $totalMasuk = (int) StockInTransaction::whereIn('product_id', $matchingProductIds)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');
        $totalKeluar = (int) StockOutTransaction::whereIn('product_id', $matchingProductIds)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');

        $paginatedProducts = $productQuery->orderBy('nama', 'asc')->paginate(12)->withQueryString();

        $paginatedProducts->getCollection()->transform(function ($product) use ($startDate, $endDate) {
            $inQty = (int) StockInTransaction::where('product_id', $product->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->sum('jumlah');

            $outQty = (int) StockOutTransaction::where('product_id', $product->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->sum('jumlah');

            $stokAwal = $product->stok_aktual - $inQty + $outQty;

            return [
                'product' => $product,
                'stok_awal' => max(0, $stokAwal),
                'total_masuk' => $inQty,
                'total_keluar' => $outQty,
                'stok_akhir' => $product->stok_aktual,
                'status_stok' => $product->status_stok,
                'valuasi' => $product->stok_aktual * ($product->harga_beli_per_satuan ?? 0),
            ];
        });

        $categories = Product::select('kategori')->distinct()->pluck('kategori');

        return view('laporan.index', [
            'reportData' => $paginatedProducts,
            'categories' => $categories,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'kategori' => $kategori,
            'statusStok' => $statusStok,
            'search' => $search,
            'totalValuasi' => $totalValuasi,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
        ]);
    }

    public function panduan()
    {
        return view('panduan.index');
    }
}
