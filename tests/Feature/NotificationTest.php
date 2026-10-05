<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_fetch_notifications_feed(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        Product::create([
            'kode_produk' => 'AP-HOD-NOTIF-01',
            'nama' => 'Hoodie Fleece Basic Hitam',
            'kategori' => 'Hoodie & Sweater',
            'satuan' => 'Pcs',
            'stok_aktual' => 5,
            'batas_minimum' => 20,
            'reorder_point' => 30,
            'batas_maksimum' => 100,
            'status_stok' => 'KRITIS',
        ]);

        $response = $this->actingAs($user)->getJson(route('notifications.feed'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'items',
                'total',
                'rbl_total',
                'activity_total',
                'has_critical'
            ]);

        $this->assertTrue($response->json('has_critical'));
        $this->assertGreaterThan(0, $response->json('total'));
    }
}
