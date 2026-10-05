@extends('layouts.app')

@section('title', 'Laporan Mutasi & Valuasi Persediaan')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-navy-900 tracking-tight">Laporan Mutasi & Valuasi Persediaan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi pergerakan stok awal, masuk, keluar, dan valuasi persediaan produk pakaian jadi.</p>
        </div>

        <button onclick="window.print()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-semibold text-xs rounded-lg flex items-center gap-1.5 transition-colors shadow-xs">
            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak / Ekspor</span>
        </button>
    </div>

    <!-- Filter Card -->
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <form action="{{ route('laporan.index') }}" method="GET" data-ajax-filter="true" data-ajax-target="#ajax-table-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <!-- Search Keyword -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-[11px] font-semibold text-slate-700">Cari Produk</label>
                    <span class="ajax-live-badge"><span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Realtime</span></span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        id="laporan_search_input"
                        type="text" 
                        name="search" 
                        value="{{ $search ?? '' }}" 
                        placeholder="Ketik nama produk (hoodie, kaos...), SKU..." 
                        class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <button 
                        type="button" 
                        @click="triggerBarcodeScan({ title: 'Scan Barcode untuk Cari Laporan', targetInput: '#laporan_search_input' })"
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-orange-500 transition-colors" title="Scan Barcode">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </button>
                </div>
            </div>

            <!-- Tanggal Mulai -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Tanggal Akhir -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Kategori</label>
                <select name="kategori" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status RBL & Actions -->
            <div class="flex gap-2">
                <div class="flex-1">
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Status RBL</label>
                    <select name="status_stok" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                        <option value="all">Semua Status</option>
                        <option value="KRITIS" {{ ($statusStok ?? '') == 'KRITIS' ? 'selected' : '' }}>🔴 Kritis</option>
                        <option value="NORMAL" {{ ($statusStok ?? '') == 'NORMAL' ? 'selected' : '' }}>🟢 Normal</option>
                        <option value="BERLEBIH" {{ ($statusStok ?? '') == 'BERLEBIH' ? 'selected' : '' }}>🔵 Berlebih</option>
                    </select>
                </div>
                <div class="self-end flex gap-1.5">
                    <button type="submit" class="px-3.5 py-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs flex items-center justify-center">
                        Filter
                    </button>
                    <a href="{{ route('laporan.index') }}" data-ajax-reset="true" class="px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-medium text-xs rounded-lg border border-slate-200 transition-colors flex items-center justify-center" title="Reset Filter">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary KPI Cards -->
    <div data-ajax-sync="laporan-kpis" class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 block uppercase tracking-wider">Total Masuk (Periode)</span>
            <div class="text-2xl font-bold text-emerald-600 mt-1.5">+{{ number_format($totalMasuk, 0, ',', '.') }} Unit</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 block uppercase tracking-wider">Total Keluar (Periode)</span>
            <div class="text-2xl font-bold text-rose-600 mt-1.5">-{{ number_format($totalKeluar, 0, ',', '.') }} Unit</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 block uppercase tracking-wider">Total Valuasi Aset</span>
            <div class="text-2xl font-bold text-slate-900 mt-1.5">Rp {{ number_format($totalValuasi, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Report Table -->
    <div id="ajax-table-container" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Tabel Mutasi Persediaan ({{ $startDate }} s/d {{ $endDate }})</h3>
            <span class="text-xs text-slate-500">Total: {{ $reportData->count() }} SKU</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-semibold">
                    <tr>
                        <th class="py-3 px-4">SKU / Nama Produk</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Saldo Awal</th>
                        <th class="py-3 px-4 text-emerald-600">Total Masuk</th>
                        <th class="py-3 px-4 text-rose-600">Total Keluar</th>
                        <th class="py-3 px-4">Stok Akhir</th>
                        <th class="py-3 px-4">Status RBL</th>
                        <th class="py-3 px-4 text-right">Valuasi (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($reportData as $row)
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="text-[11px] font-mono text-slate-400 font-semibold">[{{ $row['product']->kode_produk }}]</span>
                                <div class="font-semibold text-slate-900">{{ $row['product']->nama }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $row['product']->kategori }}</td>
                            <td class="py-3.5 px-4 font-medium text-slate-600">{{ $row['stok_awal'] }} {{ $row['product']->satuan }}</td>
                            <td class="py-3.5 px-4 font-bold text-emerald-600">+{{ $row['total_masuk'] }}</td>
                            <td class="py-3.5 px-4 font-bold text-rose-600">-{{ $row['total_keluar'] }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $row['stok_akhir'] }} {{ $row['product']->satuan }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $row['product']->rbl_meta['badge_class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $row['product']->rbl_meta['dot_class'] }}"></span>
                                    {{ $row['status_stok'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-slate-900">
                                Rp {{ number_format($row['valuasi'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 text-xs">Tidak ada data mutasi untuk filter yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($reportData->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $reportData->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
