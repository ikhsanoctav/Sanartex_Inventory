<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekap Stok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: #1f2937;
        }
    </style>
</head>
<body>
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

        // The variables are passed as $startDate and $endDate (Carbon objects)
        $periodeStr = ($startDate ?? \Carbon\Carbon::now()->startOfMonth())->translatedFormat('d M Y') . ' - ' . ($endDate ?? \Carbon\Carbon::now()->endOfMonth())->translatedFormat('d M Y');
        
        $kritisCount = $products->where('status_stok', 'KRITIS')->count();
        $accuracy = 100 - $slowMovingPercent;
    @endphp

    <div style="max-width: 900px; margin: 0 auto; padding: 20px;">
        
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 60%;">
                    <div style="display: inline-block; width: 50px; height: 50px; background-color: #111827; color: white; text-align: center; line-height: 50px; font-size: 24px; font-weight: bold; border-radius: 4px; vertical-align: top;">
                        S
                    </div>
                    <div style="display: inline-block; margin-left: 10px; vertical-align: top;">
                        <div style="font-size: 20px; font-weight: 800; letter-spacing: 1px; color: #111827;">SANARTEX</div>
                        <div style="font-size: 12px; color: #4b5563;">Sistem Manajemen Inventori Terpadu</div>
                        <div class="company-address">Jl Cipaku, Bandung, Jawabarat</div>
                    </div>
                </td>
                <td style="text-align: right; vertical-align: top; width: 40%;">
                    <div style="font-size: 18px; font-weight: bold; color: #111827; margin-bottom: 4px;">LAPORAN INVENTORI</div>
                    <div style="font-size: 16px; font-weight: bold; color: #374151; margin-bottom: 4px;">{{ $periodeStr }}</div>
                    <div style="font-size: 12px; color: #6b7280;">No. Dokumen: INV/RPT/{{ date('Y/m/') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
        </table>

        <div style="border-bottom: 2px solid #111827; margin-bottom: 20px;"></div>

        <table style="width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 15px 0;">
            <tr>
                <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; width: 25%;">
                    <div style="font-size: 10px; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-bottom: 8px;">Total SKU</div>
                    <div style="font-size: 24px; font-weight: bold; color: #111827;">{{ number_format($totalSku) }}</div>
                </td>
                <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; width: 25%;">
                    <div style="font-size: 10px; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-bottom: 8px;">Total Stok Fisik</div>
                    <div style="font-size: 24px; font-weight: bold; color: #111827;">{{ number_format($totalStokFisik) }}</div>
                </td>
                <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; width: 25%;">
                    <div style="font-size: 10px; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-bottom: 8px;">Produk Aktif</div>
                    <div style="font-size: 24px; font-weight: bold; color: #059669;">{{ $fastMovingPercent }}% &#8599;</div>
                </td>
                <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; width: 25%;">
                    <div style="font-size: 10px; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-bottom: 8px;">Dead Stock</div>
                    <div style="font-size: 24px; font-weight: bold; color: #dc2626;">{{ $slowMovingPercent }}% &#8600;</div>
                </td>
            </tr>
        </table>

        <div style="margin-bottom: 20px;">
            <div style="margin-bottom: 15px;">
                <span style="display: inline-block; width: 4px; height: 16px; background-color: #111827; margin-right: 8px; vertical-align: middle;"></span>
                <h3 style="display: inline-block; font-size: 16px; font-weight: bold; color: #111827; margin: 0; vertical-align: middle;">Ringkasan Pergerakan Stok</h3>
            </div>

            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 12px;">
                <thead>
                    <tr style="background-color: #dbeafe; color: #1e3a8a;">
                        <th style="padding: 10px; border-bottom: 1px solid #bfdbfe;">KODE SKU</th>
                        <th style="padding: 10px; border-bottom: 1px solid #bfdbfe;">NAMA PRODUK</th>
                        <th style="padding: 10px; border-bottom: 1px solid #bfdbfe; text-align: right;">AWAL</th>
                        <th style="padding: 10px; border-bottom: 1px solid #bfdbfe; text-align: right;">MASUK</th>
                        <th style="padding: 10px; border-bottom: 1px solid #bfdbfe; text-align: right;">KELUAR</th>
                        <th style="padding: 10px; border-bottom: 1px solid #bfdbfe; text-align: right;">AKHIR</th>
                        <th style="padding: 10px; border-bottom: 1px solid #bfdbfe; text-align: center;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td class="text-secondary" style="padding: 10px; font-size: 8px;">STX-KTN-{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="font-bold" style="padding: 10px; color: #111827;">{{ $product->nama }}</td>
                        <td class="text-right" style="padding: 10px;">{{ number_format($product->awal) }}</td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($product->masuk) }}</td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($product->keluar) }}</td>
                        <td style="padding: 10px; text-align: right; font-weight: bold;">{{ number_format($product->akhir) }}</td>
                        <td style="padding: 10px; text-align: center;">
                            @php
                                $bgColor = match($product->status_stok) {
                                    'NORMAL' => '#10b981',
                                    'KRITIS' => '#dc2626',
                                    'HABIS' => '#7f1d1d',
                                    'LEBIH' => '#d97706',
                                    default => '#111827'
                                };
                            @endphp
                            <span style="background-color: {{ $bgColor }}; color: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold;">{{ $product->status_stok }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #dbeafe; color: #111827; font-weight: bold;">
                        <td colspan="2" class="text-right" style="padding: 10px;">TOTAL VOLUME PERGERAKAN</td>
                        <td class="text-right" style="padding: 10px;">{{ number_format($totalAwal) }}</td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($totalMasuk) }}</td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($totalKeluar) }}</td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($totalAkhir) }}</td>
                        <td style="padding: 10px;"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <table style="width: 100%; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 40px; border-collapse: collapse;">
            <tr>
                <td style="padding: 20px; border-right: 1px solid #cbd5e1; width: 70%;">
                    <div style="margin-bottom: 15px;">
                        <h4 style="font-size: 14px; font-weight: bold; color: #111827; margin: 0;">Analisis Pergerakan Stok</h4>
                    </div>
                    <p style="font-size: 12px; color: #4b5563; margin-bottom: 15px; line-height: 1.5;">
                        Berdasarkan data transaksi dari {{ $periodeStr }}, sistem mengidentifikasi status stok sebagai berikut:
                    </p>
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            <td style="vertical-align: top; width: 15px; padding-top: 5px;">
                                <div style="width: 6px; height: 6px; background-color: #059669; border-radius: 2px;"></div>
                            </td>
                            <td style="font-size: 12px; color: #4b5563; line-height: 1.5; padding-bottom: 10px;">
                                <strong style="color: #111827;">Prioritas Restock:</strong> Terdapat {{ $kritisCount }} SKU yang berada pada status KRITIS. Rekomendasi pemesanan segera.
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; width: 15px; padding-top: 5px;">
                                <div style="width: 6px; height: 6px; background-color: #d97706; border-radius: 2px;"></div>
                            </td>
                            <td style="font-size: 12px; color: #4b5563; line-height: 1.5;">
                                <strong style="color: #111827;">Dead Stock Alert:</strong> Terdapat {{ $deadStockCount }} SKU yang tidak mengalami pergerakan keluar pada periode ini. Pertimbangkan strategi diskon atau likuidasi.
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="padding: 20px; text-align: center; width: 30%; vertical-align: middle;">
                    <center>
                        <div style="width: 60px; height: 60px; border: 4px solid #059669; border-radius: 4px; line-height: 60px; font-size: 20px; font-weight: bold; color: #111827; margin-bottom: 10px;">
                            {{ $fastMovingPercent }}%
                        </div>
                    </center>
                    <div style="font-size: 10px; font-weight: bold; color: #4b5563; text-align: center; letter-spacing: 1px;">PRODUK AKTIF</div>
                </td>
            </tr>
        </table>

        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: bottom; font-size: 10px; color: #6b7280; line-height: 1.5; width: 50%;">
                    Laporan ini dihasilkan secara otomatis oleh <strong>Sanartex Inventory System</strong>.<br>
                    Waktu Pembuatan: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB
                </td>
                <td style="vertical-align: bottom; text-align: right; width: 50%;">
                    <table style="display: inline-table; text-align: center;">
                        <tr><td style="font-size: 12px; font-weight: bold; color: #111827; padding-bottom: 50px;">Mengetahui,</td></tr>
                        <tr><td style="border-top: 1px solid #111827; padding-top: 5px; font-size: 12px; font-weight: bold; color: #111827; width: 150px;">Pemilik</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
