<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori',
        'satuan',
        'stok_aktual',
        'batas_minimum',
        'batas_maksimum',
        'status_stok',
    ];

    protected static function booted()
    {
        static::saving(function ($product) {
            $status = 'NORMAL';
            
            if ($product->stok_aktual <= $product->batas_minimum) {
                $status = 'KRITIS';
            } elseif ($product->stok_aktual > $product->batas_maksimum) {
                $status = 'BERLEBIH';
            }
            
            $product->status_stok = $status;
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
     * Re-evaluate stock status based on actual stock
     */
    public function evaluateStockStatus()
    {
        $status = 'NORMAL';
        
        if ($this->stok_aktual <= $this->batas_minimum) {
            $status = 'KRITIS';
        } elseif ($this->stok_aktual > $this->batas_maksimum) {
            $status = 'BERLEBIH';
        }

        if ($this->status_stok !== $status) {
            $this->status_stok = $status;
            $this->save();
        }

        return $status;
    }
}
