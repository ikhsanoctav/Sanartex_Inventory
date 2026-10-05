<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\RblEvaluatorService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('supplier_utama', 'like', "%{$search}%")
                  ->orWhere('lokasi_rak', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status_stok') && $request->status_stok !== 'all') {
            $query->where('status_stok', $request->status_stok);
        }

        if ($request->filled('satuan') && $request->satuan !== 'all') {
            $query->where('satuan', $request->satuan);
        }

        // Sorting
        $sort = $request->input('sort', 'nama_asc');
        switch ($sort) {
            case 'nama_desc':
                $query->orderBy('nama', 'desc');
                break;
            case 'stok_asc':
                $query->orderBy('stok_aktual', 'asc');
                break;
            case 'stok_desc':
                $query->orderBy('stok_aktual', 'desc');
                break;
            case 'terbaru':
                $query->latest('id');
                break;
            case 'terlama':
                $query->oldest('id');
                break;
            case 'nama_asc':
            default:
                $query->orderBy('nama', 'asc');
                break;
        }

        $categories = Product::select('kategori')->distinct()->pluck('kategori');
        $satuans = Product::select('satuan')->distinct()->pluck('satuan');
        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'categories', 'satuans', 'sort'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'nullable|string|max:50|unique:products,kode_produk',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'stok_aktual' => 'required|integer|min:0',
            'batas_minimum' => 'required|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'batas_maksimum' => 'required|integer|gt:batas_minimum',
            'lead_time_days' => 'nullable|integer|min:1',
            'lokasi_rak' => 'nullable|string|max:100',
            'supplier_utama' => 'nullable|string|max:150',
            'harga_beli_per_satuan' => 'nullable|numeric|min:0',
            'spesifikasi' => 'nullable|string',
        ]);

        if (empty($validated['kode_produk'])) {
            $validated['kode_produk'] = 'TEX-' . strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $validated['kategori']), 0, 3)) . '-' . rand(100, 999);
        }

        if (empty($validated['reorder_point'])) {
            $min = (int) $validated['batas_minimum'];
            $max = (int) $validated['batas_maksimum'];
            $validated['reorder_point'] = $min + (int) ceil(($max - $min) * 0.3);
        }

        $product = Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', "Produk textile [{$product->kode_produk}] {$product->nama} berhasil ditambahkan!");
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'kode_produk' => 'required|string|max:50|unique:products,kode_produk,' . $product->id,
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'stok_aktual' => 'required|integer|min:0',
            'batas_minimum' => 'required|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'batas_maksimum' => 'required|integer|gt:batas_minimum',
            'lead_time_days' => 'nullable|integer|min:1',
            'lokasi_rak' => 'nullable|string|max:100',
            'supplier_utama' => 'nullable|string|max:150',
            'harga_beli_per_satuan' => 'nullable|numeric|min:0',
            'spesifikasi' => 'nullable|string',
        ]);

        if (empty($validated['reorder_point'])) {
            $min = (int) $validated['batas_minimum'];
            $max = (int) $validated['batas_maksimum'];
            $validated['reorder_point'] = $min + (int) ceil(($max - $min) * 0.3);
        }

        $product->update($validated);
        $product->evaluateStockStatus();

        return redirect()->route('products.index')
            ->with('success', "Produk textile [{$product->kode_produk}] berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->nama;
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "Produk textile '{$name}' telah berhasil dihapus.");
    }
}
