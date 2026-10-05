@extends('layouts.app')

@section('title', 'Master Produk & Parameter RBL')

@section('content')
<div class="space-y-6" x-data="{ openCreateModal: false, editProduct: null }">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-navy-900 tracking-tight">Master Data Pakaian Jadi (Apparel)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola katalog produk jadi (hoodie, kaos, kemeja, jaket, dll), buffer level RBL, dan alokasi rak.</p>
        </div>
        
        <button 
            @click="openCreateModal = true"
            class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Produk</span>
        </button>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <form action="{{ route('products.index') }}" method="GET" data-ajax-filter="true" data-ajax-target="#ajax-table-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            <!-- Search -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-[11px] font-semibold text-slate-700">Cari Produk</label>
                    <span class="ajax-live-badge"><span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Realtime</span></span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        id="product_search_input"
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Ketik nama produk (hoodie, kaos...), SKU, rak, vendor..." 
                        class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <button 
                        type="button" 
                        @click="triggerBarcodeScan({ title: 'Scan Barcode Produk untuk Cari', targetInput: '#product_search_input' })"
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-orange-500 transition-colors" title="Scan Barcode">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </button>
                </div>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Kategori</label>
                <select name="kategori" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status RBL -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Status RBL</label>
                <select name="status_stok" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <option value="all">Semua Status</option>
                    <option value="KRITIS" {{ request('status_stok') == 'KRITIS' ? 'selected' : '' }}>🔴 Kritis (Merah)</option>
                    <option value="NORMAL" {{ request('status_stok') == 'NORMAL' ? 'selected' : '' }}>🟢 Normal (Hijau)</option>
                    <option value="BERLEBIH" {{ request('status_stok') == 'BERLEBIH' ? 'selected' : '' }}>🔵 Berlebih (Biru)</option>
                </select>
            </div>

            <!-- Satuan / Sorting -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Urutkan</label>
                <select name="sort" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <option value="nama_asc" {{ ($sort ?? '') == 'nama_asc' ? 'selected' : '' }}>Nama Produk (A-Z)</option>
                    <option value="nama_desc" {{ ($sort ?? '') == 'nama_desc' ? 'selected' : '' }}>Nama Produk (Z-A)</option>
                    <option value="stok_asc" {{ ($sort ?? '') == 'stok_asc' ? 'selected' : '' }}>Stok Terendah</option>
                    <option value="stok_desc" {{ ($sort ?? '') == 'stok_desc' ? 'selected' : '' }}>Stok Tertinggi</option>
                    <option value="terbaru" {{ ($sort ?? '') == 'terbaru' ? 'selected' : '' }}>Input Terbaru</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>
                <a href="{{ route('products.index') }}" data-ajax-reset="true" class="px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs rounded-lg font-medium border border-slate-200 transition-colors flex items-center justify-center" title="Reset Filter">
                    Reset
                </a>
            </div>
        </form>

        <!-- Quick Satuan Filter -->
        <div data-ajax-sync="satuan-pills" class="mt-3 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-semibold text-slate-500">Filter Satuan:</span>
                <a href="{{ route('products.index', array_merge(request()->except('satuan'), ['satuan' => 'all'])) }}" 
                   data-ajax-link="true"
                   class="px-2.5 py-0.5 rounded-full text-[11px] font-medium border transition-colors {{ !request('satuan') || request('satuan') == 'all' ? 'bg-navy-900 text-white border-navy-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                   Semua
                </a>
                @foreach($satuans as $sat)
                    <a href="{{ route('products.index', array_merge(request()->except('satuan'), ['satuan' => $sat])) }}" 
                       data-ajax-link="true"
                       class="px-2.5 py-0.5 rounded-full text-[11px] font-medium border transition-colors {{ request('satuan') == $sat ? 'bg-orange-500 text-white border-orange-500 font-semibold' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                       {{ $sat }}
                    </a>
                @endforeach
            </div>

            @if(request()->hasAny(['search', 'kategori', 'status_stok', 'satuan', 'sort']))
                <a href="{{ route('products.index') }}" data-ajax-reset="true" class="text-[11px] text-rose-600 hover:text-rose-700 font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Hapus Filter Aktif</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Product Table Container -->
    <div id="ajax-table-container" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4">SKU / Nama Produk</th>
                        <th class="py-3 px-4">Kategori & Satuan</th>
                        <th class="py-3 px-4">Stok Saat Ini</th>
                        <th class="py-3 px-4 min-w-[180px]">Buffer Status RBL</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Lokasi Rak</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($products as $product)
                        @php
                            $hb = $product->health_bar;
                        @endphp
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <!-- Name & Code -->
                            <td class="py-3.5 px-4">
                                <span class="text-[11px] font-mono text-slate-500 font-semibold">[{{ $product->kode_produk }}]</span>
                                <div class="font-semibold text-slate-900 text-sm mt-0.5">{{ $product->nama }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $product->spesifikasi ?? 'Spesifikasi standar' }}</div>
                            </td>

                            <!-- Category & Unit -->
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium text-[11px]">
                                    {{ $product->kategori }}
                                </span>
                                <div class="text-[11px] text-slate-400 mt-1">Satuan: <span class="font-medium text-slate-600">{{ $product->satuan }}</span></div>
                            </td>

                            <!-- Stock -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-sm text-slate-900">
                                    {{ number_format($product->stok_aktual, 0, ',', '.') }}
                                    <span class="text-xs font-normal text-slate-500">{{ $product->satuan }}</span>
                                </div>
                            </td>

                            <!-- Buffer Progress Bar (3 Segments) -->
                            <td class="py-3.5 px-4">
                                <div class="space-y-1.5">
                                    <div class="w-full h-2.5 bg-slate-100 rounded-full border border-slate-200 p-0 relative overflow-hidden flex">
                                        <div class="h-full bg-rose-400" style="width: 25%;" title="Zona Kritis"></div>
                                        <div class="h-full bg-emerald-400" style="width: 55%;" title="Zona Normal"></div>
                                        <div class="h-full bg-blue-400" style="width: 20%;" title="Zona Berlebih"></div>

                                        <!-- Indicator needle -->
                                        <div class="absolute top-0 bottom-0 w-1.5 bg-slate-900 rounded-full shadow"
                                             style="left: {{ $hb['current_percent'] }}%;"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                                        <span>Min: {{ $product->batas_minimum }}</span>
                                        <span>Max: {{ $product->batas_maksimum }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $product->rbl_meta['badge_class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->rbl_meta['dot_class'] }}"></span>
                                    {{ $product->status_stok }}
                                </span>
                            </td>

                            <!-- Rack Location -->
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ $product->lokasi_rak ?? 'Gudang Utama' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button 
                                        @click="editProduct = {{ json_encode($product) }}" 
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors"
                                        title="Edit Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Produk">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada data produk yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="p-3.5 border-t border-slate-200 bg-slate-50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL TAMBAH PRODUK -->
    <div x-show="openCreateModal" x-cloak @click.self="openCreateModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs" x-transition>
        <div class="w-full max-w-xl bg-white border border-slate-200 rounded-xl p-6 shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Tambah Produk Pakaian Jadi Baru</h3>
                <button @click="openCreateModal = false" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <form action="{{ route('products.store') }}" method="POST" class="mt-4 space-y-3.5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-semibold text-slate-700">Kode SKU (Opsional)</label>
                            <button 
                                type="button" 
                                @click="triggerBarcodeScan({ title: 'Scan Barcode / QR Label SKU', targetInput: '#create_product_sku' })"
                                class="inline-flex items-center gap-1 text-[10px] font-semibold text-orange-600 hover:text-orange-700" title="Scan Barcode SKU">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Scan Barcode</span>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" id="create_product_sku" name="kode_produk" placeholder="Contoh: AP-HOD-001" class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <button 
                                type="button" 
                                @click="triggerBarcodeScan({ title: 'Scan Barcode / QR Label SKU', targetInput: '#create_product_sku' })"
                                class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-orange-500 transition-colors" title="Scan Barcode">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Produk Apparel <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" required placeholder="Contoh: Hoodie Oversize Cotton Fleece Hitam" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="kategori" required placeholder="Contoh: Hoodie & Sweater / Kaos & T-Shirt" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Satuan <span class="text-rose-500">*</span></label>
                        <select name="satuan" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                            <option value="Pcs" selected>Pcs</option>
                            <option value="Lusin">Lusin</option>
                            <option value="Box">Box</option>
                            <option value="Pack">Pack</option>
                            <option value="Set">Set</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Stok Awal <span class="text-rose-500">*</span></label>
                        <input type="number" name="stok_aktual" value="0" min="0" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Batas Minimum (Kritis) <span class="text-rose-500">*</span></label>
                        <input type="number" name="batas_minimum" value="20" min="0" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Batas Maksimum <span class="text-rose-500">*</span></label>
                        <input type="number" name="batas_maksimum" value="100" min="1" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Lead Time (Hari)</label>
                        <input type="number" name="lead_time_days" value="7" min="1" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Lokasi Rak</label>
                        <input type="text" name="lokasi_rak" placeholder="Contoh: Rak Apparel A-01" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Vendor / Konveksi Utama</label>
                        <input type="text" name="supplier_utama" placeholder="Contoh: PT Konveksi Garment Mandiri" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Harga Satuan (Rp)</label>
                        <input type="number" name="harga_beli_per_satuan" value="0" min="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Spesifikasi / Detail Produk</label>
                    <textarea name="spesifikasi" rows="2" placeholder="Bahan Cotton Fleece 330 gsm, sablon discharge, size L/XL..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                    <button type="button" @click="openCreateModal = false" class="px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition-all shadow-xs">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT PRODUK -->
    <template x-if="editProduct">
        <div @click.self="editProduct = null" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/50 backdrop-blur-xs">
            <div class="w-full max-w-xl bg-white border border-slate-200 rounded-xl p-6 shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <h3 class="text-sm font-bold text-navy-900">Edit Produk Pakaian Jadi</h3>
                    <button @click="editProduct = null" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
                </div>

                <form :action="'/products/' + editProduct.id" method="POST" class="mt-4 space-y-3.5">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-[11px] font-semibold text-slate-700">Kode SKU</label>
                                <button 
                                    type="button" 
                                    @click="triggerBarcodeScan({ title: 'Scan Barcode / QR Label SKU', callback: (code) => { if(editProduct) editProduct.kode_produk = code; } })"
                                    class="inline-flex items-center gap-1 text-[10px] font-semibold text-orange-600 hover:text-orange-700" title="Scan Barcode SKU">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    <span>Scan Barcode</span>
                                </button>
                            </div>
                            <div class="relative">
                                <input type="text" name="kode_produk" x-model="editProduct.kode_produk" required class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                <button 
                                    type="button" 
                                    @click="triggerBarcodeScan({ title: 'Scan Barcode / QR Label SKU', callback: (code) => { if(editProduct) editProduct.kode_produk = code; } })"
                                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-orange-500 transition-colors" title="Scan Barcode">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Produk Apparel</label>
                            <input type="text" name="nama" x-model="editProduct.nama" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Kategori</label>
                            <input type="text" name="kategori" x-model="editProduct.kategori" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Satuan</label>
                            <input type="text" name="satuan" x-model="editProduct.satuan" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Stok Saat Ini</label>
                            <input type="number" name="stok_aktual" x-model.number="editProduct.stok_aktual" min="0" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Batas Minimum (Kritis)</label>
                            <input type="number" name="batas_minimum" x-model.number="editProduct.batas_minimum" min="0" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Batas Maksimum</label>
                            <input type="number" name="batas_maksimum" x-model.number="editProduct.batas_maksimum" min="1" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Lead Time (Hari)</label>
                            <input type="number" name="lead_time_days" x-model.number="editProduct.lead_time_days" min="1" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Lokasi Rak</label>
                            <input type="text" name="lokasi_rak" x-model="editProduct.lokasi_rak" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                        <button type="button" @click="editProduct = null" class="px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition-all shadow-xs">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
