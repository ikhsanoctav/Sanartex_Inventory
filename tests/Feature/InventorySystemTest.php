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
        $roles = ['superadmin', 'kepala_gudang', 'admin_gudang', 'purchasing'];

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
}
