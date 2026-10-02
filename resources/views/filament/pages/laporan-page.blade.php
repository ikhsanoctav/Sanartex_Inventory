<x-filament-panels::page>
    @php
        $totalSku = $products->count();
        $totalStokFisik = $products->sum('stok_aktual');
        $movedProductsCount = $products->where('keluar', '>', 0)->count();
        $fastMovingPercent = $totalSku > 0 ? round(($movedProductsCount / $totalSku) * 100) : 0;
        
        $deadStockCount = $products->where('keluar', 0)->where('stok_aktual', '>', 0)->count();
        $slowMovingPercent = $totalSku > 0 ? round(($deadStockCount / $totalSku) * 100) : 0;

        $totalAwal = $products->sum('awal');
        $totalMasuk = $products->sum('masuk');
        $totalKeluar = $products->sum('keluar');
        $totalAkhir = $products->sum('akhir');

        $kritisCount = $products->where('status_stok', 'KRITIS')->count();
        $accuracy = 100 - $slowMovingPercent;

        $activeProducts = $products->where('keluar', '>', 0);
        $deadStockProducts = $products->where('keluar', 0)->where('stok_aktual', '>', 0);
        $stokFisikProducts = $products->where('stok_aktual', '>', 0);
    @endphp

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-report, #printable-report * {
                visibility: visible;
            }
            #printable-report {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
            .fi-topbar, .fi-sidebar {
                display: none !important;
            }
        }
    </style>

    <div style="background-color: #f3f4f6; padding: 2rem 0; font-family: 'Inter', sans-serif;">
        
        {{-- A4 Paper Container --}}
        <div id="printable-report" style="background-color: white; max-width: 900px; margin: 0 auto; border-radius: 0.25rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); padding: 3rem; color: #1f2937;">
            
            {{-- Document Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                
                {{-- Left Side: Logo & Company --}}
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 3.5rem; height: 3.5rem; background-color: #111827; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; border-radius: 0.25rem;">
                        S
                    </div>
                    <div>
                        <div style="font-size: 1.25rem; font-weight: 800; letter-spacing: 0.05em; color: #111827;">SANARTEX</div>
                        <div style="font-size: 0.75rem; color: #4b5563;">Sistem Manajemen Inventori Terpadu</div>
                        <div style="font-size: 0.75rem; color: #6b7280;">Jl Cipaku, Bandung, Jawabarat</div>
                    </div>
                </div>

                {{-- Right Side: Report Info --}}
                <div style="text-align: right;">
                    <div style="font-size: 1.125rem; font-weight: bold; color: #111827; margin-bottom: 0.25rem;">LAPORAN INVENTORI</div>
                    <div style="font-size: 1rem; font-weight: bold; color: #374151; margin-bottom: 0.25rem;">{{ $periodeStr }}</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">No. Dokumen: INV/RPT/{{ date('Y/m/') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

            {{-- Separator Line --}}
            <div style="border-bottom: 2px solid #111827; margin-bottom: 2rem;"></div>

            {{-- Statistic Cards Row --}}
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem;">
                {{-- Card 1 --}}
                <div x-data x-on:click="$dispatch('open-modal', { id: 'sku-modal' })" style="flex: 1; min-width: 150px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.25rem; padding: 1rem; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='#f8fafc'">
                    <div style="font-size: 0.65rem; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Total SKU</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: #111827;">{{ number_format($totalSku) }}</div>
                </div>
                
                {{-- Card 2 --}}
                <div x-data x-on:click="$dispatch('open-modal', { id: 'stok-fisik-modal' })" style="flex: 1; min-width: 150px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.25rem; padding: 1rem; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='#f8fafc'">
                    <div style="font-size: 0.65rem; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Total Stok Fisik</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: #111827;">{{ number_format($totalStokFisik) }}</div>
                </div>

                {{-- Card 3 --}}
                <div x-data x-on:click="$dispatch('open-modal', { id: 'produk-aktif-modal' })" style="flex: 1; min-width: 150px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.25rem; padding: 1rem; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='#f8fafc'">
                    <div style="font-size: 0.65rem; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Produk Aktif</div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #059669;">{{ $fastMovingPercent }}%</div>
                        <x-heroicon-o-arrow-trending-up style="width: 1rem; height: 1rem; color: #059669;" />
                    </div>
                </div>

                {{-- Card 4 --}}
                <div x-data x-on:click="$dispatch('open-modal', { id: 'dead-stock-modal' })" style="flex: 1; min-width: 150px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.25rem; padding: 1rem; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='#f8fafc'">
                    <div style="font-size: 0.65rem; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Dead Stock</div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #dc2626;">{{ $slowMovingPercent }}%</div>
                        <x-heroicon-o-arrow-trending-down style="width: 1rem; height: 1rem; color: #dc2626;" />
                    </div>
                </div>
            </div>

            {{-- Table Section --}}
            <div style="margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                    <div style="width: 4px; height: 1.25rem; background-color: #111827; margin-right: 0.5rem;"></div>
                    <h3 style="font-size: 1rem; font-weight: bold; color: #111827; margin: 0;">Ringkasan Pergerakan Stok</h3>
                </div>

                <div class="overflow-x-auto">
                    <table style="width: 100%; min-width: 800px; border-collapse: collapse; text-align: left; font-size: 0.75rem;">
                    <thead>
                        <tr style="background-color: #dbeafe; color: #1e3a8a;">
                            <th style="padding: 0.75rem; font-weight: bold; border-bottom: 1px solid #bfdbfe;">KODE SKU</th>
                            <th style="padding: 0.75rem; font-weight: bold; border-bottom: 1px solid #bfdbfe;">NAMA PRODUK</th>
                            <th style="padding: 0.75rem; font-weight: bold; border-bottom: 1px solid #bfdbfe; text-align: right;">AWAL</th>
                            <th style="padding: 0.75rem; font-weight: bold; border-bottom: 1px solid #bfdbfe; text-align: right;">MASUK</th>
                            <th style="padding: 0.75rem; font-weight: bold; border-bottom: 1px solid #bfdbfe; text-align: right;">KELUAR</th>
                            <th style="padding: 0.75rem; font-weight: bold; border-bottom: 1px solid #bfdbfe; text-align: right;">AKHIR</th>
                            <th style="padding: 0.75rem; font-weight: bold; border-bottom: 1px solid #bfdbfe; text-align: center;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 0.75rem; color: #6b7280;">STX-KTN-{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td style="padding: 0.75rem; font-weight: 600; color: #111827;">{{ $product->nama }}</td>
                            <td style="padding: 0.75rem; text-align: right;">{{ number_format($product->awal) }}</td>
                            <td style="padding: 0.75rem; text-align: right;">{{ number_format($product->masuk) }}</td>
                            <td style="padding: 0.75rem; text-align: right;">{{ number_format($product->keluar) }}</td>
                            <td style="padding: 0.75rem; text-align: right; font-weight: bold; color: #111827;">{{ number_format($product->akhir) }}</td>
                            <td style="padding: 0.75rem; text-align: center;">
                                @php
                                    $bgColor = match($product->status_stok) {
                                        'NORMAL' => '#10b981',
                                        'KRITIS' => '#dc2626',
                                        'HABIS' => '#7f1d1d',
                                        'LEBIH' => '#d97706',
                                        default => '#111827'
                                    };
                                @endphp
                                <span style="background-color: {{ $bgColor }}; color: white; padding: 0.125rem 0.375rem; border-radius: 0.125rem; font-size: 0.6rem; font-weight: bold; letter-spacing: 0.05em;">{{ $product->status_stok }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #dbeafe; color: #111827; font-weight: bold;">
                            <td colspan="2" style="padding: 0.75rem; text-align: right;">TOTAL VOLUME PERGERAKAN</td>
                            <td style="padding: 0.75rem; text-align: right;">{{ number_format($totalAwal) }}</td>
                            <td style="padding: 0.75rem; text-align: right;">{{ number_format($totalMasuk) }}</td>
                            <td style="padding: 0.75rem; text-align: right;">{{ number_format($totalKeluar) }}</td>
                            <td style="padding: 0.75rem; text-align: right;">{{ number_format($totalAkhir) }}</td>
                            <td style="padding: 0.75rem;"></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>

            {{-- Analytics Box --}}
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; margin-bottom: 3rem;">
                <div style="flex: 1; border-right: 1px solid #cbd5e1; padding-right: 2rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <x-heroicon-o-share style="width: 1.25rem; height: 1.25rem; color: #111827; transform: rotate(90deg);" />
                        <h4 style="font-size: 0.875rem; font-weight: bold; color: #111827; margin: 0;">Analisis Pergerakan Stok</h4>
                    </div>
                    
                    <p style="font-size: 0.75rem; color: #4b5563; margin-bottom: 1rem; line-height: 1.5;">
                        Berdasarkan data transaksi dari {{ $periodeStr }}, sistem mengidentifikasi status stok sebagai berikut:
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; gap: 0.5rem; align-items: flex-start;">
                            <div style="width: 0.375rem; height: 0.375rem; background-color: #059669; border-radius: 0.125rem; margin-top: 0.3rem;"></div>
                            <div style="font-size: 0.75rem; color: #4b5563; line-height: 1.5;">
                                <strong style="color: #111827;">Prioritas Restock:</strong> Terdapat {{ $kritisCount }} SKU yang berada pada status KRITIS. Rekomendasi pemesanan segera.
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.5rem; align-items: flex-start;">
                            <div style="width: 0.375rem; height: 0.375rem; background-color: #d97706; border-radius: 0.125rem; margin-top: 0.3rem;"></div>
                            <div style="font-size: 0.75rem; color: #4b5563; line-height: 1.5;">
                                <strong style="color: #111827;">Dead Stock Alert:</strong> Terdapat {{ $deadStockCount }} SKU yang tidak mengalami pergerakan keluar pada periode ini. Pertimbangkan strategi diskon atau likuidasi.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="width: 8rem; display: flex; flex-direction: column; items-center; justify-content: center; padding-left: 1rem; text-align: center;">
                    <div style="width: 4rem; height: 4rem; border: 4px solid #059669; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: bold; color: #111827; margin-bottom: 0.5rem;">
                        {{ $fastMovingPercent }}%
                    </div>
                    <div style="font-size: 0.5rem; font-weight: bold; color: #4b5563; text-align: center; letter-spacing: 0.05em;">PRODUK AKTIF</div>
                </div>
            </div>

            {{-- Signatures --}}
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 2rem;">
                <div style="font-size: 0.65rem; color: #6b7280; line-height: 1.5;">
                    Laporan ini dihasilkan secara otomatis oleh <strong>Sanartex Inventory System</strong>.<br>
                    Waktu Pembuatan: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB
                </div>
                
                <div style="text-align: center;">
                    <div style="font-size: 0.75rem; font-weight: bold; color: #111827; margin-bottom: 4rem;">Mengetahui,</div>
                    <div style="border-top: 1px solid #111827; width: 10rem; margin: 0 auto; padding-top: 0.25rem;">
                        <div style="font-size: 0.75rem; font-weight: bold; color: #111827;">Pemilik</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modals for Stats Cards --}}
    <x-filament::modal id="sku-modal" width="2xl">
        <x-slot name="heading">Daftar Produk (Total SKU)</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-gray-900">
                    <tr>
                        <th class="px-4 py-2 rounded-tl-md">Kode SKU</th>
                        <th class="px-4 py-2">Nama Produk</th>
                        <th class="px-4 py-2 text-right rounded-tr-md">Stok Aktual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr class="border-b border-gray-100">
                        <td class="px-4 py-2">STX-KTN-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-2 font-medium">{{ $p->nama }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($p->stok_aktual) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::modal>

    <x-filament::modal id="stok-fisik-modal" width="2xl">
        <x-slot name="heading">Daftar Produk (Total Stok Fisik)</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-gray-900">
                    <tr>
                        <th class="px-4 py-2 rounded-tl-md">Kode SKU</th>
                        <th class="px-4 py-2">Nama Produk</th>
                        <th class="px-4 py-2 text-right rounded-tr-md">Stok Aktual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokFisikProducts as $p)
                    <tr class="border-b border-gray-100">
                        <td class="px-4 py-2">STX-KTN-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-2 font-medium">{{ $p->nama }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($p->stok_aktual) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada produk dengan stok fisik.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::modal>

    <x-filament::modal id="produk-aktif-modal" width="2xl">
        <x-slot name="heading">Daftar Produk Aktif</x-slot>
        <p class="text-sm text-gray-500 mb-4">Produk yang memiliki pergerakan keluar pada periode ini.</p>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-gray-900">
                    <tr>
                        <th class="px-4 py-2 rounded-tl-md">Kode SKU</th>
                        <th class="px-4 py-2">Nama Produk</th>
                        <th class="px-4 py-2 text-right rounded-tr-md">Total Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeProducts as $p)
                    <tr class="border-b border-gray-100">
                        <td class="px-4 py-2">STX-KTN-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-2 font-medium">{{ $p->nama }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($p->keluar) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada produk aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::modal>

    <x-filament::modal id="dead-stock-modal" width="2xl">
        <x-slot name="heading">Daftar Dead Stock</x-slot>
        <p class="text-sm text-gray-500 mb-4">Produk yang memiliki stok tetapi tidak ada pergerakan keluar pada periode ini.</p>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-gray-900">
                    <tr>
                        <th class="px-4 py-2 rounded-tl-md">Kode SKU</th>
                        <th class="px-4 py-2">Nama Produk</th>
                        <th class="px-4 py-2 text-right rounded-tr-md">Stok Tersisa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deadStockProducts as $p)
                    <tr class="border-b border-gray-100">
                        <td class="px-4 py-2">STX-KTN-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-2 font-medium">{{ $p->nama }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($p->stok_aktual) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada dead stock.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::modal>

</x-filament-panels::page>

