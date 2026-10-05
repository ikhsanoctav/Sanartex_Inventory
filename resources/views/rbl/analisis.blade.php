@extends('layouts.app')

@section('title', 'Analisis Buffer RBL')

@section('content')
<div class="space-y-6" x-data="{ 
    activeTab: 'reorder',
    searchQuery: '',
    statusFilter: 'all',
    kategoriFilter: 'all',
    reordersList: {{ Js::from($reorders->values()) }},
    allProductsList: {{ Js::from($allProducts->values()) }},
    get filteredReorders() {
        return this.reordersList.filter(item => {
            const p = item.product;
            const matchesSearch = !this.searchQuery.trim() || 
                (p.nama && p.nama.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                (p.kode_produk && p.kode_produk.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                (p.supplier_utama && p.supplier_utama.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                (p.kategori && p.kategori.toLowerCase().includes(this.searchQuery.toLowerCase()));
            
            const matchesKategori = this.kategoriFilter === 'all' || p.kategori === this.kategoriFilter;
            return matchesSearch && matchesKategori;
        });
    },
    get filteredAllProducts() {
        return this.allProductsList.filter(p => {
            const matchesSearch = !this.searchQuery.trim() || 
                (p.nama && p.nama.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                (p.kode_produk && p.kode_produk.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                (p.supplier_utama && p.supplier_utama.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                (p.kategori && p.kategori.toLowerCase().includes(this.searchQuery.toLowerCase()));
            
            const matchesStatus = this.statusFilter === 'all' || p.status_stok === this.statusFilter;
            const matchesKategori = this.kategoriFilter === 'all' || p.kategori === this.kategoriFilter;
            return matchesSearch && matchesStatus && matchesKategori;
        });
    },
    get filteredTotalCost() {
        return this.filteredReorders.reduce((sum, item) => sum + (item.estimated_cost || 0), 0);
    },
    resetFilters() {
        this.searchQuery = '';
        this.statusFilter = 'all';
        this.kategoriFilter = 'all';
    }
}">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-navy-900 tracking-tight">Analisis Buffer Stok & Reorder RBL (3 Zona)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Monitoring level buffer produk pakaian jadi dan kalkulasi otomatis draft restock PO.</p>
        </div>

        <button onclick="window.print()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-semibold text-xs rounded-lg flex items-center gap-1.5 transition-colors shadow-xs">
            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Rekap</span>
        </button>
    </div>

    <!-- 3 Zone Breakdown Summary Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <div @click="activeTab = 'all'; statusFilter = 'KRITIS'" class="cursor-pointer p-4 rounded-xl bg-white border border-slate-200 hover:border-rose-300 shadow-sm transition-all group">
            <div class="flex justify-between items-center text-xs text-rose-600 font-semibold">
                <span>Zona Kritis</span>
                <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded bg-rose-50 text-rose-700">Merah</span>
            </div>
            <div class="text-2xl font-bold text-slate-900 mt-2">{{ $kritis->count() }} <span class="text-xs font-normal text-slate-500">SKU</span></div>
            <p class="text-[11px] text-slate-400 mt-0.5">Stok &le; Safety Stock (Perlu PO)</p>
        </div>

        <div @click="activeTab = 'all'; statusFilter = 'NORMAL'" class="cursor-pointer p-4 rounded-xl bg-white border border-slate-200 hover:border-emerald-300 shadow-sm transition-all group">
            <div class="flex justify-between items-center text-xs text-emerald-600 font-semibold">
                <span>Zona Normal</span>
                <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700">Hijau</span>
            </div>
            <div class="text-2xl font-bold text-slate-900 mt-2">{{ $normal->count() }} <span class="text-xs font-normal text-slate-500">SKU</span></div>
            <p class="text-[11px] text-slate-400 mt-0.5">Min &lt; Stok &le; Max (Stok Aman)</p>
        </div>

        <div @click="activeTab = 'all'; statusFilter = 'BERLEBIH'" class="cursor-pointer p-4 rounded-xl bg-white border border-slate-200 hover:border-blue-300 shadow-sm transition-all group">
            <div class="flex justify-between items-center text-xs text-blue-600 font-semibold">
                <span>Zona Berlebih</span>
                <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-700">Biru</span>
            </div>
            <div class="text-2xl font-bold text-slate-900 mt-2">{{ $berlebih->count() }} <span class="text-xs font-normal text-slate-500">SKU</span></div>
            <p class="text-[11px] text-slate-400 mt-0.5">Stok &gt; Max Buffer (Overstock)</p>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <!-- Search Keyword -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-[11px] font-semibold text-slate-700">Cari Analisis RBL</label>
                    <span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Realtime</span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        id="rbl_search_input"
                        type="text" 
                        x-model="searchQuery" 
                        placeholder="Ketik SKU, nama produk (hoodie, kaos...), supplier, kategori..." 
                        class="w-full pl-9 pr-14 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center gap-1">
                        <template x-if="searchQuery">
                            <button type="button" @click="searchQuery = ''" class="p-1 text-slate-400 hover:text-slate-600">&times;</button>
                        </template>
                        <button 
                            type="button" 
                            @click="triggerBarcodeScan({ title: 'Scan Barcode untuk Cari RBL', callback: (code) => { searchQuery = code; } })"
                            class="p-1 text-slate-400 hover:text-orange-500 transition-colors" title="Scan Barcode">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Kategori -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Kategori Produk</label>
                <select x-model="kategoriFilter" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <option value="all">Semua Kategori</option>
                    @foreach($allProducts->pluck('kategori')->unique() as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status RBL (Khusus Tab 2) -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Filter Status Zona</label>
                <select x-model="statusFilter" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <option value="all">Semua Status RBL</option>
                    <option value="KRITIS">🔴 Zona Kritis (Merah)</option>
                    <option value="NORMAL">🟢 Zona Normal (Hijau)</option>
                    <option value="BERLEBIH">🔵 Zona Berlebih (Biru)</option>
                </select>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
            <span class="text-[11px] text-slate-500">
                Menampilkan <strong class="text-slate-800" x-text="activeTab === 'reorder' ? filteredReorders.length : filteredAllProducts.length"></strong> data
            </span>
            <template x-if="searchQuery || statusFilter !== 'all' || kategoriFilter !== 'all'">
                <button type="button" @click="resetFilters()" class="text-[11px] text-rose-600 hover:text-rose-700 font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Reset Filter</span>
                </button>
            </template>
        </div>
    </div>

    <!-- Main Card: Reorder Generator & Buffer Analysis -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        
        <!-- Tab Controls -->
        <div class="flex flex-wrap items-center justify-between p-4 border-b border-slate-200 gap-3">
            <div class="flex items-center gap-2">
                <button 
                    @click="activeTab = 'reorder'" 
                    :class="activeTab === 'reorder' ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                    class="px-3.5 py-1.5 rounded-lg text-xs transition-colors flex items-center gap-1.5">
                    <span>Saran Pengadaan PO</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-rose-500 text-white" x-text="filteredReorders.length"></span>
                </button>
                <button 
                    @click="activeTab = 'all'" 
                    :class="activeTab === 'all' ? 'bg-navy-900 text-white font-semibold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                    class="px-3.5 py-1.5 rounded-lg text-xs transition-colors flex items-center gap-1.5">
                    <span>Semua Status SKU</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-300 text-slate-800" x-text="filteredAllProducts.length"></span>
                </button>
            </div>

            <div class="text-right">
                <span class="text-xs text-slate-500">Estimasi Total Biaya PO: </span>
                <span class="text-sm font-bold text-slate-900" x-text="'Rp ' + filteredTotalCost.toLocaleString('id-ID')"></span>
            </div>
        </div>

        <!-- TAB 1: REORDER SUGGESTIONS TABLE -->
        <div x-show="activeTab === 'reorder'">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-semibold">
                        <tr>
                            <th class="py-3 px-4">Prioritas</th>
                            <th class="py-3 px-4">SKU / Nama Produk</th>
                            <th class="py-3 px-4">Stok Saat Ini</th>
                            <th class="py-3 px-4">Batas Min / Max</th>
                            <th class="py-3 px-4">Saran Order</th>
                            <th class="py-3 px-4">Supplier</th>
                            <th class="py-3 px-4">Lead Time</th>
                            <th class="py-3 px-4 text-right">Estimasi Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <template x-for="r in filteredReorders" :key="r.product.id">
                            <tr class="hover:bg-slate-50/75 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800" x-text="r.priority"></span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-[11px] font-mono text-slate-400 font-semibold" x-text="'[' + r.product.kode_produk + ']'"></span>
                                    <div class="font-semibold text-slate-900" x-text="r.product.nama"></div>
                                    <div class="text-[10px] text-slate-400" x-text="r.product.kategori"></div>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-rose-600" x-text="r.product.stok_aktual + ' ' + r.product.satuan"></td>
                                <td class="py-3.5 px-4 text-slate-500" x-text="'Min: ' + r.product.batas_minimum + ' / Max: ' + r.product.batas_maksimum"></td>
                                <td class="py-3.5 px-4 font-bold text-slate-900" x-text="'+' + r.suggested_order + ' ' + r.product.satuan"></td>
                                <td class="py-3.5 px-4 text-slate-600" x-text="r.product.supplier_utama || '-'"></td>
                                <td class="py-3.5 px-4 text-slate-500" x-text="(r.product.lead_time_days || 0) + ' Hari'"></td>
                                <td class="py-3.5 px-4 text-right font-bold text-slate-900" x-text="'Rp ' + (r.estimated_cost || 0).toLocaleString('id-ID')"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredReorders.length === 0">
                            <td colspan="8" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada rekomendasi pengadaan yang sesuai dengan filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: ALL BUFFER STATUS TABLE -->
        <div x-show="activeTab === 'all'" style="display:none;">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-semibold">
                        <tr>
                            <th class="py-3 px-4">SKU / Nama Produk</th>
                            <th class="py-3 px-4">Stok Saat Ini</th>
                            <th class="py-3 px-4">Safety Stock (Min)</th>
                            <th class="py-3 px-4">Batas Max</th>
                            <th class="py-3 px-4">Status RBL</th>
                            <th class="py-3 px-4">Tindakan SOP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <template x-for="p in filteredAllProducts" :key="p.id">
                            <tr class="hover:bg-slate-50/75 transition-colors">
                                <td class="py-3 px-4">
                                    <span class="text-[11px] font-mono text-slate-400 font-semibold" x-text="'[' + p.kode_produk + ']'"></span>
                                    <div class="font-semibold text-slate-900" x-text="p.nama"></div>
                                    <div class="text-[10px] text-slate-400" x-text="p.kategori"></div>
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-900" x-text="p.stok_aktual + ' ' + p.satuan"></td>
                                <td class="py-3 px-4 text-slate-500" x-text="p.batas_minimum + ' ' + p.satuan"></td>
                                <td class="py-3 px-4 text-slate-500" x-text="p.batas_maksimum + ' ' + p.satuan"></td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                          :class="p.status_stok === 'KRITIS' ? 'bg-rose-100 text-rose-800' : (p.status_stok === 'NORMAL' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800')">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="p.status_stok === 'KRITIS' ? 'bg-rose-500' : (p.status_stok === 'NORMAL' ? 'bg-emerald-500' : 'bg-blue-500')"></span>
                                        <span x-text="p.status_stok"></span>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-medium" 
                                    x-text="p.status_stok === 'KRITIS' ? 'Segera terbitkan Purchase Order (PO)' : (p.status_stok === 'NORMAL' ? 'Pertahankan level stok operasional aman' : 'Tahan pengadaan baru, percepat alokasi produksi')">
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredAllProducts.length === 0">
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada data SKU yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
