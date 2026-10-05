@extends('layouts.app')

@section('title', 'SOP & Panduan Sistem RBL')

@section('content')
<div class="space-y-6 w-full">

    <!-- Header -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
        <h1 class="text-xl font-bold text-navy-900 tracking-tight">Panduan Operasional & Konsep RBL (3 Zona)</h1>
        <p class="text-xs text-slate-500 mt-1">
            Pedoman Standar Operasional Prosedur (SOP) pengelolaan persediaan produk pakaian jadi berbasis Rule-Based Logic & Buffer Level di PT Sanartex.
        </p>
    </div>

    <!-- 1. Konsep RBL 3-Zona -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
        <h2 class="text-sm font-bold text-navy-900">1. Definisi 3 Zona Buffer RBL</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 space-y-1.5">
                <div class="font-bold text-rose-800">Zona Kritis (Merah)</div>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    Stok Aktual &le; Batas Minimum (Safety Stock).
                </p>
                <div class="text-[11px] text-rose-700 font-semibold">
                    Tindakan: Terbitkan purchase order (PO) restock ke konveksi segera.
                </div>
            </div>

            <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 space-y-1.5">
                <div class="font-bold text-emerald-800">Zona Normal (Hijau)</div>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    Batas Minimum &lt; Stok Aktual &le; Batas Maksimum.
                </p>
                <div class="text-[11px] text-emerald-700 font-semibold">
                    Tindakan: Buffer persediaan aman, monitor pesanan pelanggan rutin.
                </div>
            </div>

            <div class="p-3.5 rounded-lg bg-blue-50 border border-blue-200 space-y-1.5">
                <div class="font-bold text-blue-800">Zona Berlebih (Biru)</div>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    Stok Aktual &gt; Batas Maksimum.
                </p>
                <div class="text-[11px] text-blue-700 font-semibold">
                    Tindakan: Tahan pengadaan baru, dorong penjualan & promo outlet.
                </div>
            </div>
        </div>
    </div>

    <!-- 2. SOP Transaksi Tekstil -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
        <h2 class="text-sm font-bold text-slate-900">2. Prosedur Mutasi Barang</h2>
        
        <div class="space-y-3 text-xs">
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                <h4 class="font-bold text-slate-800 mb-1">SOP Penerimaan Pakaian Jadi (Stok Masuk)</h4>
                <ol class="list-decimal list-inside space-y-1 text-slate-600 text-[11px]">
                    <li>Periksa fisik produk apparel (hoodie, kaos, jaket, dll), nomor surat jalan vendor konveksi, dan nomor batch produksi.</li>
                    <li>Buka menu Stok Masuk, pilih produk atau scan barcode SKU hangtag.</li>
                    <li>Masukkan jumlah unit yang diterima dan catat nomor batch produksi.</li>
                    <li>Simpan transaksi; sistem otomatis memperbarui stok dan status RBL.</li>
                </ol>
            </div>

            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                <h4 class="font-bold text-slate-800 mb-1">SOP Pengiriman Pakaian Jadi (Stok Keluar)</h4>
                <ol class="list-decimal list-inside space-y-1 text-slate-600 text-[11px]">
                    <li>Pastikan terdapat dokumen Delivery Order (DO) / Surat Jalan atau bukti pesanan toko cabang / marketplace.</li>
                    <li>Buka menu Stok Keluar, scan SKU atau pilih produk yang dikirimkan.</li>
                    <li>Sistem otomatis memvalidasi stok fisik untuk mencegah nilai minus.</li>
                    <li>Simpan transaksi dan serahkan barang ke kurir / tim logistik pengiriman.</li>
                </ol>
            </div>
        </div>
    </div>

</div>
@endsection
