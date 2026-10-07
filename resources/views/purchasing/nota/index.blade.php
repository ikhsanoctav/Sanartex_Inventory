@extends('layouts.app')

@section('title', 'Nota Pembelian & PO Masuk')

@section('content')
<div class="space-y-6" x-data="{
    openCreateModal: false,
    openDetailModal: false,
    openPaymentModal: false,
    activeNota: null,
    selectedProduct: '',
    selectedProductName: '',
    selectedProductSku: '',
    currentStock: 0,
    unit: 'Pcs',
    supplier: '',
    min: 0,
    max: 0,
    inQty: 1,
    unitPrice: 0,
    discount: 0,
    ppnPercent: 0,
    searchQuery: '',
    dropdownOpen: false,
    paymentStatus: 'LUNAS',
    paymentMethod: 'Transfer Bank BCA',
    dueDate: '{{ now()->addDays(30)->format('Y-m-d') }}',
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
    get projectedTotal() {
        return (parseInt(this.currentStock) || 0) + (parseInt(this.inQty) || 0);
    },
    formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    },
    showDetail(item) {
        this.activeNota = item;
        this.openDetailModal = true;
    },
    showPayment(item) {
        this.activeNota = item;
        this.openPaymentModal = true;
    }
}">

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                    🛒 Procurement & Purchasing
                </span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs text-slate-500 font-medium">Dokumen Keuangan Pengadaan</span>
            </div>
            <h1 class="text-xl font-bold text-navy-900 tracking-tight mt-1">Nota Pembelian & PO Barang Masuk</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola faktur belanja pengadaan pakaian jadi dari vendor konveksi, pencatatan harga beli satuan, dan status tagihan tempo.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('transaksi.masuk') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1.5 shadow-3xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Riwayat Inbound</span>
            </a>
            <button 
                type="button"
                @click="openCreateModal = true"
                class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold text-xs rounded-xl transition-all shadow-sm shadow-orange-600/20 hover:scale-[1.02]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buat Nota Pembelian</span>
            </button>
        </div>
    </div>

    <!-- 4 Summary KPI Tiles -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- KPI 1: Total Belanja Pengadaan -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Total Akumulasi Belanja PO</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-navy-900 tracking-tight">
                    Rp {{ number_format($totalBelanja, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span>Dari <strong>{{ $totalNotaCount }} Nota Pembelian</strong></span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Rata-rata per Nota:</span>
                <span class="font-bold text-slate-800">Rp {{ number_format($totalNotaCount > 0 ? $totalBelanja / $totalNotaCount : 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- KPI 2: Total Pembayaran Lunas -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Nota Terbayar (Lunas)</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-emerald-600 tracking-tight">
                    Rp {{ number_format($totalLunas, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span class="font-bold text-emerald-700">{{ $countLunas }} Nota</span> Lunas tanpa tunggakan
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Rasio Pelunasan:</span>
                <span class="font-bold text-emerald-700">{{ $totalBelanja > 0 ? round(($totalLunas / $totalBelanja) * 100, 1) : 100 }}%</span>
            </div>
        </div>

        <!-- KPI 3: Tagihan Tempo / Hutang Dagang -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Hutang Dagang (Tempo)</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black {{ $totalTempo > 0 ? 'text-rose-600' : 'text-slate-800' }} tracking-tight">
                    Rp {{ number_format($totalTempo, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span class="font-bold text-rose-600">{{ $countTempo }} Nota Tagihan</span> Berjalan
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Status Pembayaran:</span>
                <span class="font-bold {{ $countTempo > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $countTempo > 0 ? 'Ada Tagihan Vendor' : 'Bebas Hutang' }}</span>
            </div>
        </div>

        <!-- KPI 4: Jumlah Faktur & Mitra Vendor -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Mitra Vendor Konveksi</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-indigo-700 tracking-tight">
                    {{ $suppliers->count() }} <span class="text-xs font-semibold text-slate-500">Pabrik / Vendor</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1 text-[11px] text-slate-500">
                    <span>Total <strong>{{ $totalNotaCount }} Dokumen Nota</strong> diterbitkan</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Aksi Cepat:</span>
                <a href="{{ route('rbl.analisis') }}" class="font-bold text-orange-600 hover:underline">Cek Matriks PO &rarr;</a>
            </div>
        </div>

    </div>

    <!-- Filter & Search Panel -->
    <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
        <form method="GET" action="{{ route('purchasing.nota.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            <!-- Search Keyword -->
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Cari No. Nota / SJ / Vendor / SKU</label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Contoh: NOTA-202610, PT Garmen, Kaos..." 
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Filter Status Pembayaran -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Status Pembayaran</label>
                <select name="status_pembayaran" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    <option value="all">Semua Status</option>
                    <option value="LUNAS" {{ request('status_pembayaran') == 'LUNAS' ? 'selected' : '' }}>🟢 Lunas</option>
                    <option value="TEMPO" {{ request('status_pembayaran') == 'TEMPO' ? 'selected' : '' }}>🔴 Tempo (Hutang)</option>
                    <option value="DP" {{ request('status_pembayaran') == 'DP' ? 'selected' : '' }}>🟡 DP / Sebagian</option>
                </select>
            </div>

            <!-- Filter Supplier / Vendor -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Vendor Konveksi</label>
                <select name="supplier" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    <option value="all">Semua Vendor</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup }}" {{ request('supplier') == $sup ? 'selected' : '' }}>{{ $sup }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Button -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-navy-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors shadow-2xs">
                    Filter Nota
                </button>
                @if(request()->hasAny(['search', 'status_pembayaran', 'supplier', 'start_date', 'end_date']))
                    <a href="{{ route('purchasing.nota.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Data Table: Nota Pembelian & Faktur -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-navy-900">Daftar Dokumen Nota Pembelian & PO</h2>
                <p class="text-xs text-slate-500 mt-0.5">Riwayat lengkap faktur pengadaan beserta rincian nominal dan status pembayaran</p>
            </div>
            <span class="text-xs text-slate-500 font-medium">Menampilkan <strong>{{ $transactions->count() }}</strong> dari <strong>{{ $transactions->total() }}</strong> Dokumen</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">No. Nota & Tanggal</th>
                        <th class="p-3.5">Vendor Konveksi</th>
                        <th class="p-3.5">Produk Apparel & Kuantitas</th>
                        <th class="p-3.5">Harga Beli Satuan</th>
                        <th class="p-3.5">Total Tagihan</th>
                        <th class="p-3.5 text-center">Status Pembayaran</th>
                        <th class="p-3.5 text-right">Aksi & Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- No. Nota & Tanggal -->
                            <td class="p-3.5">
                                <div class="font-mono font-bold text-navy-900 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>{{ $t->no_nota }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}
                                    @if($t->no_surat_jalan)
                                        <span class="text-slate-400">&bull; SJ: {{ $t->no_surat_jalan }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Vendor -->
                            <td class="p-3.5">
                                <div class="font-bold text-slate-800">{{ $t->supplier ?: ($t->product->supplier_utama ?? 'Vendor Umum') }}</div>
                                <div class="text-[10px] text-slate-400">Metode: {{ $t->metode_pembayaran ?: 'Transfer Bank' }}</div>
                            </td>

                            <!-- Produk & Qty -->
                            <td class="p-3.5">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 font-mono font-bold text-[10px]">{{ $t->product->kode_produk ?? '-' }}</span>
                                    <span class="font-bold text-slate-900 truncate max-w-[200px]">{{ $t->product->nama ?? 'Produk Dihapus' }}</span>
                                </div>
                                <div class="text-[11px] font-semibold text-emerald-700 mt-0.5">
                                    +{{ number_format($t->jumlah) }} {{ $t->product->satuan ?? 'Pcs' }}
                                    @if($t->no_batch_lot)
                                        <span class="text-slate-400 font-normal">&bull; Lot: {{ $t->no_batch_lot }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Harga Satuan -->
                            <td class="p-3.5 font-medium text-slate-700">
                                Rp {{ number_format($t->harga_beli_satuan ?: ($t->product->harga_beli_per_satuan ?? 0), 0, ',', '.') }}
                                <div class="text-[10px] text-slate-400">/ {{ $t->product->satuan ?? 'Pcs' }}</div>
                            </td>

                            <!-- Total Tagihan -->
                            <td class="p-3.5">
                                <div class="text-sm font-black text-navy-900">
                                    Rp {{ number_format($t->total_harga ?: ($t->jumlah * ($t->harga_beli_satuan ?: ($t->product->harga_beli_per_satuan ?? 0))), 0, ',', '.') }}
                                </div>
                                @if($t->diskon > 0)
                                    <div class="text-[10px] text-emerald-600">Diskon: -Rp {{ number_format($t->diskon, 0, ',', '.') }}</div>
                                @endif
                            </td>

                            <!-- Status Pembayaran -->
                            <td class="p-3.5 text-center">
                                @if($t->status_pembayaran === 'LUNAS')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>LUNAS</span>
                                    </span>
                                @elseif($t->status_pembayaran === 'TEMPO')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>TEMPO</span>
                                    </span>
                                    @if($t->jatuh_tempo)
                                        <div class="text-[10px] text-rose-600 font-semibold mt-0.5">
                                            Due: {{ \Carbon\Carbon::parse($t->jatuh_tempo)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <span>DP / SEBAGIAN</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi & Dokumen -->
                            <td class="p-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    
                                    <!-- Tombol Cetak Nota -->
                                    <a href="{{ route('purchasing.nota.cetak', $t->id) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-700 font-semibold text-[11px] border border-orange-200 transition-colors inline-flex items-center gap-1" title="Cetak Nota Resmi PDF / Print">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>Cetak</span>
                                    </a>

                                    <!-- Tombol Ubah Status Pembayaran jika Tempo -->
                                    @if($t->status_pembayaran !== 'LUNAS')
                                        <button 
                                            type="button" 
                                            @click="showPayment({{ Js::from($t) }})"
                                            class="px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-[11px] border border-emerald-200 transition-colors" title="Lunasi Tagihan">
                                            <span>Bayar</span>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Belum ada data Nota Pembelian</h4>
                                <p class="text-xs text-slate-500 mt-1">Klik tombol "+ Buat Nota Pembelian" di atas untuk mencatat faktur belanja pakaian jadi baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL FORM BUAT NOTA PEMBELIAN & TERIMA BARANG -->
    <div x-show="openCreateModal" x-cloak @click.self="openCreateModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" x-transition>
        <div class="w-full max-w-2xl bg-white border border-slate-200 rounded-2xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-navy-900">Form Nota Pembelian & Penerimaan Stok</h3>
                        <p class="text-xs text-slate-500">Catat faktur pengadaan barang masuk, harga beli satuan, dan status pembayaran vendor.</p>
                    </div>
                </div>
                <button @click="openCreateModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('transaksi.masuk.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="nota">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    
                    <!-- Searchable Combobox for Produk -->
                    <div class="sm:col-span-2 relative" @click.away="dropdownOpen = false">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-semibold text-slate-700">
                                Pilih Produk Pakaian Jadi <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] text-slate-400">Harga beli satuan akan otomatis terisi</span>
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
                                        <span class="text-slate-500 text-[11px]" x-text="'(Stok Saat Ini: ' + currentStock + ' ' + unit + ')'"></span>
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
                            x-transition
                            class="absolute left-0 right-0 top-full mt-1.5 z-50 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">
                            <div class="p-2.5 bg-slate-50 border-b border-slate-200">
                                <input 
                                    x-ref="searchInput"
                                    type="text" 
                                    x-model="searchQuery" 
                                    placeholder="Ketik nama produk, SKU, kategori, atau vendor..." 
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-orange-500">
                            </div>
                            <div class="max-h-56 overflow-y-auto divide-y divide-slate-100 text-xs">
                                <template x-for="p in filteredProducts" :key="p.id">
                                    <div 
                                        @click="selectProduct(p)"
                                        class="p-3 hover:bg-orange-50/70 cursor-pointer transition-colors flex items-center justify-between gap-2"
                                        :class="selectedProduct === p.id ? 'bg-orange-50/90 font-semibold' : ''">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 font-mono font-bold text-[10px]" x-text="p.kode_produk"></span>
                                                <span class="font-bold text-slate-900 truncate" x-text="p.nama"></span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 mt-0.5" x-text="p.supplier_utama || 'Vendor Umum'"></div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <div class="font-bold text-navy-900" x-text="formatRupiah(p.harga_beli_per_satuan)"></div>
                                            <div class="text-[10px] text-slate-400" x-text="'Stok: ' + p.stok_aktual + ' ' + p.satuan"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- No. Nota Pembelian -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">No. Nota Pembelian / PO</label>
                        <input type="text" name="no_nota" value="NOTA-{{ date('Ym') }}-{{ strtoupper(substr(uniqid(), -5)) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Jumlah Masuk -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Jumlah Masuk (<span x-text="unit"></span>) <span class="text-rose-500">*</span></label>
                        <input type="number" name="jumlah" min="1" x-model.number="inQty" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Harga Beli Satuan -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Harga Beli Satuan (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="harga_beli_satuan" min="0" x-model.number="unitPrice" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-emerald-800 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Diskon & PPN -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Potongan / Diskon (Rp)</label>
                        <input type="number" name="diskon" min="0" x-model.number="discount" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">PPN (%)</label>
                        <input type="number" name="ppn_persen" min="0" max="100" x-model.number="ppnPercent" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Status Pembayaran -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Status Pembayaran <span class="text-rose-500">*</span></label>
                        <select name="status_pembayaran" x-model="paymentStatus" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                            <option value="LUNAS">🟢 LUNAS (Cash / Transfer)</option>
                            <option value="TEMPO">🔴 TEMPO (Hutang Dagang)</option>
                            <option value="DP">🟡 DP / SEBAGIAN</option>
                        </select>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" x-model="paymentMethod" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
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
                        <input type="date" name="jatuh_tempo" x-model="dueDate" class="w-full px-3 py-2 bg-rose-50/50 border border-rose-200 rounded-xl text-xs text-rose-900 font-bold focus:bg-white focus:outline-none focus:border-rose-400">
                    </div>

                    <!-- Vendor & Surat Jalan -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Vendor Konveksi / Pabrik</label>
                        <input type="text" name="supplier" x-model="supplier" placeholder="Contoh: PT Garmen Nusantara" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">No. Surat Jalan Vendor</label>
                        <input type="text" name="no_surat_jalan" placeholder="Contoh: SJ-GRM-8899" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- No. Batch / Lot QC -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nomor Batch / Lot QC</label>
                        <input type="text" name="no_batch_lot" placeholder="Contoh: BATCH-QC-771" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <!-- Upload Foto / File Nota Fisik -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Lampiran Foto Nota / Invoice Vendor</label>
                        <input type="file" name="bukti_nota" accept="image/*,.pdf" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 focus:bg-white focus:outline-none">
                    </div>

                    <!-- Catatan -->
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Catatan Tambahan Pengadaan</label>
                        <textarea name="keterangan" rows="2" placeholder="Catatan kondisi bahan, no rekening pembayaran vendor..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500"></textarea>
                    </div>

                </div>

                <!-- Live Calculation Summary Box -->
                <div class="p-4 rounded-xl bg-orange-50/70 border border-orange-200/80 space-y-2 text-xs">
                    <div class="font-bold text-navy-900 flex items-center justify-between">
                        <span>Rincian Kalkulasi Nota Pembelian:</span>
                        <span class="text-orange-700 font-bold" x-text="paymentStatus"></span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px] pt-1">
                        <div>
                            <span class="text-slate-500 block">Kuantitas:</span>
                            <span class="font-bold text-slate-900" x-text="(inQty || 0) + ' ' + unit"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Subtotal:</span>
                            <span class="font-bold text-slate-900" x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">PPN (11%):</span>
                            <span class="font-bold text-slate-900" x-text="formatRupiah(ppnNominal)"></span>
                        </div>
                        <div>
                            <span class="text-orange-800 font-bold block">Grand Total:</span>
                            <span class="font-black text-sm text-navy-900" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" @click="openCreateModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-semibold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm shadow-orange-600/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan & Terbitkan Nota</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- MODAL PELUNASAN / UPDATE STATUS PEMBAYARAN -->
    <div x-show="openPaymentModal" x-cloak @click.self="openPaymentModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" x-transition>
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl p-6 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-navy-900">Pelunasan / Update Status Nota</h3>
                <button @click="openPaymentModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <template x-if="activeNota">
                <form :action="'/purchasing/nota/' + activeNota.id + '/status'" method="POST" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1">
                        <div class="flex justify-between">
                            <span class="text-slate-500">No. Nota:</span>
                            <span class="font-bold text-navy-900" x-text="activeNota.no_nota"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Vendor:</span>
                            <span class="font-bold text-slate-800" x-text="activeNota.supplier || '-'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Total Tagihan:</span>
                            <span class="font-black text-rose-600" x-text="formatRupiah(activeNota.total_harga)"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Ubah Status Pembayaran</label>
                        <select name="status_pembayaran" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                            <option value="LUNAS">🟢 LUNAS (Sudah Dibayar)</option>
                            <option value="DP">🟡 DP / SEBAGIAN</option>
                            <option value="TEMPO">🔴 TEMPO (Belum Lunas)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Metode Pembayaran Pelunasan</label>
                        <select name="metode_pembayaran" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                            <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                            <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                            <option value="Tunai / Kasir">Tunai / Kasir</option>
                            <option value="Bilyet Giro">Bilyet Giro</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Catatan Pelunasan / No. Ref Transfer</label>
                        <input type="text" name="keterangan_pelunasan" placeholder="Contoh: Bukti transfer BCA No. TRF-9921" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="openPaymentModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
