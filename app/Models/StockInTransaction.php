<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockInTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'tanggal',
        'jumlah',
        'keterangan',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            $product = $transaction->product;
            $product->stok_aktual += $transaction->jumlah;
            $product->save();
        });

        static::deleted(function ($transaction) {
            $product = $transaction->product;
            $product->stok_aktual -= $transaction->jumlah;
            if($product->stok_aktual < 0) $product->stok_aktual = 0;
            $product->save();
        });
    }
}
