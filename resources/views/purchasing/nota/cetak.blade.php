<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian {{ $transaction->no_nota }} - PT SANARTEX INDONESIA</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            500: '#ea580c',
                            600: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .sheet {
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .sheet {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                border-radius: 0 !important;
            }
            .avoid-break {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="py-6 px-4 sm:px-6 antialiased">

    @php
        // Terbilang Rupiah Helper Function
        if (!function_exists('terbilangAngkaSanartex')) {
            function terbilangAngkaSanartex($nilai) {
                $nilai = abs((float)$nilai);
                $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
                $temp = "";
                if ($nilai < 12) {
                    $temp = " ". $huruf[(int)$nilai];
                } else if ($nilai < 20) {
                    $temp = terbilangAngkaSanartex($nilai - 10). " Belas";
                } else if ($nilai < 100) {
                    $temp = terbilangAngkaSanartex(floor($nilai/10))." Puluh". terbilangAngkaSanartex($nilai % 10);
                } else if ($nilai < 200) {
                    $temp = " Seratus" . terbilangAngkaSanartex($nilai - 100);
                } else if ($nilai < 1000) {
                    $temp = terbilangAngkaSanartex(floor($nilai/100))." Ratus". terbilangAngkaSanartex($nilai % 100);
                } else if ($nilai < 2000) {
                    $temp = " Seribu" . terbilangAngkaSanartex($nilai - 1000);
                } else if ($nilai < 1000000) {
                    $temp = terbilangAngkaSanartex(floor($nilai/1000))." Ribu". terbilangAngkaSanartex($nilai % 1000);
                } else if ($nilai < 1000000000) {
                    $temp = terbilangAngkaSanartex(floor($nilai/1000000))." Juta". terbilangAngkaSanartex($nilai % 1000000);
                } else if ($nilai < 1000000000000) {
                    $temp = terbilangAngkaSanartex(floor($nilai/1000000000))." Miliar". terbilangAngkaSanartex(fmod($nilai, 1000000000));
                } else if ($nilai < 1000000000000000) {
                    $temp = terbilangAngkaSanartex(floor($nilai/1000000000000))." Triliun". terbilangAngkaSanartex(fmod($nilai, 1000000000000));
                }     
                return $temp;
            }

            function formatTerbilangSanartex($nilai) {
                if($nilai < 0) {
                    $hasil = "Minus ". trim(terbilangAngkaSanartex($nilai));
                } else if($nilai == 0) {
                    $hasil = "Nol";
                } else {
                    $hasil = trim(terbilangAngkaSanartex($nilai));
                }     
                return $hasil . " Rupiah";
            }
        }

        $rawSubtotal = ($transaction->jumlah * ($transaction->harga_beli_satuan ?: ($transaction->product->harga_beli_per_satuan ?? 0))) - ($transaction->diskon ?? 0);
        $ppnNominal = $rawSubtotal * (($transaction->ppn_persen ?? 0) / 100);
        $grandTotal = $transaction->total_harga ?: ($rawSubtotal + $ppnNominal);
        $terbilangText = formatTerbilangSanartex($grandTotal);
    @endphp

    <!-- ACTION CONTROL BAR (Hidden on Print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('purchasing.nota.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs transition inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Daftar Nota</span>
            </a>
            <span class="text-xs text-slate-300">|</span>
            <span class="text-xs text-slate-500 font-mono">No. Nota: <strong class="text-slate-800">{{ $transaction->no_nota }}</strong></span>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold text-xs rounded-lg transition-colors flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- MAIN INVOICE SHEET -->
    <div class="sheet max-w-4xl mx-auto rounded-xl border border-slate-200/80 p-8 sm:p-12 text-slate-800">
        
        <!-- HEADER SECTION: Official Company Info & Invoice Title -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pb-6 border-b border-slate-200">
            
            <!-- Left: Official Logo & Company Info -->
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/sanartex_horizontal.png') }}" alt="PT SANARTEX INDONESIA" class="h-9 w-auto object-contain">
                </div>
                <div class="text-xs text-slate-500 leading-relaxed pt-1">
                    <p class="font-medium text-slate-700">Divisi Pengadaan & Purchasing Pakaian Jadi (Apparel)</p>
                    <p>Kawasan Industri Tekstil & Garmen, Jl. Rancaekek No. 88, Bandung 40394</p>
                    <p class="text-[11px] text-slate-400">Telp: (022) 8765-4321 &bull; Email: purchasing@sanartex.com &bull; NPWP: 01.345.678.9-429.000</p>
                </div>
            </div>

            <!-- Right: Document Meta & Status -->
            <div class="text-left sm:text-right flex-shrink-0 space-y-1.5">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    BUKTI NOTA PEMBELIAN
                </div>
                <div class="text-lg font-extrabold font-mono text-slate-900 tracking-tight">
                    {{ $transaction->no_nota }}
                </div>
                <div class="text-xs text-slate-500">
                    Tanggal Terbit: <strong class="text-slate-700 font-medium">{{ \Carbon\Carbon::parse($transaction->tanggal)->translatedFormat('d F Y') }}</strong>
                </div>
                <div class="pt-1">
                    @if($transaction->status_pembayaran === 'LUNAS')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            LUNAS
                        </span>
                    @elseif($transaction->status_pembayaran === 'TEMPO')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            TEMPO (HUTANG)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            DP / SEBAGIAN
                        </span>
                    @endif
                </div>
            </div>

        </div>

        <!-- TWO-COLUMN METADATA GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-200 text-xs">
            
            <!-- Vendor / Suplier -->
            <div class="space-y-1.5">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Diterbitkan Untuk / Vendor:</span>
                <div class="text-sm font-bold text-slate-900">
                    {{ $transaction->supplier ?: ($transaction->product->supplier_utama ?? 'PT Busana Flannel Indah') }}
                </div>
                <div class="text-slate-600 leading-relaxed text-xs">
                    Mitra Suplai Produk Garmen & Apparel
                </div>
                <div class="pt-1 text-slate-500">
                    Metode Pembayaran: <strong class="text-slate-800 font-medium">{{ $transaction->metode_pembayaran ?: 'Transfer Bank Mandiri' }}</strong>
                </div>
                @if($transaction->status_pembayaran === 'TEMPO' && $transaction->jatuh_tempo)
                    <div class="text-rose-600 font-semibold pt-0.5">
                        Jatuh Tempo: {{ \Carbon\Carbon::parse($transaction->jatuh_tempo)->translatedFormat('d F Y') }}
                    </div>
                @endif
            </div>

            <!-- Penerimaan & Gudang Info -->
            <div class="space-y-1.5 sm:border-l sm:border-slate-100 sm:pl-6">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Informasi Penerimaan Dokumen:</span>
                <div class="space-y-1 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">No. Surat Jalan Vendor:</span>
                        <span class="font-mono font-medium text-slate-900">{{ $transaction->no_surat_jalan ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Nomor Batch / Lot QC:</span>
                        <span class="font-mono font-medium text-slate-900">{{ $transaction->no_batch_lot ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Petugas Penerima:</span>
                        <span class="font-medium text-slate-900">{{ $transaction->user->name ?? 'Siti Nurhaliza (Admin Gudang)' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Lokasi Simpan Rak:</span>
                        <span class="font-medium text-slate-900">{{ $transaction->product->lokasi_rak ?? 'Rak C-02 (Kemeja)' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ITEMS BREAKDOWN TABLE -->
        <div class="py-6 border-b border-slate-200">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="pb-3 text-center w-10">No</th>
                        <th class="pb-3">Kode SKU & Deskripsi Produk</th>
                        <th class="pb-3 text-center">Kategori</th>
                        <th class="pb-3 text-right">Kuantitas</th>
                        <th class="pb-3 text-right">Harga Satuan</th>
                        <th class="pb-3 text-right">Diskon</th>
                        <th class="pb-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr>
                        <td class="py-4 text-center font-semibold text-slate-400">1</td>
                        <td class="py-4 pr-3">
                            <div class="font-mono text-slate-500 text-[11px]">[{{ $transaction->product->kode_produk ?? '-' }}]</div>
                            <div class="font-bold text-slate-900 text-xs sm:text-sm mt-0.5">
                                {{ $transaction->product->nama ?? 'Produk Apparel' }}
                            </div>
                            @if($transaction->product->spesifikasi)
                                <div class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                    {{ $transaction->product->spesifikasi }}
                                </div>
                            @endif
                        </td>
                        <td class="py-4 text-center text-slate-600">
                            {{ $transaction->product->kategori ?? '-' }}
                        </td>
                        <td class="py-4 text-right font-bold text-slate-900 whitespace-nowrap">
                            {{ number_format($transaction->jumlah) }} {{ $transaction->product->satuan ?? 'Pcs' }}
                        </td>
                        <td class="py-4 text-right whitespace-nowrap">
                            Rp {{ number_format($transaction->harga_beli_satuan ?: ($transaction->product->harga_beli_per_satuan ?? 0), 0, ',', '.') }}
                        </td>
                        <td class="py-4 text-right text-slate-500 whitespace-nowrap">
                            @if(($transaction->diskon ?? 0) > 0)
                                <span class="text-rose-600 font-medium">-Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</span>
                            @else
                                Rp 0
                            @endif
                        </td>
                        <td class="py-4 text-right font-bold text-slate-900 whitespace-nowrap">
                            Rp {{ number_format($rawSubtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SUMMARY & TERBILANG -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 py-6 border-b border-slate-200 items-start text-xs">
            
            <!-- Left: Terbilang & Notes (7 Cols) -->
            <div class="sm:col-span-7 space-y-3">
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Terbilang:</div>
                    <div class="font-medium italic text-slate-800 text-xs leading-relaxed">
                        "{{ $terbilangText }}"
                    </div>
                </div>

                <div class="space-y-1 text-slate-500 leading-relaxed text-[11px]">
                    <span class="font-semibold text-slate-700 block">Catatan & Ketentuan Pembelian:</span>
                    <p>{{ $transaction->keterangan ?: 'Penerimaan stok pakaian jadi dari vendor konveksi, lolos inspeksi QC 100%.' }}</p>
                    <p class="text-slate-400 text-[10px] pt-1">Dokumen ini sah dan diakui secara digital oleh sistem inventori PT SANARTEX INDONESIA.</p>
                </div>
            </div>

            <!-- Right: Subtotal & Grand Total (5 Cols) -->
            <div class="sm:col-span-5 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal Pembelian:</span>
                    <span class="font-medium text-slate-900">Rp {{ number_format($rawSubtotal + ($transaction->diskon ?? 0), 0, ',', '.') }}</span>
                </div>

                @if(($transaction->diskon ?? 0) > 0)
                    <div class="flex justify-between text-rose-600">
                        <span>Potongan Diskon:</span>
                        <span class="font-medium">-Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-slate-600">
                    <span>PPN ({{ $transaction->ppn_persen ?? 0 }}%):</span>
                    <span class="font-medium text-slate-900">Rp {{ number_format($ppnNominal, 0, ',', '.') }}</span>
                </div>

                <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">TOTAL DIBAYAR:</span>
                    <span class="text-lg font-black text-orange-600 font-mono">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>

        </div>

        <!-- 4 SIGNATURE BLOCKS -->
        <div class="pt-8 avoid-break">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center text-xs">
                
                <!-- Sign 1: Purchasing -->
                <div class="space-y-14">
                    <span class="text-slate-500 font-medium block">Dibuat Oleh (Purchasing),</span>
                    <div>
                        <div class="font-bold text-slate-900 underline decoration-1 underline-offset-2">
                            {{ $transaction->user->name ?? 'Rian Pratama' }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Staff Procurement</div>
                    </div>
                </div>

                <!-- Sign 2: Gudang QC -->
                <div class="space-y-14">
                    <span class="text-slate-500 font-medium block">Diperiksa (Checker QC),</span>
                    <div>
                        <div class="font-bold text-slate-900 underline decoration-1 underline-offset-2">
                            Siti Nurhaliza
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Admin Gudang Masuk</div>
                    </div>
                </div>

                <!-- Sign 3: Vendor -->
                <div class="space-y-14">
                    <span class="text-slate-500 font-medium block">Diserahkan Oleh (Vendor),</span>
                    <div>
                        <div class="font-bold text-slate-900 underline decoration-1 underline-offset-2">
                            {{ $transaction->supplier ?: 'Mitra Vendor' }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Pabrik / Vendor Konveksi</div>
                    </div>
                </div>

                <!-- Sign 4: Kepala Gudang -->
                <div class="space-y-14">
                    <span class="text-slate-500 font-medium block">Disetujui (Kepala Gudang),</span>
                    <div>
                        <div class="font-bold text-slate-900 underline decoration-1 underline-offset-2">
                            Hendra Wijaya
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Kepala Gudang & Logistik</div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
