@extends('layouts.app')

@section('title', 'Stok Masuk (Inbound)')

@section('content')
<div class="space-y-6" x-data="{ 
    openModal: false, 
    selectedProduct: '', 
    selectedProductName: '',
    selectedProductSku: '',
    inQty: 1, 
    currentStock: 0, 
    unit: 'Pcs', 
    supplier: '', 
    min: 0, 
    max: 0,
    unitPrice: 0,
    discount: 0,
    ppnPercent: 0,
    paymentStatus: 'LUNAS',
    paymentMethod: 'Transfer Bank BCA',
    dueDate: '{{ now()->addDays(30)->format('Y-m-d') }}',
    searchQuery: '',
    dropdownOpen: false,
    productsList: {{ Js::from($products) }},
    get filteredProducts() {
        if (!this.searchQuery.trim()) return this.productsList;
        const q = this.searchQuery.toLowerCase();
        return this.productsList.filter(p => 
            (p.nama && p.nama.toLowerCase().includes(q)) || 
            (p.kode_produk && p.kode_produk.toLowerCase().includes(q)) ||
            (p.kategori && p.kategori.toLowerCase().includes(q)) ||
            (p.supplier_utama && p.supplier_utama.toLowerCase().includes(q))
        );
    },
    selectProduct(p) {
        this.selectedProduct = p.id;
        this.selectedProductName = p.nama;
        this.selectedProductSku = p.kode_produk;
        this.currentStock = parseInt(p.stok_aktual || 0);
        this.unit = p.satuan || 'Pcs';
        this.supplier = p.supplier_utama || '';
        this.min = parseInt(p.batas_minimum || 0);
        this.max = parseInt(p.batas_maksimum || 0);
        this.unitPrice = parseFloat(p.harga_beli_per_satuan || 0);
        this.dropdownOpen = false;
        this.searchQuery = '';
    },
    get subtotal() {
        return Math.max(0, (parseInt(this.inQty) || 0) * (parseFloat(this.unitPrice) || 0) - (parseFloat(this.discount) || 0));
    },
    get ppnNominal() {
        return this.subtotal * ((parseFloat(this.ppnPercent) || 0) / 100);
    },
    get grandTotal() {
        return this.subtotal + this.ppnNominal;
    },
    formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    },
    get projectedTotal() {
        return (parseInt(this.currentStock) || 0) + (parseInt(this.inQty) || 0);
    },
    get projectedStatus() {
        if (!this.selectedProduct) return null;
        const total = this.projectedTotal;
        const min = parseInt(this.min) || 0;
        const max = parseInt(this.max) || 0;

        if (total <= min) {
            return {
                status: 'KRITIS',
                label: '🔴 Zona Kritis (Stok <= ' + min + ' ' + this.unit + ')',
                colorClass: 'text-rose-600'
            };
        } else if (max > 0 && total > max) {
            return {
                status: 'BERLEBIH',
                label: '🔵 Zona Berlebih (Overstock > ' + max + ' ' + this.unit + ')',
                colorClass: 'text-blue-600'
            };
        } else {
            return {
                status: 'NORMAL',
                label: '🟢 Zona Normal (Optimal)',
                colorClass: 'text-emerald-600'
            };
        }
    },
    scanProductBarcode() {
        window.triggerBarcodeScan({
            title: 'Scan Barcode / QR Label Produk Apparel (SKU)',
            callback: (code) => {
                const found = this.productsList.find(p => 
                    (p.kode_produk && p.kode_produk.toLowerCase() === code.toLowerCase()) ||
                    (p.nama && p.nama.toLowerCase().includes(code.toLowerCase()))
                );
                if (found) {
                    this.selectProduct(found);
                } else {
                    this.searchQuery = code;
                    this.dropdownOpen = true;
                }
            }
        });
    }
}">

    <!-- Page Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-navy-900 tracking-tight">Penerimaan Stok Masuk (Inbound)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Catat penerimaan produk jadi dari vendor konveksi, surat jalan, nomor batch produksi, dan update buffer stok.</p>
        </div>

        <button 
            @click="openModal = true"
            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            <span>Catat Stok Masuk</span>
        </button>
    </div>

    <!-- Filter Card for Inbound History -->
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <form action="{{ route('transaksi.masuk') }}" method="GET" data-ajax-filter="true" data-ajax-target="#ajax-table-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            <!-- Search Keyword -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-[11px] font-semibold text-slate-700">Cari Transaksi</label>
                    <span class="ajax-live-badge"><span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Realtime</span></span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        id="masuk_filter_search"
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Ketik Surat Jalan, Batch, SKU, Vendor, Produk..." 
                        class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <button 
                        type="button" 
                        @click="triggerBarcodeScan({ title: 'Scan Barcode untuk Cari Riwayat Masuk', targetInput: '#masuk_filter_search' })"
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-orange-500 transition-colors" title="Scan Barcode">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </button>
                </div>
            </div>

            <!-- Tanggal Mulai -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Dari Tanggal</label>
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ request('start_date') }}" 
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Tanggal Akhir -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Sampai Tanggal</label>
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ request('end_date') }}" 
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Supplier -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Supplier / Vendor</label>
                <select name="supplier" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <option value="all">Semua Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup }}" {{ request('supplier') == $sup ? 'selected' : '' }}>{{ $sup }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>
                <a href="{{ route('transaksi.masuk') }}" data-ajax-reset="true" class="px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-medium text-xs rounded-lg border border-slate-200 transition-colors flex items-center justify-center" title="Reset Filter">
                    Reset
                </a>
            </div>
        </form>

        <!-- Secondary Quick Filters (Produk Dropdown & Active tag) -->
        <div data-ajax-sync="masuk-quick-filters" class="mt-3 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-semibold text-slate-500">Filter Produk Khusus:</span>
                <select 
                    onchange="const form = document.querySelector('form[data-ajax-filter]'); if(form){ let inP = form.querySelector('input[name=product_id]'); if(!inP){ inP = document.createElement('input'); inP.type = 'hidden'; inP.name = 'product_id'; form.appendChild(inP); } inP.value = this.value; form.dispatchEvent(new Event('submit')); }"
                    class="px-2.5 py-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] text-slate-700 focus:outline-none focus:border-orange-500">
                    <option value="all">-- Semua Produk / SKU --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>[{{ $p->kode_produk }}] {{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            @if(request()->hasAny(['search', 'start_date', 'end_date', 'supplier', 'product_id']))
                <a href="{{ route('transaksi.masuk') }}" data-ajax-reset="true" class="text-[11px] text-rose-600 hover:text-rose-700 font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Hapus Filter Aktif</span>
                </a>
            @endif
        </div>
    </div>

    <!-- History Table (Full Width) -->
    <div id="ajax-table-container" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Riwayat Penerimaan Stok Masuk</h3>
            <span class="text-xs text-slate-500">Total Transaksi: {{ $transactions->total() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-semibold">
                    <tr>
                        <th class="py-3 px-4">Tanggal & No. Nota</th>
                        <th class="py-3 px-4">SKU / Nama Produk</th>
                        <th class="py-3 px-4">Jumlah Masuk</th>
                        <th class="py-3 px-4">Harga & Total Tagihan</th>
                        <th class="py-3 px-4">Supplier & Surat Jalan</th>
                        <th class="py-3 px-4 text-center">Status Bayar</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="text-slate-900 font-bold">{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</div>
                                <div class="font-mono text-[11px] text-orange-600 font-semibold">{{ $t->no_nota ?: 'NOTA-IN' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-[11px] font-mono text-slate-400 font-semibold">[{{ $t->product->kode_produk ?? 'TEX' }}]</span>
                                <div class="font-semibold text-slate-900">{{ $t->product->nama ?? 'Produk Dihapus' }}</div>
                            </td>
                            <td class="py-3 px-4 font-bold text-emerald-600">
                                +{{ number_format($t->jumlah, 0, ',', '.') }} <span class="text-xs font-normal text-slate-500">{{ $t->product->satuan ?? 'Pcs' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">
                                    Rp {{ number_format($t->total_harga ?: ($t->jumlah * ($t->harga_beli_satuan ?: ($t->product->harga_beli_per_satuan ?? 0))), 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400">@ Rp {{ number_format($t->harga_beli_satuan ?: ($t->product->harga_beli_per_satuan ?? 0), 0, ',', '.') }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-medium text-slate-800">{{ $t->supplier ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400">SJ: {{ $t->no_surat_jalan ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if(($t->status_pembayaran ?? 'LUNAS') === 'LUNAS')
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        LUNAS
                                    </span>
                                @elseif(($t->status_pembayaran ?? '') === 'TEMPO')
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        TEMPO
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        DP
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('purchasing.nota.cetak', $t->id) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-700 font-semibold text-[11px] border border-orange-200 transition-colors inline-flex items-center gap-1" title="Cetak Nota Resmi">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Nota</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs">Belum ada transaksi penerimaan barang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-3.5 border-t border-slate-200 bg-slate-50">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL FORM STOK MASUK -->
    <div x-show="openModal" x-cloak @click.self="openModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs" x-transition>
        <div class="w-full max-w-2xl bg-white border border-slate-200 rounded-xl p-6 shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Form Penerimaan Stok Masuk (Inbound)</h3>
                    <p class="text-[11px] text-slate-500">Isi detail penerimaan pakaian jadi dari vendor konveksi untuk menambah stok fisik.</p>
                </div>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>

            <form action="{{ route('transaksi.masuk.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Searchable Combobox for Produk Pakaian Jadi -->
                    <div class="sm:col-span-2 relative" @click.away="dropdownOpen = false">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-semibold text-slate-700">
                                Pilih Produk Pakaian Jadi <span class="text-rose-500">*</span>
                            </label>
                            <button 
                                type="button" 
                                @click="scanProductBarcode()"
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 transition-colors shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Scan Barcode SKU</span>
                            </button>
                        </div>
                        
                        <input type="hidden" name="product_id" :value="selectedProduct" required>

                        <!-- Trigger Button -->
                        <button 
                            type="button" 
                            @click="dropdownOpen = !dropdownOpen; if(dropdownOpen) $nextTick(() => $refs.searchInput.focus())"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-left flex items-center justify-between hover:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all shadow-2xs">
                            <div class="flex items-center gap-2 truncate">
                                <template x-if="!selectedProduct">
                                    <span class="text-slate-400 font-medium">-- Klik untuk cari & pilih produk apparel / SKU --</span>
                                </template>
                                <template x-if="selectedProduct">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="px-1.5 py-0.5 rounded bg-navy-900 text-white font-mono font-bold text-[10px]" x-text="selectedProductSku"></span>
                                        <span class="font-bold text-slate-900 truncate" x-text="selectedProductName"></span>
                                        <span class="text-slate-500 text-[11px]" x-text="'(Sisa: ' + currentStock + ' ' + unit + ')'"></span>
                                    </div>
                                </template>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200" :class="dropdownOpen ? 'rotate-180 text-orange-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Panel -->
                        <div 
                            x-show="dropdownOpen" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 right-0 top-full mt-1.5 z-50 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">
                            
                            <!-- Live Search Input -->
                            <div class="p-2.5 bg-slate-50/90 border-b border-slate-200 sticky top-0 z-10 backdrop-blur-xs">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input 
                                        x-ref="searchInput"
                                        type="text" 
                                        x-model="searchQuery" 
                                        placeholder="Ketik kode SKU, nama produk (hoodie, kaos...), kategori, vendor..." 
                                        class="w-full pl-9 pr-7 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 font-medium focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/10">
                                    <template x-if="searchQuery">
                                        <button type="button" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600">&times;</button>
                                    </template>
                                </div>
                            </div>

                            <!-- Options List -->
                            <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 custom-scrollbar text-xs">
                                <template x-for="p in filteredProducts" :key="p.id">
                                    <div 
                                        @click="selectProduct(p)"
                                        class="p-3 hover:bg-orange-50/70 cursor-pointer transition-colors flex items-center justify-between gap-3 group"
                                        :class="selectedProduct === p.id ? 'bg-orange-50/90 font-semibold' : ''">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="px-1.5 py-0.5 rounded bg-slate-100 group-hover:bg-orange-100 text-slate-800 group-hover:text-orange-900 font-mono font-bold text-[10px] transition-colors" x-text="p.kode_produk"></span>
                                                <span class="font-bold text-slate-900 truncate" x-text="p.nama"></span>
                                            </div>
                                            <div class="flex items-center gap-3 text-[11px] text-slate-500 mt-1">
                                                <span x-text="p.kategori"></span>
                                                <span>&bull;</span>
                                                <span x-text="'Rak: ' + (p.lokasi_rak || '-')"></span>
                                                <template x-if="p.supplier_utama">
                                                    <span>&bull; <span x-text="p.supplier_utama"></span></span>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold"
                                                  :class="p.status_stok === 'KRITIS' ? 'bg-rose-100 text-rose-700' : (p.status_stok === 'NORMAL' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700')"
                                                  x-text="p.stok_aktual + ' ' + p.satuan">
                                            </span>
                                        </div>
                                    </div>
                                </template>

                                <!-- Empty State -->
                                <div x-show="filteredProducts.length === 0" class="p-6 text-center text-slate-400 text-xs">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Tidak ditemukan produk dengan kata kunci "<span x-text="searchQuery" class="font-bold text-slate-600"></span>"</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No. Nota Pembelian -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">No. Nota Pembelian / PO</label>
                        <input type="text" name="no_nota" value="NOTA-{{ date('Ym') }}-{{ strtoupper(substr(uniqid(), -5)) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Tanggal Penerimaan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Jumlah Masuk (<span x-text="unit"></span>) <span class="text-rose-500">*</span></label>
                        <input type="number" name="jumlah" min="1" x-model.number="inQty" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>

                    <!-- Harga Beli Satuan -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Harga Beli Satuan (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="harga_beli_satuan" min="0" x-model.number="unitPrice" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-emerald-800 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Diskon & PPN -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Potongan / Diskon (Rp)</label>
                        <input type="number" name="diskon" min="0" x-model.number="discount" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">PPN (%)</label>
                        <input type="number" name="ppn_persen" min="0" max="100" x-model.number="ppnPercent" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Status Pembayaran -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Status Pembayaran <span class="text-rose-500">*</span></label>
                        <select name="status_pembayaran" x-model="paymentStatus" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                            <option value="LUNAS">🟢 LUNAS (Cash / Transfer)</option>
                            <option value="TEMPO">🔴 TEMPO (Hutang Dagang)</option>
                            <option value="DP">🟡 DP / SEBAGIAN</option>
                        </select>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" x-model="paymentMethod" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                            <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                            <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                            <option value="Transfer Bank BRI/BNI">Transfer Bank BRI / BNI</option>
                            <option value="Tunai / Cash">Tunai / Cash</option>
                            <option value="Bilyet Giro / Cek">Bilyet Giro / Cek</option>
                            <option value="Termin 30 Hari">Termin 30 Hari</option>
                        </select>
                    </div>

                    <!-- Jatuh Tempo jika Tempo -->
                    <div x-show="paymentStatus === 'TEMPO'" x-transition class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-rose-700 mb-1">Tanggal Jatuh Tempo Pembayaran</label>
                        <input type="date" name="jatuh_tempo" x-model="dueDate" class="w-full px-3 py-2 bg-rose-50/50 border border-rose-200 rounded-lg text-xs text-rose-900 font-bold focus:bg-white focus:outline-none focus:border-rose-400">
                    </div>

                    <!-- Surat Jalan -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-semibold text-slate-700">No. Surat Jalan / Faktur</label>
                            <button 
                                type="button" 
                                @click="triggerBarcodeScan({ title: 'Scan Barcode Surat Jalan', targetInput: '#inbound_no_surat_jalan' })"
                                class="inline-flex items-center gap-1 text-[10px] font-semibold text-orange-600 hover:text-orange-700" title="Scan Barcode Dokumen">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Scan Barcode</span>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" id="inbound_no_surat_jalan" name="no_surat_jalan" placeholder="Contoh: SJ-AP-2026-098" class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <button 
                                type="button" 
                                @click="triggerBarcodeScan({ title: 'Scan Barcode Surat Jalan', targetInput: '#inbound_no_surat_jalan' })"
                                class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-orange-500 transition-colors" title="Scan Barcode Surat Jalan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Vendor Konveksi / Pabrik Garmen</label>
                        <input type="text" name="supplier" x-model="supplier" placeholder="Contoh: PT Konveksi Garment Mandiri" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400">
                    </div>

                    <!-- Lot / Dyeing Batch -->
                    <div class="sm:col-span-2">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-semibold text-slate-700">Nomor Batch Produksi / Lot QC</label>
                            <button 
                                type="button" 
                                @click="triggerBarcodeScan({ title: 'Scan Barcode Nomor Batch / QC Lot', targetInput: '#inbound_no_batch_lot' })"
                                class="inline-flex items-center gap-1 text-[10px] font-semibold text-orange-600 hover:text-orange-700" title="Scan Barcode Lot">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Scan Barcode</span>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" id="inbound_no_batch_lot" name="no_batch_lot" placeholder="Contoh: BATCH-HOD-2026-01" class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <button 
                                type="button" 
                                @click="triggerBarcodeScan({ title: 'Scan Barcode Nomor Batch / QC Lot', targetInput: '#inbound_no_batch_lot' })"
                                class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-orange-500 transition-colors" title="Scan Barcode Lot">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Catatan Kondisi Barang</label>
                        <textarea name="keterangan" rows="2" placeholder="QC passed, pakaian terbungkus polybag rapi..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-slate-400"></textarea>
                    </div>
                </div>

                <!-- Financial Calculation & Live Stock Projection -->
                <div class="p-4 rounded-xl bg-orange-50/70 border border-orange-200/80 space-y-2 text-xs">
                    <div class="font-bold text-navy-900 flex items-center justify-between">
                        <span>Rincian Nota Finansial:</span>
                        <span class="text-orange-700 font-bold" x-text="paymentStatus"></span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px] pt-1">
                        <div>
                            <span class="text-slate-500 block">Kuantitas Masuk:</span>
                            <span class="font-bold text-slate-900" x-text="(inQty || 0) + ' ' + unit"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Harga Satuan:</span>
                            <span class="font-bold text-slate-900" x-text="formatRupiah(unitPrice)"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Subtotal:</span>
                            <span class="font-bold text-slate-900" x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div>
                            <span class="text-orange-800 font-bold block">Total Tagihan:</span>
                            <span class="font-black text-sm text-navy-900" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                    </div>
                </div>

                <!-- Live Stock Projection Inside Modal -->
                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 space-y-2 text-xs">
                    <div class="font-semibold text-slate-800">Proyeksi Stok Setelah Masuk:</div>
                    <div class="grid grid-cols-3 gap-2 text-[11px]">
                        <div>
                            <span class="text-slate-500 block">Stok Awal:</span>
                            <span class="font-bold text-slate-900" x-text="currentStock + ' ' + unit"></span>
                        </div>
                        <div>
                            <span class="text-emerald-600 font-medium block">Tambahan:</span>
                            <span class="font-bold text-emerald-600" x-text="'+' + (inQty || 0) + ' ' + unit"></span>
                        </div>
                        <div>
                            <span class="text-slate-700 font-medium block">Total Akhir:</span>
                            <span class="font-bold text-slate-900" x-text="projectedTotal + ' ' + unit"></span>
                        </div>
                    </div>

                    <!-- 3-Zone Buffer Status Preview -->
                    <div x-show="selectedProduct" class="pt-2 border-t border-slate-200 text-[11px]">
                        <span class="text-slate-500">Status Buffer: </span>
                        <span class="font-bold" 
                              :class="projectedStatus ? projectedStatus.colorClass : 'text-slate-600'"
                              x-text="projectedStatus ? projectedStatus.label : 'Pilih produk dahulu'">
                        </span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                    <button type="button" @click="openModal = false" class="px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Stok Masuk</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
