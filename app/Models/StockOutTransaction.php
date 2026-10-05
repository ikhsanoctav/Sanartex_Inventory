<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOutTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'tanggal',
        'jumlah',
        'no_spk_tujuan',
        'penerima_divisi',
        'keterangan',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            $product = $transaction->product;
            if ($product) {
                $product->stok_aktual -= $transaction->jumlah;
                $product->save();
            }
        });

        static::deleted(function ($transaction) {
            $product = $transaction->product;
            if ($product) {
                $product->stok_aktual += $transaction->jumlah;
                $product->save();
            }
        });
    }
}
