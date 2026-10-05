<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockInTransaction;
use App\Models\StockOutTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with Finished Goods (Pakaian Jadi / Garmen).
     */
    public function run(): void
    {
        // 1. Seed Demo Users for Every Role
        $users = [
            [
                'name' => 'Budi Santoso (Superadmin)',
                'email' => 'superadmin@sanartex.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'phone' => '0812-3456-7890',
            ],
            [
                'name' => 'Hendra Wijaya (Kepala Gudang)',
                'email' => 'kepala.gudang@sanartex.com',
                'password' => Hash::make('password'),
                'role' => 'kepala_gudang',
                'phone' => '0813-9876-5432',
            ],
            [
                'name' => 'Siti Nurhaliza (Admin Gudang)',
                'email' => 'admin.gudang@sanartex.com',
                'password' => Hash::make('password'),
                'role' => 'admin_gudang',
                'phone' => '0811-2233-4455',
            ],
            [
                'name' => 'Rian Pratama (Purchasing)',
                'email' => 'purchasing@sanartex.com',
                'password' => Hash::make('password'),
                'role' => 'purchasing',
                'phone' => '0815-6677-8899',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['email' => $userData['email']], $userData);
        }

        $superadmin = User::where('email', 'superadmin@sanartex.com')->first();
        $adminGudang = User::where('email', 'admin.gudang@sanartex.com')->first();

        // 2. Clear previous products to cleanly replace with Finished Goods
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        StockInTransaction::truncate();
        StockOutTransaction::truncate();
        Product::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 3. Seed Realistic Finished Apparel Products (Hoodie, Kaos, Kemeja, Jaket, Celana, Polo)
        $products = [
            // KRITIS (Red Zone)
            [
                'kode_produk' => 'HD-OVS-BLK-01',
                'nama' => 'Hoodie Oversize Heavyweight 330 GSM (Black Onyx)',
                'kategori' => 'Hoodie & Sweater',
                'satuan' => 'Pcs',
                'stok_aktual' => 15,
                'batas_minimum' => 30,
                'reorder_point' => 60,
                'batas_maksimum' => 200,
                'lead_time_days' => 10,
                'lokasi_rak' => 'Rak A-01 (Hoodie)',
                'supplier_utama' => 'PT Garmen Konveksi Nusantara',
                'harga_beli_per_satuan' => 145000,
                'spesifikasi' => 'Bahan Cotton Fleece 330 GSM Tebal, Sablon Plastisol HD, Tali Katun, Size L/XL',
            ],
            [
                'kode_produk' => 'TS-CMB-WHT-02',
                'nama' => 'Kaos Polos Combed 24s Cotton (White Solid)',
                'kategori' => 'Kaos & T-Shirt',
                'satuan' => 'Pcs',
                'stok_aktual' => 25,
                'batas_minimum' => 50,
                'reorder_point' => 100,
                'batas_maksimum' => 400,
                'lead_time_days' => 7,
                'lokasi_rak' => 'Rak B-01 (Kaos)',
                'supplier_utama' => 'CV Kaos Prima Bandung',
                'harga_beli_per_satuan' => 42000,
                'spesifikasi' => '100% Ring Spun Cotton Combed 24s Soft Touch, Jahitan Rantai Distro, Size M/L/XL',
            ],
            [
                'kode_produk' => 'KM-FLN-NAV-03',
                'nama' => 'Kemeja Flannel Tartan Casual (Navy Grey)',
                'kategori' => 'Kemeja & Flannel',
                'satuan' => 'Pcs',
                'stok_aktual' => 12,
                'batas_minimum' => 25,
                'reorder_point' => 50,
                'batas_maksimum' => 150,
                'lead_time_days' => 12,
                'lokasi_rak' => 'Rak C-01 (Kemeja)',
                'supplier_utama' => 'PT Busana Flannel Indah',
                'harga_beli_per_satuan' => 110000,
                'spesifikasi' => 'Wool Flannel Brush Lembut & Tebal, Kancing Kayu Eksklusif, Saku Ganda, Size M/L/XL',
            ],

            // NORMAL (Green Zone)
            [
                'kode_produk' => 'JK-BMB-OLV-04',
                'nama' => 'Jaket Bomber Flight Waterproof (Olive Green)',
                'kategori' => 'Jaket & Outerwear',
                'satuan' => 'Pcs',
                'stok_aktual' => 48,
                'batas_minimum' => 20,
                'reorder_point' => 45,
                'batas_maksimum' => 120,
                'lead_time_days' => 14,
                'lokasi_rak' => 'Rak D-01 (Jaket)',
                'supplier_utama' => 'PT Garmen Outerwear Prima',
                'harga_beli_per_satuan' => 185000,
                'spesifikasi' => 'Bahan Taslan Milky Waterproof, Furing Quilting Dacron 4oz, Saku Lengan, Size L/XL',
            ],
            [
                'kode_produk' => 'PL-PKT-GRY-05',
                'nama' => 'Polo Shirt Pique Classic Fit (Charcoal Grey)',
                'kategori' => 'Polo Shirt',
                'satuan' => 'Pcs',
                'stok_aktual' => 65,
                'batas_minimum' => 30,
                'reorder_point' => 60,
                'batas_maksimum' => 180,
                'lead_time_days' => 8,
                'lokasi_rak' => 'Rak E-01 (Polo)',
                'supplier_utama' => 'CV Polo Mandiri Garmen',
                'harga_beli_per_satuan' => 75000,
                'spesifikasi' => 'CVC Lacoste Pique 24s Adem, Kerah Rajut Anti-Kerut, Kancing Mutiara, Size M/L/XL',
            ],
            [
                'kode_produk' => 'CL-CHN-KHK-06',
                'nama' => 'Celana Chino Slim Fit Stretch (Khaki Tan)',
                'kategori' => 'Celana & Chino',
                'satuan' => 'Pcs',
                'stok_aktual' => 55,
                'batas_minimum' => 25,
                'reorder_point' => 50,
                'batas_maksimum' => 160,
                'lead_time_days' => 10,
                'lokasi_rak' => 'Rak F-01 (Celana)',
                'supplier_utama' => 'PT Celana Karya Mandiri',
                'harga_beli_per_satuan' => 125000,
                'spesifikasi' => 'Katun Twill Stretch 20s (98% Cotton 2% Spandex), Resleting YKK, Size 29-34',
            ],
            [
                'kode_produk' => 'HD-ZPR-ASH-07',
                'nama' => 'Hoodie Zipper Basic Fleece (Ash Grey)',
                'kategori' => 'Hoodie & Sweater',
                'satuan' => 'Pcs',
                'stok_aktual' => 70,
                'batas_minimum' => 25,
                'reorder_point' => 50,
                'batas_maksimum' => 180,
                'lead_time_days' => 9,
                'lokasi_rak' => 'Rak A-02 (Hoodie)',
                'supplier_utama' => 'PT Garmen Konveksi Nusantara',
                'harga_beli_per_satuan' => 135000,
                'spesifikasi' => 'Cotton Fleece 280 GSM, Resleting Metal YKK Anti-Karat, Saku Kanguru, Size L/XL',
            ],
            [
                'kode_produk' => 'TS-OVS-SGE-08',
                'nama' => 'Kaos Oversize Heavyweight 20s (Sage Green)',
                'kategori' => 'Kaos & T-Shirt',
                'satuan' => 'Pcs',
                'stok_aktual' => 85,
                'batas_minimum' => 35,
                'reorder_point' => 70,
                'batas_maksimum' => 250,
                'lead_time_days' => 6,
                'lokasi_rak' => 'Rak B-02 (Kaos)',
                'supplier_utama' => 'CV Kaos Prima Bandung',
                'harga_beli_per_satuan' => 52000,
                'spesifikasi' => 'Cotton Combed 20s 220 GSM Drop Shoulder Cut, Rib Leher 3cm Tebal, Size M/L/XL',
            ],
            [
                'kode_produk' => 'JK-WND-BLK-09',
                'nama' => 'Jaket Windbreaker Sport Active (Jet Black)',
                'kategori' => 'Jaket & Outerwear',
                'satuan' => 'Pcs',
                'stok_aktual' => 60,
                'batas_minimum' => 20,
                'reorder_point' => 45,
                'batas_maksimum' => 150,
                'lead_time_days' => 11,
                'lokasi_rak' => 'Rak D-02 (Jaket)',
                'supplier_utama' => 'PT Garmen Outerwear Prima',
                'harga_beli_per_satuan' => 140000,
                'spesifikasi' => 'Micro Despo Windproof, Furing Jaring Breathable, Hoodie Serut, Size L/XL',
            ],
            [
                'kode_produk' => 'SW-CRW-MST-10',
                'nama' => 'Sweater Crewneck Minimalist (Mustard Yellow)',
                'kategori' => 'Hoodie & Sweater',
                'satuan' => 'Pcs',
                'stok_aktual' => 50,
                'batas_minimum' => 20,
                'reorder_point' => 40,
                'batas_maksimum' => 140,
                'lead_time_days' => 8,
                'lokasi_rak' => 'Rak A-03 (Sweater)',
                'supplier_utama' => 'PT Garmen Konveksi Nusantara',
                'harga_beli_per_satuan' => 115000,
                'spesifikasi' => 'Cotton Baby Terry Premium, Lembut Tanpa Gerah, Bordir Dada Kiri, Size M/L/XL',
            ],

            // BERLEBIH (Blue Zone)
            [
                'kode_produk' => 'CL-CRG-ARM-11',
                'nama' => 'Celana Cargo Tactical 6-Pocket (Army Green)',
                'kategori' => 'Celana & Chino',
                'satuan' => 'Pcs',
                'stok_aktual' => 240,
                'batas_minimum' => 30,
                'reorder_point' => 60,
                'batas_maksimum' => 180,
                'lead_time_days' => 14,
                'lokasi_rak' => 'Rak F-02 (Cargo)',
                'supplier_utama' => 'PT Celana Karya Mandiri',
                'harga_beli_per_satuan' => 155000,
                'spesifikasi' => 'Ripstop Tornado Tahan Sobek, 6 Kantong Fungsional Velcro, Karet Pinggang, Size 30-36',
            ],
            [
                'kode_produk' => 'KM-OXF-BLU-12',
                'nama' => 'Kemeja Oxford Long Sleeve Formal (Sky Blue)',
                'kategori' => 'Kemeja & Flannel',
                'satuan' => 'Pcs',
                'stok_aktual' => 220,
                'batas_minimum' => 25,
                'reorder_point' => 50,
                'batas_maksimum' => 160,
                'lead_time_days' => 10,
                'lokasi_rak' => 'Rak C-02 (Kemeja)',
                'supplier_utama' => 'PT Busana Flannel Indah',
                'harga_beli_per_satuan' => 105000,
                'spesifikasi' => 'Oxford Cotton Pinpoint 40s Adem, Kerah Button-Down, Slim Fit Ergonomis, Size M/L/XL',
            ],
        ];

        foreach ($products as $p) {
            $createdProduct = Product::create($p);

            // 4. Seed Inbound Transactions (Penerimaan Barang Jadi dari Pabrik/Vendor)
            StockInTransaction::create([
                'product_id' => $createdProduct->id,
                'user_id' => $adminGudang->id ?? $superadmin->id,
                'tanggal' => now()->subDays(rand(5, 20))->format('Y-m-d'),
                'jumlah' => rand(20, 50),
                'no_surat_jalan' => 'SJ-GRM-' . rand(1000, 9999),
                'supplier' => $p['supplier_utama'],
                'no_batch_lot' => 'BATCH-QC-' . rand(100, 999),
                'keterangan' => 'Penerimaan stok pakaian jadi dari vendor konveksi, lolos inspeksi QC 100%',
            ]);

            // 5. Seed Outbound Transactions (Pengeluaran Barang Jadi untuk Pengiriman Toko / Marketplace)
            StockOutTransaction::create([
                'product_id' => $createdProduct->id,
                'user_id' => $adminGudang->id ?? $superadmin->id,
                'tanggal' => now()->subDays(rand(1, 4))->format('Y-m-d'),
                'jumlah' => rand(5, 15),
                'no_spk_tujuan' => 'DO-DIST-' . rand(1000, 9999),
                'penerima_divisi' => 'Distribusi Outlet & Marketplace Order',
                'keterangan' => 'Pemenuhan pesanan batch marketplace dan distribusi toko ritel',
            ]);
        }
    }
}
