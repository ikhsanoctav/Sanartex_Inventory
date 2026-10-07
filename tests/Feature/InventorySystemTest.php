<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockInTransaction;
use App\Models\StockOutTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('PT SANARTEX');
        $response->assertSee('Masuk ke Dashboard');
    }

    public function test_dashboard_accessible_for_all_roles(): void
    {
        $roles = ['superadmin', 'manajemen', 'kepala_gudang', 'admin_gudang', 'purchasing'];

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role]);

            $product = Product::create([
                'kode_produk' => 'AP-HOD-' . rand(100, 999),
                'nama' => 'Hoodie Fleece Heavyweight Hitam',
                'kategori' => 'Hoodie & Sweater',
                'satuan' => 'Pcs',
                'stok_aktual' => 20,
                'batas_minimum' => 10,
                'batas_maksimum' => 100,
                'lead_time_days' => 7,
            ]);

            StockInTransaction::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'tanggal' => now()->format('Y-m-d'),
                'jumlah' => 10,
                'no_surat_jalan' => 'SJ-TEST-123',
                'supplier' => 'CV Konveksi Bandung Prima',
            ]);

            StockOutTransaction::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'tanggal' => now()->format('Y-m-d'),
                'jumlah' => 5,
                'no_spk_tujuan' => 'OUT-TEST-123',
            ]);

            $response = $this->actingAs($user)->get('/dashboard');
            $response->assertStatus(200);
            $response->assertSee('SANARTEX');
        }
    }

    public function test_products_catalog_accessible(): void
    {
        $user = User::factory()->create(['role' => 'admin_gudang']);
        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Master Data Pakaian Jadi (Apparel)');
    }

    public function test_inbound_outbound_pages_accessible(): void
    {
        $user = User::factory()->create(['role' => 'admin_gudang']);
        
        $inbound = $this->actingAs($user)->get('/transaksi/masuk');
        $inbound->assertStatus(200);
        $inbound->assertSee('Stok Masuk');

        $outbound = $this->actingAs($user)->get('/transaksi/keluar');
        $outbound->assertStatus(200);
        $outbound->assertSee('Stok Keluar');
    }

    public function test_rbl_analysis_page_accessible(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $response = $this->actingAs($user)->get('/rbl/analisis');
        $response->assertStatus(200);
        $response->assertSee('Analisis Buffer Stok');
    }

    public function test_purchasing_dashboard_displays_multi_period_financials(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Finansial');
        $response->assertSee('Pendapatan Pengadaan');
        $response->assertSee('Harian');
        $response->assertSee('Mingguan');
        $response->assertSee('Bulanan');
        $response->assertSee('Tahunan');
        $response->assertViewHas('purchasingFinancials');
    }

    public function test_purchasing_nota_index_accessible(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $response = $this->actingAs($user)->get('/purchasing/nota');
        $response->assertStatus(200);
        $response->assertSee('Nota Pembelian');
        $response->assertSee('PO Barang Masuk');
        $response->assertSee('Total Akumulasi Belanja PO');
    }

    public function test_purchasing_nota_cetak_accessible(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $product = Product::create([
            'kode_produk' => 'TS-NOTE-01',
            'nama' => 'Kaos Polo Premium',
            'kategori' => 'Kaos',
            'satuan' => 'Pcs',
            'stok_aktual' => 50,
            'batas_minimum' => 20,
            'batas_maksimum' => 100,
            'harga_beli_per_satuan' => 75000,
        ]);

        $transaction = StockInTransaction::create([
            'no_nota' => 'NOTA-202610-099',
            'product_id' => $product->id,
            'user_id' => $user->id,
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 20,
            'harga_beli_satuan' => 75000,
            'total_harga' => 1500000,
            'status_pembayaran' => 'LUNAS',
            'metode_pembayaran' => 'Transfer Bank BCA',
            'no_surat_jalan' => 'SJ-TEST-123',
            'supplier' => 'PT Mitra Apparel',
        ]);

        $response = $this->actingAs($user)->get('/purchasing/nota/' . $transaction->id . '/cetak');
        $response->assertStatus(200);
        $response->assertSee('PT SANARTEX INDONESIA');
        $response->assertSee('NOTA-202610-099');
        $response->assertSee('Kaos Polo Premium');
    }

    public function test_purchasing_nota_store_and_update_status(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $product = Product::create([
            'kode_produk' => 'HD-NOTE-02',
            'nama' => 'Hoodie Zipper Fleece',
            'kategori' => 'Hoodie',
            'satuan' => 'Pcs',
            'stok_aktual' => 10,
            'batas_minimum' => 15,
            'batas_maksimum' => 80,
            'harga_beli_per_satuan' => 120000,
        ]);

        $response = $this->actingAs($user)->post('/transaksi/masuk', [
            'product_id' => $product->id,
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 15,
            'no_nota' => 'NOTA-TEST-001',
            'harga_beli_satuan' => 120000,
            'status_pembayaran' => 'TEMPO',
            'metode_pembayaran' => 'Termin 30 Hari',
            'jatuh_tempo' => now()->addDays(30)->format('Y-m-d'),
            'redirect_to' => 'nota',
        ]);

        $response->assertRedirect('/purchasing/nota');

        $transaction = StockInTransaction::where('no_nota', 'NOTA-TEST-001')->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('TEMPO', $transaction->status_pembayaran);
        $this->assertEquals(1800000, (float) $transaction->total_harga);

        // Test Update Status Pelunasan
        $updateResp = $this->actingAs($user)->put('/purchasing/nota/' . $transaction->id . '/status', [
            'status_pembayaran' => 'LUNAS',
            'metode_pembayaran' => 'Transfer Bank BCA',
            'keterangan_pelunasan' => 'Pelunasan transfer BCA',
        ]);

        $updateResp->assertSessionHas('success');
        $transaction->refresh();
        $this->assertEquals('LUNAS', $transaction->status_pembayaran);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'superadmin@sanartex.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'superadmin@sanartex.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_table_filters_work_across_modules(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        $product = Product::create([
            'kode_produk' => 'AP-HOD-099',
            'nama' => 'Hoodie Fleece Heavyweight Navy',
            'kategori' => 'Hoodie & Sweater',
            'satuan' => 'Pcs',
            'stok_aktual' => 50,
            'batas_minimum' => 20,
            'batas_maksimum' => 100,
            'lead_time_days' => 5,
            'supplier_utama' => 'CV Mitra Garment Bandung',
            'lokasi_rak' => 'Rak A-01',
        ]);

        // 1. Products filter test
        $resProd = $this->actingAs($user)->get('/products?search=Navy&kategori=Hoodie+%26+Sweater&status_stok=NORMAL&satuan=Pcs&sort=nama_asc');
        $resProd->assertStatus(200);
        $resProd->assertSee('Hoodie Fleece Heavyweight Navy');

        // 2. Stok Masuk filter test
        $resIn = $this->actingAs($user)->get('/transaksi/masuk?search=Mitra&start_date=2026-01-01&end_date=2026-12-31');
        $resIn->assertStatus(200);

        // 3. Stok Keluar filter test
        $resOut = $this->actingAs($user)->get('/transaksi/keluar?search=Outlet&start_date=2026-01-01&end_date=2026-12-31');
        $resOut->assertStatus(200);

        // 4. Laporan filter test
        $resLap = $this->actingAs($user)->get('/laporan?search=Navy&kategori=Hoodie+%26+Sweater&status_stok=NORMAL');
        $resLap->assertStatus(200);
        $resLap->assertSee('Hoodie Fleece Heavyweight Navy');

        // 5. Users filter test
        $resUser = $this->actingAs($user)->get('/users?search=admin&role=superadmin');
        $resUser->assertStatus(200);
    }

    public function test_dashboard_provides_all_five_trend_timeframe_datasets(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        // Verify that all 5 required timeframe options are present in the response
        $response->assertSee('Harian');
        $response->assertSee('Mingguan');
        $response->assertSee('3 Bulan (Triwulan)');
        $response->assertSee('6 Bulan (Semester)');
        $response->assertSee('Bulanan');

        $trendDatasets = $response->viewData('trendDatasets');
        $this->assertIsArray($trendDatasets);
        $this->assertArrayHasKey('harian', $trendDatasets);
        $this->assertArrayHasKey('mingguan', $trendDatasets);
        $this->assertArrayHasKey('triwulan', $trendDatasets);
        $this->assertArrayHasKey('semester', $trendDatasets);
        $this->assertArrayHasKey('bulanan', $trendDatasets);

        // Check specific structure
        $this->assertCount(14, $trendDatasets['harian']['labels']);
        $this->assertCount(8, $trendDatasets['mingguan']['labels']);
        $this->assertCount(3, $trendDatasets['triwulan']['labels']);
        $this->assertCount(6, $trendDatasets['semester']['labels']);
        $this->assertCount(12, $trendDatasets['bulanan']['labels']);
    }

    public function test_pagination_works_across_all_modules(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        // Create 20 products to trigger pagination
        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'kode_produk' => 'AP-TEST-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => 'Test Product Apparel ' . $i,
                'kategori' => 'Kaos & T-Shirt',
                'satuan' => 'Pcs',
                'stok_aktual' => 25,
                'batas_minimum' => 10,
                'batas_maksimum' => 100,
                'lead_time_days' => 5,
            ]);
        }

        // 1. Products page pagination
        $resProd = $this->actingAs($user)->get('/products?page=2');
        $resProd->assertStatus(200);
        $resProd->assertSee('Menampilkan');
        $resProd->assertSee('total data');

        // 2. Laporan page pagination
        $resLap = $this->actingAs($user)->get('/laporan?page=2');
        $resLap->assertStatus(200);
        $resLap->assertSee('Menampilkan');
    }

    public function test_purchasing_nota_index_and_print_faktur(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        $product = Product::create([
            'kode_produk' => 'KM-OXF-BLU-12',
            'nama' => 'Kemeja Oxford Long Sleeve Formal (Sky Blue)',
            'kategori' => 'Kemeja & Flannel',
            'satuan' => 'Pcs',
            'stok_aktual' => 41,
            'batas_minimum' => 10,
            'batas_maksimum' => 100,
            'lead_time_days' => 5,
            'harga_beli_per_satuan' => 105000,
        ]);

        $nota = StockInTransaction::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'tanggal' => '2026-10-04',
            'jumlah' => 41,
            'no_surat_jalan' => 'SJ-GRM-5000',
            'supplier' => 'PT Busana Flannel Indah',
            'harga_beli_satuan' => 105000,
            'diskon' => 0,
            'ppn_persen' => 0,
            'status_pembayaran' => 'LUNAS',
            'metode_pembayaran' => 'Transfer Bank Mandiri',
            'keterangan' => 'Penerimaan stok pakaian jadi dari vendor konveksi, lolos inspeksi QC 100%',
        ]);

        // 1. Check index page
        $resIndex = $this->actingAs($user)->get(route('purchasing.nota.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Nota Pembelian & PO Masuk');
        $resIndex->assertSee('PT Busana Flannel Indah');

        // 2. Check print page
        $resPrint = $this->actingAs($user)->get(route('purchasing.nota.cetak', $nota->id));
        $resPrint->assertStatus(200);
        $resPrint->assertSee('BUKTI NOTA PEMBELIAN');
        $resPrint->assertSee('PT SANARTEX');
        $resPrint->assertSee('Terbilang:');
        $resPrint->assertSee('Rp 4.305.000');
        $resPrint->assertSee('Empat Juta Tiga Ratus Lima Ribu Rupiah');
    }

    public function test_role_based_access_control_isolation(): void
    {
        $adminGudang = User::factory()->create(['role' => 'admin_gudang']);
        $purchasing = User::factory()->create(['role' => 'purchasing']);
        $kepalaGudang = User::factory()->create(['role' => 'kepala_gudang']);
        $superadmin = User::factory()->create(['role' => 'superadmin']);

        // 1. Admin Gudang cannot access Nota, RBL, Laporan, or Users
        $this->actingAs($adminGudang)->get(route('purchasing.nota.index'))->assertRedirect(route('dashboard'));
        $this->actingAs($adminGudang)->get(route('rbl.analisis'))->assertRedirect(route('dashboard'));
        $this->actingAs($adminGudang)->get(route('laporan.index'))->assertRedirect(route('dashboard'));
        $this->actingAs($adminGudang)->get(route('users.index'))->assertRedirect(route('dashboard'));

        // Admin Gudang CAN access Inbound & Outbound
        $this->actingAs($adminGudang)->get(route('transaksi.masuk'))->assertStatus(200);
        $this->actingAs($adminGudang)->get(route('transaksi.keluar'))->assertStatus(200);

        // 2. Purchasing cannot access Outbound or Users
        $this->actingAs($purchasing)->get(route('transaksi.keluar'))->assertRedirect(route('dashboard'));
        $this->actingAs($purchasing)->get(route('users.index'))->assertRedirect(route('dashboard'));

        // Purchasing CAN access Nota, RBL, Laporan
        $this->actingAs($purchasing)->get(route('purchasing.nota.index'))->assertStatus(200);
        $this->actingAs($purchasing)->get(route('rbl.analisis'))->assertStatus(200);
        $this->actingAs($purchasing)->get(route('laporan.index'))->assertStatus(200);

        // 3. Kepala Gudang cannot access Nota or Users
        $this->actingAs($kepalaGudang)->get(route('purchasing.nota.index'))->assertRedirect(route('dashboard'));
        $this->actingAs($kepalaGudang)->get(route('users.index'))->assertRedirect(route('dashboard'));

        // Kepala Gudang CAN access Inbound, Outbound, RBL, Laporan
        $this->actingAs($kepalaGudang)->get(route('transaksi.masuk'))->assertStatus(200);
        $this->actingAs($kepalaGudang)->get(route('transaksi.keluar'))->assertStatus(200);
        $this->actingAs($kepalaGudang)->get(route('rbl.analisis'))->assertStatus(200);
        $this->actingAs($kepalaGudang)->get(route('laporan.index'))->assertStatus(200);

        // 4. Manajemen (Direksi / C-Level) has full strategic view of Inbound, Outbound, Nota, RBL, Laporan, but cannot manage users
        $manajemen = User::factory()->create(['role' => 'manajemen']);
        $this->actingAs($manajemen)->get(route('purchasing.nota.index'))->assertStatus(200);
        $this->actingAs($manajemen)->get(route('transaksi.masuk'))->assertStatus(200);
        $this->actingAs($manajemen)->get(route('transaksi.keluar'))->assertStatus(200);
        $this->actingAs($manajemen)->get(route('rbl.analisis'))->assertStatus(200);
        $this->actingAs($manajemen)->get(route('laporan.index'))->assertStatus(200);
        $this->actingAs($manajemen)->get(route('users.index'))->assertRedirect(route('dashboard'));

        // 5. Superadmin has full access across all modules
        $this->actingAs($superadmin)->get(route('purchasing.nota.index'))->assertStatus(200);
        $this->actingAs($superadmin)->get(route('transaksi.masuk'))->assertStatus(200);
        $this->actingAs($superadmin)->get(route('transaksi.keluar'))->assertStatus(200);
        $this->actingAs($superadmin)->get(route('rbl.analisis'))->assertStatus(200);
        $this->actingAs($superadmin)->get(route('laporan.index'))->assertStatus(200);
        $this->actingAs($superadmin)->get(route('users.index'))->assertStatus(200);
    }
}



