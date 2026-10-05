<?php

namespace App\Models;

use App\Services\RblEvaluatorService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_produk',
        'nama',
        'kategori',
        'satuan',
        'stok_aktual',
        'batas_minimum',
        'reorder_point',
        'batas_maksimum',
        'lead_time_days',
        'lokasi_rak',
        'supplier_utama',
        'harga_beli_per_satuan',
        'spesifikasi',
        'status_stok',
    ];

    protected $casts = [
        'stok_aktual' => 'integer',
        'batas_minimum' => 'integer',
        'reorder_point' => 'integer',
        'batas_maksimum' => 'integer',
        'lead_time_days' => 'integer',
        'harga_beli_per_satuan' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function ($product) {
            // Evaluasi otomatis status stok dengan mesin RBL
            $product->status_stok = RblEvaluatorService::evaluate($product);
        });
    }

    public function stockInTransactions(): HasMany
    {
        return $this->hasMany(StockInTransaction::class);
    }

    public function stockOutTransactions(): HasMany
    {
        return $this->hasMany(StockOutTransaction::class);
    }

    /**
     * Evaluasi ulang status stok
     */
    public function evaluateStockStatus(): string
    {
        $status = RblEvaluatorService::evaluate($this);

        if ($this->status_stok !== $status) {
            $this->status_stok = $status;
            $this->saveQuietly();
        }

        return $status;
    }

    /**
     * Dapatkan saran jumlah pesanan pengadaan
     */
    public function getSuggestedOrderQuantityAttribute(): int
    {
        return RblEvaluatorService::calculateSuggestedOrder($this);
    }

    /**
     * Dapatkan metadata visual status RBL
     */
    public function getRblMetaAttribute(): array
    {
        return RblEvaluatorService::getStatusMeta($this->status_stok ?? 'NORMAL');
    }

    /**
     * Dapatkan metrik health bar
     */
    public function getHealthBarAttribute(): array
    {
        return RblEvaluatorService::getHealthBarMetrics($this);
    }
}
