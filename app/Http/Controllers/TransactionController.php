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
                  ->orWhere('no_nota', 'like', "%{$search}%")
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

        // Filter Status Pembayaran
        if ($request->filled('status_pembayaran') && $request->status_pembayaran !== 'all') {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        $suppliers = StockInTransaction::whereNotNull('supplier')->where('supplier', '!=', '')->distinct()->pluck('supplier');

        $transactions = $query->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('transaksi.masuk', compact('products', 'transactions', 'suppliers'));
    }

    /**
     * Proses Simpan Stok Masuk & Nota Pembelian
     */
    public function storeMasuk(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'no_nota' => 'nullable|string|max:100',
            'harga_beli_satuan' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'ppn_persen' => 'nullable|numeric|min:0|max:100',
            'status_pembayaran' => 'nullable|string|in:LUNAS,TEMPO,DP',
            'metode_pembayaran' => 'nullable|string|max:100',
            'jatuh_tempo' => 'nullable|date',
            'no_surat_jalan' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:150',
            'no_batch_lot' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'bukti_nota' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_nota')) {
            $file = $request->file('bukti_nota');
            $filename = 'nota_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/nota'), $filename);
            $buktiPath = 'uploads/nota/' . $filename;
        }

        $transaction = DB::transaction(function () use ($validated, $buktiPath) {
            $product = Product::where('id', $validated['product_id'])->lockForUpdate()->first();

            $hargaBeli = !empty($validated['harga_beli_satuan']) && $validated['harga_beli_satuan'] > 0
                ? (float) $validated['harga_beli_satuan']
                : (float) ($product->harga_beli_per_satuan ?? 0);

            $diskon = (float) ($validated['diskon'] ?? 0);
            $ppnPersen = (float) ($validated['ppn_persen'] ?? 0);
            $subtotal = ((int) $validated['jumlah'] * $hargaBeli) - $diskon;
            $ppn = $subtotal * ($ppnPersen / 100);
            $totalHarga = max(0, $subtotal + $ppn);

            return StockInTransaction::create([
                'no_nota' => !empty($validated['no_nota']) ? $validated['no_nota'] : ('NOTA-' . now()->format('Ym') . '-' . strtoupper(substr(uniqid(), -5))),
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'tanggal' => $validated['tanggal'],
                'jumlah' => $validated['jumlah'],
                'harga_beli_satuan' => $hargaBeli,
                'diskon' => $diskon,
                'ppn_persen' => $ppnPersen,
                'total_harga' => $totalHarga,
                'status_pembayaran' => $validated['status_pembayaran'] ?? 'LUNAS',
                'metode_pembayaran' => $validated['metode_pembayaran'] ?? 'Transfer Bank',
                'jatuh_tempo' => ($validated['status_pembayaran'] ?? 'LUNAS') === 'TEMPO' ? ($validated['jatuh_tempo'] ?? now()->addDays(30)->format('Y-m-d')) : null,
                'no_surat_jalan' => $validated['no_surat_jalan'] ?? null,
                'supplier' => $validated['supplier'] ?? $product->supplier_utama,
                'no_batch_lot' => $validated['no_batch_lot'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'bukti_nota' => $buktiPath,
            ]);
        });

        $redirectRoute = $request->input('redirect_to') === 'nota' ? 'purchasing.nota.index' : 'transaksi.masuk';

        return redirect()->route($redirectRoute)
            ->with('success', 'Nota Pembelian & Penerimaan Barang [' . $transaction->no_nota . '] berhasil dicatat ke sistem inventori.');
    }

    /**
     * Modul Khusus Purchasing: Daftar Nota Pembelian & Faktur Pengadaan
     */
    public function indexNota(Request $request)
    {
        if (!Auth::user()->isPurchasing()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Modul Nota Pembelian & Faktur Pengadaan hanya dapat diakses oleh Purchasing, Direksi, dan Superadmin.');
        }

        $products = Product::orderBy('nama', 'asc')->get();
        $query = StockInTransaction::with(['product', 'user']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_nota', 'like', "%{$search}%")
                  ->orWhere('no_surat_jalan', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('nama', 'like', "%{$search}%")
                         ->orWhere('kode_produk', 'like', "%{$search}%");
                  });
            });
        }

        // Date Filter
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Status Pembayaran Filter
        if ($request->filled('status_pembayaran') && $request->status_pembayaran !== 'all') {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        // Supplier Filter
        if ($request->filled('supplier') && $request->supplier !== 'all') {
            $query->where('supplier', $request->supplier);
        }

        // Clone query for KPI calculation
        $allMatching = (clone $query)->get();
        $totalBelanja = $allMatching->sum('total_harga');
        $totalLunas = $allMatching->where('status_pembayaran', 'LUNAS')->sum('total_harga');
        $totalTempo = $allMatching->where('status_pembayaran', 'TEMPO')->sum('total_harga');
        $totalNotaCount = $allMatching->count();
        $countTempo = $allMatching->where('status_pembayaran', 'TEMPO')->count();
        $countLunas = $allMatching->where('status_pembayaran', 'LUNAS')->count();

        $suppliers = StockInTransaction::whereNotNull('supplier')->where('supplier', '!=', '')->distinct()->pluck('supplier');

        $transactions = $query->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('purchasing.nota.index', compact(
            'products',
            'transactions',
            'suppliers',
            'totalBelanja',
            'totalLunas',
            'totalTempo',
            'totalNotaCount',
            'countTempo',
            'countLunas'
        ));
    }

    /**
     * Cetak Formal Bukti Nota Pembelian & Faktur Penerimaan (Print View)
     */
    public function cetakNota(int $id)
    {
        if (!Auth::user()->isPurchasing() && !Auth::user()->isKepalaGudang()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Dokumen faktur pembelian hanya dapat dicetak oleh Purchasing, Kepala Gudang, Direksi, atau Superadmin.');
        }

        $transaction = StockInTransaction::with(['product', 'user'])->findOrFail($id);

        return view('purchasing.nota.cetak', compact('transaction'));
    }

    /**
     * Update Status Pembayaran Nota (misal: Pelunasan Hutang Tempo)
     */
    public function updateStatusBayar(Request $request, int $id)
    {
        if (!Auth::user()->isPurchasing()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Perubahan status pembayaran hanya dapat dilakukan oleh Purchasing dan Superadmin.');
        }

        $validated = $request->validate([
            'status_pembayaran' => 'required|string|in:LUNAS,TEMPO,DP',
            'metode_pembayaran' => 'nullable|string|max:100',
            'keterangan_pelunasan' => 'nullable|string',
        ]);

        $transaction = StockInTransaction::findOrFail($id);
        $transaction->status_pembayaran = $validated['status_pembayaran'];
        if ($request->filled('metode_pembayaran')) {
            $transaction->metode_pembayaran = $validated['metode_pembayaran'];
        }
        if ($request->filled('keterangan_pelunasan')) {
            $transaction->keterangan = ($transaction->keterangan ? $transaction->keterangan . ' | ' : '') . 'Pelunasan: ' . $validated['keterangan_pelunasan'];
        }
        $transaction->save();

        return back()->with('success', 'Status pembayaran Nota [' . $transaction->no_nota . '] berhasil diperbarui menjadi ' . $transaction->status_pembayaran . '.');
    }

    /**
     * Halaman Stok Keluar (Outbound)
     */
    public function indexKeluar(Request $request)
    {
        if (Auth::user()->role === 'purchasing' && !Auth::user()->isSuperadmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Modul Pengeluaran Barang (Outbound) hanya dikelola oleh tim Gudang.');
        }

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
