<!-- Kepala Gudang Workspace (Fokus Manajerial, Kapasitas Gudang, Buffer RBL, & Zonasi Rak) -->
<div class="space-y-6">
    
    <!-- 1. Top Kepala Gudang KPI Tiles -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- KPI 1: Utilisasi Kapasitas Ruang Gudang -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Utilisasi Kapasitas Gudang</span>
                <div class="w-8 h-8 rounded-xl bg-navy-50 text-navy-800 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-navy-900 tracking-tight">
                    {{ $utilisasiGudang }}%
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 overflow-hidden">
                    <div class="bg-navy-900 h-2 rounded-full" style="width: {{ min(100, $utilisasiGudang) }}%"></div>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Total Stok Fisik:</span>
                <span class="font-bold text-slate-800">{{ number_format($totalStokFisik) }} / {{ number_format($totalKapasitasMaks) }} Unit</span>
            </div>
        </div>

        <!-- KPI 2: Skor Kepatuhan Buffer RBL -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Kesehatan Buffer RBL</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-emerald-600 tracking-tight">
                    {{ $healthScore }}% <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">OPTIMAL</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1.5 text-[11px] text-slate-500">
                    <span><strong>{{ $normalCount }}</strong> dari <strong>{{ $totalSKU }}</strong> SKU dalam zona ideal</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Matriks RBL:</span>
                <a href="{{ route('rbl.analisis') }}" class="font-bold text-emerald-600 hover:underline">Analisis Lengkap &rarr;</a>
            </div>
        </div>

        <!-- KPI 3: Item Butuh Intervensi -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Item Perlu Intervensi</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-rose-600 tracking-tight">
                    {{ $kritisCount + $berlebihCount }} <span class="text-xs font-semibold text-slate-500">SKU</span>
                </div>
                <div class="flex items-center gap-2 mt-1 text-[11px]">
                    <span class="text-rose-600 font-bold">{{ $kritisCount }} Kritis</span>
                    <span>&bull;</span>
                    <span class="text-blue-600 font-bold">{{ $berlebihCount }} Overstock</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Status Stok:</span>
                <span class="font-bold text-slate-700">Di luar ambang normal</span>
            </div>
        </div>

        <!-- KPI 4: Total Valuasi Fisik Gudang -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Valuasi Persediaan Fisik</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2.5">
                <div class="text-xl sm:text-2xl font-black text-navy-900 tracking-tight">
                    Rp {{ number_format($totalValuasi, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500">
                    <span>{{ number_format($totalPcs) }} Pcs &bull; {{ number_format($totalLusin) }} Lusin</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400">Laporan Mutasi:</span>
                <a href="{{ route('laporan.index') }}" class="font-bold text-orange-600 hover:underline">Lihat Laporan &rarr;</a>
            </div>
        </div>

    </div>

    <!-- 2. Interactive 3-Tier RBL Status Zone Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ 
        openModal: null, 
        modalSearch: '',
        kritisList: {{ Js::from($kritisProducts) }},
        normalList: {{ Js::from($normalProducts) }},
        berlebihList: {{ Js::from($berlebihProducts) }},
        get activeModalProducts() {
            let list = [];
            if (this.openModal === 'kritis') list = this.kritisList;
            else if (this.openModal === 'normal') list = this.normalList;
            else if (this.openModal === 'berlebih') list = this.berlebihList;
            
            if (!this.modalSearch.trim()) return list;
            const q = this.modalSearch.toLowerCase();
            return list.filter(p => 
                (p.nama && p.nama.toLowerCase().includes(q)) ||
                (p.kode_produk && p.kode_produk.toLowerCase().includes(q)) ||
                (p.kategori && p.kategori.toLowerCase().includes(q)) ||
                (p.lokasi_rak && p.lokasi_rak.toLowerCase().includes(q))
            );
        }
    }">
        <!-- 🔴 Zona Kritis -->
        <div @click="openModal = 'kritis'; modalSearch = ''" 
             class="cursor-pointer p-4 rounded-2xl bg-gradient-to-br from-white to-rose-50/40 border border-rose-200 hover:border-rose-400 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500 animate-ping"></span>
                    <span class="text-xs font-bold text-rose-700">🔴 Zona Kritis (&le; Batas Min)</span>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-800">
                    {{ $kritisCount }} SKU
                </span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">Stok mencapai atau di bawah batas minimum. Butuh koordinasi order restock dengan tim Purchasing.</p>
            <div class="mt-3 pt-2 border-t border-rose-100 flex items-center justify-between text-[11px] font-semibold text-rose-600">
                <span>Klik untuk drill-down data</span>
                <span>&rarr;</span>
            </div>
        </div>

        <!-- 🟢 Zona Normal -->
        <div @click="openModal = 'normal'; modalSearch = ''" 
             class="cursor-pointer p-4 rounded-2xl bg-gradient-to-br from-white to-emerald-50/40 border border-emerald-200 hover:border-emerald-400 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-bold text-emerald-700">🟢 Zona Normal (Min - Max)</span>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                    {{ $normalCount }} SKU
                </span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">Persediaan optimal dan lancar memenuhi pesanan pelanggan & toko cabang.</p>
            <div class="mt-3 pt-2 border-t border-emerald-100 flex items-center justify-between text-[11px] font-semibold text-emerald-600">
                <span>Klik untuk drill-down data</span>
                <span>&rarr;</span>
            </div>
        </div>

        <!-- 🔵 Zona Berlebih -->
        <div @click="openModal = 'berlebih'; modalSearch = ''" 
             class="cursor-pointer p-4 rounded-2xl bg-gradient-to-br from-white to-blue-50/40 border border-blue-200 hover:border-blue-400 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <span class="text-xs font-bold text-blue-700">🔵 Zona Berlebih (&gt; Batas Maks)</span>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">
                    {{ $berlebihCount }} SKU
                </span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">Stok melebihi kapasitas simpan rak gudang. Perlu diprioritaskan untuk program promosi / distribusi.</p>
            <div class="mt-3 pt-2 border-t border-blue-100 flex items-center justify-between text-[11px] font-semibold text-blue-600">
                <span>Klik untuk drill-down data</span>
                <span>&rarr;</span>
            </div>
        </div>

        <!-- Drill Down Modal -->
        <div x-show="openModal" 
             x-cloak
             @click.self="openModal = null"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-xs"
             x-transition>
            <div class="w-full max-w-2xl bg-white border border-slate-200 rounded-2xl p-6 shadow-2xl flex flex-col max-h-[85vh]">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-navy-900 flex items-center gap-2">
                        <span>Daftar Produk Apparel:</span>
                        <span class="uppercase font-extrabold text-orange-600" x-text="'Zona ' + openModal"></span>
                        <span class="text-xs font-normal text-slate-400" x-text="'(' + activeModalProducts.length + ' item)'"></span>
                    </h3>
                    <button @click="openModal = null" class="text-slate-400 hover:text-slate-700 text-xl font-bold leading-none">&times;</button>
                </div>

                <div class="mt-3">
                    <input 
                        type="text" 
                        x-model="modalSearch" 
                        placeholder="Cari SKU, nama apparel (hoodie, kaos...), atau lokasi rak..." 
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-orange-500">
                </div>

                <div class="mt-3 flex-1 overflow-y-auto custom-scrollbar border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="text-slate-500 bg-slate-50 uppercase text-[10px] font-semibold border-b border-slate-200 sticky top-0 z-10">
                            <tr>
                                <th class="p-2.5">SKU</th>
                                <th class="p-2.5">Nama Produk Apparel</th>
                                <th class="p-2.5">Stok Fisik</th>
                                <th class="p-2.5">Min / Max</th>
                                <th class="p-2.5">Lokasi Rak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <template x-for="p in activeModalProducts" :key="p.id">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-2.5 font-mono font-bold text-navy-900" x-text="p.kode_produk"></td>
                                    <td class="p-2.5">
                                        <div class="font-medium text-slate-900" x-text="p.nama"></div>
                                        <div class="text-[10px] text-slate-400" x-text="p.kategori"></div>
                                    </td>
                                    <td class="p-2.5 font-bold" 
                                        :class="openModal === 'kritis' ? 'text-rose-600' : (openModal === 'normal' ? 'text-emerald-600' : 'text-blue-600')"
                                        x-text="p.stok_aktual + ' ' + p.satuan">
                                    </td>
                                    <td class="p-2.5 text-slate-500" x-text="'Min: ' + p.batas_minimum + ' / Max: ' + p.batas_maksimum"></td>
                                    <td class="p-2.5 text-slate-500" x-text="p.lokasi_rak || '-'"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <button @click="openModal = null" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Dual Section (Item Perlu Perhatian & Top Fast-Moving) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <!-- Left: Item Butuh Perhatian Khusus -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-navy-900">Item Perlu Perhatian Manajerial</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Produk apparel yang berada di luar batas ideal (Kritis & Berlebih)</p>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                    {{ $attentionProducts->count() }} Item
                </span>
            </div>

            <div class="space-y-2 max-h-80 overflow-y-auto custom-scrollbar pr-1">
                @forelse($attentionProducts as $item)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs hover:bg-slate-100/60 transition-colors">
                        <div>
                            <div class="font-bold text-navy-900">[{{ $item->kode_produk }}] {{ $item->nama }}</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Stok: <strong class="{{ $item->status_stok === 'KRITIS' ? 'text-rose-600' : 'text-blue-600' }}">{{ $item->stok_aktual }} {{ $item->satuan }}</strong> &bull;
                                Min: {{ $item->batas_minimum }} &bull; Max: {{ $item->batas_maksimum }} &bull;
                                Rak: {{ $item->lokasi_rak ?? 'Area Utama' }}
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $item->status_stok === 'KRITIS' ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                            {{ $item->status_stok }}
                        </span>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">Semua persediaan pakaian jadi dalam kondisi normal.</div>
                @endforelse
            </div>
        </div>

        <!-- Right: Top 5 Fast-Moving Fabrics -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-navy-900">Top 5 Produk Paling Banyak Terjual / Keluar (*Fast-Moving*)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Produk apparel dengan volume pengiriman pesanan dan perputaran tertinggi</p>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-200">
                    High Demand
                </span>
            </div>

            <div class="space-y-2.5">
                @foreach($fastMovingProducts as $index => $item)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-6 h-6 rounded-lg bg-navy-900 text-white font-black text-xs flex items-center justify-center flex-shrink-0">
                                #{{ $index + 1 }}
                            </span>
                            <div class="min-w-0">
                                <div class="font-bold text-navy-900 truncate">[{{ $item['product']->kode_produk }}] {{ $item['product']->nama }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">
                                    Kategori: {{ $item['product']->kategori }} &bull; Rak: {{ $item['product']->lokasi_rak ?? 'Area Utama' }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="font-black text-orange-600 text-xs">{{ number_format($item['out_qty']) }} {{ $item['product']->satuan }}</div>
                            <span class="text-[10px] text-slate-400">Total Terpakai</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- 4. Peta Zonasi Rak & Utilisasi Ruang -->
    <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-navy-900">Peta Zonasi Rak Penyimpanan Gudang</h3>
                <p class="text-xs text-slate-500 mt-0.5">Distribusi penempatan produk pakaian jadi dan okupansi per blok rak</p>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                {{ $warehouseRacks->count() }} Lokasi Rak
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
            @foreach($warehouseRacks as $rack)
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-navy-900 text-xs">{{ $rack['rack'] }}</span>
                        @if($rack['has_kritis'])
                            <span class="w-2 h-2 rounded-full bg-rose-500" title="Ada stok kritis"></span>
                        @else
                            <span class="w-2 h-2 rounded-full bg-emerald-500" title="Stok aman"></span>
                        @endif
                    </div>
                    <div class="mt-2 flex items-baseline justify-between text-xs">
                        <span class="text-[11px] text-slate-500">{{ $rack['count'] }} Jenis SKU</span>
                        <span class="font-black text-navy-900">{{ number_format($rack['total_stock']) }} Unit</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
