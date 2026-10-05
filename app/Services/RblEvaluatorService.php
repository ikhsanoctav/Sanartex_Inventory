<?php

namespace App\Services;

use App\Models\Product;

class RblEvaluatorService
{
    /**
     * Evaluasi status stok berdasarkan aturan 3-Zona RBL:
     * - KRITIS  : Stok <= Batas Minimum (Safety Stock / Reorder Trigger)
     * - NORMAL  : Batas Minimum < Stok <= Batas Maksimum (Zona Optimal)
     * - BERLEBIH: Stok > Batas Maksimum (Zona Overstock)
     */
    public static function evaluate(Product $product): string
    {
        $stok = (int) $product->stok_aktual;
        $min  = (int) $product->batas_minimum;
        $max  = (int) $product->batas_maksimum;

        if ($stok <= $min) {
            return 'KRITIS';
        } elseif ($stok <= $max) {
            return 'NORMAL';
        } else {
            return 'BERLEBIH';
        }
    }

    /**
     * Hitung kuantitas saran pembelian ulang (Reorder Suggestion)
     */
    public static function calculateSuggestedOrder(Product $product): int
    {
        $stok = (int) $product->stok_aktual;
        $max  = (int) $product->batas_maksimum;

        if ($product->status_stok === 'KRITIS' || $stok <= (int) $product->batas_minimum) {
            return max(0, $max - $stok);
        }

        return 0;
    }

    /**
     * Dapatkan metadata visual untuk badge zona RBL (3 Zona)
     */
    public static function getStatusMeta(string $status): array
    {
        return match ($status) {
            'KRITIS' => [
                'label' => 'STOK KRITIS',
                'badge_class' => 'bg-rose-100 text-rose-800 border-rose-200',
                'dot_class' => 'bg-rose-500',
                'zone' => 'Zona Merah (Kritis / Reorder)',
                'action' => 'Segera terbitkan pesanan pengadaan (PO) ke supplier',
                'icon' => 'exclamation-circle',
            ],
            'NORMAL' => [
                'label' => 'STOK NORMAL',
                'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'dot_class' => 'bg-emerald-500',
                'zone' => 'Zona Hijau (Normal / Optimal)',
                'action' => 'Persediaan ideal dan mencukupi kebutuhan operasional',
                'icon' => 'check-circle',
            ],
            'BERLEBIH' => [
                'label' => 'STOK BERLEBIH',
                'badge_class' => 'bg-blue-100 text-blue-800 border-blue-200',
                'dot_class' => 'bg-blue-500',
                'zone' => 'Zona Biru (Berlebih / Overstock)',
                'action' => 'Tahan pesanan baru, evaluasi pemakaian kain agar tidak menumpuk',
                'icon' => 'archive-box-x-mark',
            ],
            default => [
                'label' => $status,
                'badge_class' => 'bg-slate-100 text-slate-800 border-slate-200',
                'dot_class' => 'bg-slate-500',
                'zone' => 'Unknown',
                'action' => 'Pantau status persediaan',
                'icon' => 'information-circle',
            ],
        };
    }

    /**
     * Hitung persentase posisi stok untuk visual stock health bar (3 Zona)
     */
    public static function getHealthBarMetrics(Product $product): array
    {
        $stok = (int) $product->stok_aktual;
        $min  = max(1, (int) $product->batas_minimum);
        $max  = max($min + 1, (int) $product->batas_maksimum);

        // Range scale normalized to max + 20%
        $totalRange = $max * 1.2;
        $currentPct = min(100, max(0, ($stok / $totalRange) * 100));
        $minPct     = min(100, ($min / $totalRange) * 100);
        $maxPct     = min(100, ($max / $totalRange) * 100);

        return [
            'stok' => $stok,
            'min' => $min,
            'max' => $max,
            'current_percent' => round($currentPct, 1),
            'min_percent' => round($minPct, 1),
            'max_percent' => round($maxPct, 1),
        ];
    }
}
