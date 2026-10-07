<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\RblEvaluatorService;
use Tests\TestCase;

class RblEvaluatorTest extends TestCase
{
    public function test_evaluates_kritis_zone_when_stock_at_or_below_minimum(): void
    {
        $product = new Product([
            'stok_aktual' => 15,
            'batas_minimum' => 20,
            'batas_maksimum' => 100,
        ]);

        $status = RblEvaluatorService::evaluate($product);
        $this->assertEquals('KRITIS', $status);

        // Exact minimum
        $product->stok_aktual = 20;
        $this->assertEquals('KRITIS', RblEvaluatorService::evaluate($product));

        // Zero stock
        $product->stok_aktual = 0;
        $this->assertEquals('KRITIS', RblEvaluatorService::evaluate($product));
    }

    public function test_evaluates_normal_zone_when_stock_between_min_and_max(): void
    {
        $product = new Product([
            'stok_aktual' => 50,
            'batas_minimum' => 20,
            'batas_maksimum' => 100,
        ]);

        $status = RblEvaluatorService::evaluate($product);
        $this->assertEquals('NORMAL', $status);

        // Upper boundary
        $product->stok_aktual = 100;
        $this->assertEquals('NORMAL', RblEvaluatorService::evaluate($product));

        // Just above min
        $product->stok_aktual = 21;
        $this->assertEquals('NORMAL', RblEvaluatorService::evaluate($product));
    }

    public function test_evaluates_berlebih_zone_when_stock_exceeds_maximum(): void
    {
        $product = new Product([
            'stok_aktual' => 101,
            'batas_minimum' => 20,
            'batas_maksimum' => 100,
        ]);

        $status = RblEvaluatorService::evaluate($product);
        $this->assertEquals('BERLEBIH', $status);
    }

    public function test_calculates_suggested_order_quantity_correctly(): void
    {
        $product = new Product([
            'stok_aktual' => 10,
            'batas_minimum' => 20,
            'batas_maksimum' => 100,
            'status_stok' => 'KRITIS',
        ]);

        // When critical, suggested = max - current = 100 - 10 = 90
        $suggested = RblEvaluatorService::calculateSuggestedOrder($product);
        $this->assertEquals(90, $suggested);

        // When normal, suggested = 0
        $product->stok_aktual = 50;
        $product->status_stok = 'NORMAL';
        $suggested = RblEvaluatorService::calculateSuggestedOrder($product);
        $this->assertEquals(0, $suggested);
    }

    public function test_meta_and_health_bar_metrics_structure(): void
    {
        $metaKritis = RblEvaluatorService::getStatusMeta('KRITIS');
        $this->assertEquals('STOK KRITIS', $metaKritis['label']);
        $this->assertStringContainsString('rose', $metaKritis['badge_class']);

        $metaNormal = RblEvaluatorService::getStatusMeta('NORMAL');
        $this->assertEquals('STOK NORMAL', $metaNormal['label']);
        $this->assertStringContainsString('emerald', $metaNormal['badge_class']);

        $metaBerlebih = RblEvaluatorService::getStatusMeta('BERLEBIH');
        $this->assertEquals('STOK BERLEBIH', $metaBerlebih['label']);
        $this->assertStringContainsString('blue', $metaBerlebih['badge_class']);

        $product = new Product([
            'stok_aktual' => 50,
            'batas_minimum' => 20,
            'batas_maksimum' => 100,
        ]);

        $metrics = RblEvaluatorService::getHealthBarMetrics($product);
        $this->assertArrayHasKey('current_percent', $metrics);
        $this->assertArrayHasKey('min_percent', $metrics);
        $this->assertArrayHasKey('max_percent', $metrics);
        $this->assertGreaterThan(0, $metrics['current_percent']);
    }
}
