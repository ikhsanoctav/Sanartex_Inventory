<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockInTransaction;
use App\Models\StockOutTransaction;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Dapatkan semua notifikasi sistem aktif (Peringatan Stok & Transaksi Terkini)
     */
    public static function getNotifications(): array
    {
        $notifications = collect();

        // 1. Peringatan Stok Kritis (Stok <= Batas Minimum)
        $kritisProducts = Product::whereRaw('stok_aktual <= batas_minimum')->get();
        foreach ($kritisProducts as $p) {
            $saran = RblEvaluatorService::calculateSuggestedOrder($p);
            $notifications->push([
                'id' => 'kritis_' . $p->id,
                'category' => 'rbl',
                'severity' => 'critical',
                'title' => 'Stok Kritis: ' . $p->nama,
                'message' => "Sisa stok {$p->stok_aktual} {$p->satuan} (Batas Min: {$p->batas_minimum}). Rekomendasi order: {$saran} {$p->satuan}.",
                'time' => 'Perlu Tindakan Segera',
                'timestamp' => now()->timestamp,
                'badge' => 'KRITIS',
                'badge_class' => 'bg-rose-50 text-rose-700 border border-rose-200',
                'icon_bg' => 'bg-rose-100 text-rose-600',
                'url' => route('rbl.analisis'),
                'action_label' => 'Analisis RBL',
            ]);
        }

        // 2. Peringatan Reorder Point / Menipis (Batas Minimum < Stok <= Reorder Point atau Stok <= 1.25 * Min)
        $lowStockProducts = Product::whereRaw('stok_aktual > batas_minimum')
            ->where(function ($q) {
                $q->whereRaw('stok_aktual <= reorder_point')
                  ->orWhereRaw('stok_aktual <= (batas_minimum * 1.3)');
            })->take(3)->get();

        foreach ($lowStockProducts as $p) {
            $notifications->push([
                'id' => 'low_' . $p->id,
                'category' => 'rbl',
                'severity' => 'warning',
                'title' => 'Stok Menipis: ' . $p->nama,
                'message' => "Stok tersisa {$p->stok_aktual} {$p->satuan} mendekati reorder buffer ({$p->batas_minimum} {$p->satuan}).",
                'time' => 'Buffer Menipis',
                'timestamp' => now()->subMinutes(15)->timestamp,
                'badge' => 'MENIPIS',
                'badge_class' => 'bg-amber-50 text-amber-800 border border-amber-200',
                'icon_bg' => 'bg-amber-100 text-amber-600',
                'url' => route('rbl.analisis'),
                'action_label' => 'Cek Buffer',
            ]);
        }

        // 3. Peringatan Stok Berlebih / Overstock (Stok > Batas Maksimum)
        $overstockProducts = Product::whereRaw('stok_aktual > batas_maksimum')->take(3)->get();
        foreach ($overstockProducts as $p) {
            $excess = $p->stok_aktual - $p->batas_maksimum;
            $notifications->push([
                'id' => 'overstock_' . $p->id,
                'category' => 'rbl',
                'severity' => 'info',
                'title' => 'Stok Berlebih: ' . $p->nama,
                'message' => "Stok {$p->stok_aktual} {$p->satuan} melebihi batas maksimum ({$p->batas_maksimum} {$p->satuan}) sebanyak +{$excess} {$p->satuan}.",
                'time' => 'Overstock Warning',
                'timestamp' => now()->subHours(1)->timestamp,
                'badge' => 'BERLEBIH',
                'badge_class' => 'bg-sky-50 text-sky-700 border border-sky-200',
                'icon_bg' => 'bg-sky-100 text-sky-600',
                'url' => route('rbl.analisis'),
                'action_label' => 'Evaluasi Pemakaian',
            ]);
        }

        // 4. Transaksi Stok Masuk Terbaru (Terakhir 3 transaksi)
        $recentInbound = StockInTransaction::with('product')->latest('id')->take(3)->get();
        foreach ($recentInbound as $tx) {
            $productName = $tx->product ? $tx->product->nama : 'Produk Tekstil';
            $unit = $tx->product ? $tx->product->satuan : 'Unit';
            $sj = $tx->no_surat_jalan ?: 'SJ-Internal';
            $supplier = $tx->supplier ?: 'Supplier Terdaftar';
            $notifications->push([
                'id' => 'in_' . $tx->id,
                'category' => 'activity',
                'severity' => 'success',
                'title' => 'Penerimaan Stok Masuk',
                'message' => "+{$tx->jumlah} {$unit} {$productName} diterima dari {$supplier} ({$sj}).",
                'time' => $tx->created_at ? $tx->created_at->diffForHumans() : 'Hari ini',
                'timestamp' => $tx->created_at ? $tx->created_at->timestamp : now()->timestamp,
                'badge' => 'MASUK',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                'icon_bg' => 'bg-emerald-100 text-emerald-600',
                'url' => route('transaksi.masuk'),
                'action_label' => 'Lihat Masuk',
            ]);
        }

        // 5. Transaksi Stok Keluar Terbaru (Terakhir 3 transaksi)
        $recentOutbound = StockOutTransaction::with('product')->latest('id')->take(3)->get();
        foreach ($recentOutbound as $tx) {
            $productName = $tx->product ? $tx->product->nama : 'Produk Tekstil';
            $unit = $tx->product ? $tx->product->satuan : 'Unit';
            $spk = $tx->no_spk_tujuan ?: 'SPK-Internal';
            $divisi = $tx->penerima_divisi ?: 'Divisi Produksi';
            $notifications->push([
                'id' => 'out_' . $tx->id,
                'category' => 'activity',
                'severity' => 'primary',
                'title' => 'Pengeluaran Stok Keluar',
                'message' => "-{$tx->jumlah} {$unit} {$productName} disalurkan ke {$divisi} ({$spk}).",
                'time' => $tx->created_at ? $tx->created_at->diffForHumans() : 'Hari ini',
                'timestamp' => $tx->created_at ? $tx->created_at->timestamp : now()->timestamp,
                'badge' => 'KELUAR',
                'badge_class' => 'bg-orange-50 text-orange-700 border border-orange-200',
                'icon_bg' => 'bg-orange-100 text-orange-600',
                'url' => route('transaksi.keluar'),
                'action_label' => 'Lihat Keluar',
            ]);
        }

        // Sort: critical first, then latest timestamp
        $sorted = $notifications->sort(function ($a, $b) {
            if ($a['severity'] === 'critical' && $b['severity'] !== 'critical') return -1;
            if ($b['severity'] === 'critical' && $a['severity'] !== 'critical') return 1;
            return $b['timestamp'] <=> $a['timestamp'];
        })->values();

        $unreadCount = $sorted->count();
        $rblCount = $sorted->where('category', 'rbl')->count();
        $activityCount = $sorted->where('category', 'activity')->count();

        return [
            'items' => $sorted->all(),
            'total' => $unreadCount,
            'rbl_total' => $rblCount,
            'activity_total' => $activityCount,
            'has_critical' => $sorted->where('severity', 'critical')->count() > 0,
        ];
    }
}
