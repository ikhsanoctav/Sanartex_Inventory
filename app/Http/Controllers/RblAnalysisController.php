<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\RblEvaluatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RblAnalysisController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role === 'admin_gudang' && !Auth::user()->isSuperadmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses dibatasi. Modul Analisis Buffer RBL diperuntukkan bagi Kepala Gudang, Purchasing, dan Superadmin.');
        }

        $allProducts = Product::orderBy('status_stok', 'asc')->orderBy('nama', 'asc')->get();

        $kritis = $allProducts->where('status_stok', 'KRITIS');
        $normal = $allProducts->where('status_stok', 'NORMAL');
        $berlebih = $allProducts->where('status_stok', 'BERLEBIH');

        // Data saran pengadaan (Reorder Suggestions - Kritis)
        $reorders = $allProducts->filter(function ($p) {
            return $p->status_stok === 'KRITIS';
        })->map(function ($p) {
            $suggested = RblEvaluatorService::calculateSuggestedOrder($p);
            $subtotal = $suggested * ($p->harga_beli_per_satuan ?? 0);
            return [
                'product' => $p,
                'suggested_order' => $suggested,
                'estimated_cost' => $subtotal,
                'priority' => 'PRIORITAS 1 (URGENT)',
            ];
        });

        $totalEstimatedReorderCost = $reorders->sum('estimated_cost');

        return view('rbl.analisis', compact(
            'allProducts',
            'kritis',
            'normal',
            'berlebih',
            'reorders',
            'totalEstimatedReorderCost'
        ));
    }
}
