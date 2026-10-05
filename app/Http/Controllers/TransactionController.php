<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockInTransaction;
use App\Models\StockOutTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Halaman Stok Masuk (Inbound)
     */
    public function indexMasuk(Request $request)
    {
        $products = Product::orderBy('nama', 'asc')->get();
        $query = StockInTransaction::with(['product', 'user']);

        // Filter Search Keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_surat_jalan', 'like', "%{$search}%")
                  ->orWhere('no_batch_lot', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('nama', 'like', "%{$search}%")
                         ->orWhere('kode_produk', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter Produk
        if ($request->filled('product_id') && $request->product_id !== 'all') {
            $query->where('product_id', $request->product_id);
        }

        // Filter Supplier
        if ($request->filled('supplier') && $request->supplier !== 'all') {
            $query->where('supplier', $request->supplier);
        }

        $suppliers = StockInTransaction::whereNotNull('supplier')->where('supplier', '!=', '')->distinct()->pluck('supplier');

        $transactions = $query->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('transaksi.masuk', compact('products', 'transactions', 'suppliers'));
    }

    /**
     * Proses Simpan Stok Masuk
     */
    public function storeMasuk(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'no_surat_jalan' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:150',
            'no_batch_lot' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::where('id', $validated['product_id'])->lockForUpdate()->first();

            StockInTransaction::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'tanggal' => $validated['tanggal'],
                'jumlah' => $validated['jumlah'],
                'no_surat_jalan' => $validated['no_surat_jalan'],
                'supplier' => $validated['supplier'] ?? $product->supplier_utama,
                'no_batch_lot' => $validated['no_batch_lot'],
                'keterangan' => $validated['keterangan'],
            ]);
        });

        return redirect()->route('transaksi.masuk')
            ->with('success', 'Transaksi Stok Masuk berhasil dicatat dan stok aktual telah ditambahkan.');
    }

    /**
     * Halaman Stok Keluar (Outbound)
     */
    public function indexKeluar(Request $request)
    {
        $products = Product::orderBy('nama', 'asc')->get();
        $query = StockOutTransaction::with(['product', 'user']);

        // Filter Search Keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_spk_tujuan', 'like', "%{$search}%")
                  ->orWhere('penerima_divisi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('nama', 'like', "%{$search}%")
                         ->orWhere('kode_produk', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter Produk
        if ($request->filled('product_id') && $request->product_id !== 'all') {
            $query->where('product_id', $request->product_id);
        }

        // Filter Divisi Penerima
        if ($request->filled('penerima_divisi') && $request->penerima_divisi !== 'all') {
            $query->where('penerima_divisi', $request->penerima_divisi);
        }

        $divisions = StockOutTransaction::whereNotNull('penerima_divisi')->where('penerima_divisi', '!=', '')->distinct()->pluck('penerima_divisi');

        $transactions = $query->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('transaksi.keluar', compact('products', 'transactions', 'divisions'));
    }

    /**
     * Proses Simpan Stok Keluar
     */
    public function storeKeluar(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'no_spk_tujuan' => 'nullable|string|max:100',
            'penerima_divisi' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $product = Product::where('id', $validated['product_id'])->lockForUpdate()->first();

                if ($product->stok_aktual < (int) $validated['jumlah']) {
                    throw new \Exception("Stok tidak mencukupi! Stok saat ini: {$product->stok_aktual} {$product->satuan}, sedangkan permintaan: {$validated['jumlah']} {$product->satuan}.");
                }

                StockOutTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'tanggal' => $validated['tanggal'],
                    'jumlah' => $validated['jumlah'],
                    'no_spk_tujuan' => $validated['no_spk_tujuan'],
                    'penerima_divisi' => $validated['penerima_divisi'],
                    'keterangan' => $validated['keterangan'],
                ]);
            });

            return redirect()->route('transaksi.keluar')
                ->with('success', 'Transaksi Stok Keluar berhasil dicatat dan stok aktual telah dikurangi.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
