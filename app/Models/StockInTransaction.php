<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockInTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_nota',
        'product_id',
        'user_id',
        'tanggal',
        'jumlah',
        'harga_beli_satuan',
        'diskon',
        'ppn_persen',
        'total_harga',
        'status_pembayaran',
        'metode_pembayaran',
        'jatuh_tempo',
        'no_surat_jalan',
        'supplier',
        'no_batch_lot',
        'keterangan',
        'bukti_nota',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_beli_satuan' => 'decimal:2',
        'diskon' => 'decimal:2',
        'ppn_persen' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
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
        static::creating(function ($transaction) {
            // Auto generate No. Nota Pembelian jika belum diisi
            if (empty($transaction->no_nota)) {
                $transaction->no_nota = 'NOTA-' . now()->format('Ym') . '-' . strtoupper(substr(uniqid(), -5));
            }

            // Fallback harga beli dari master produk jika belum diisi
            if (empty($transaction->harga_beli_satuan) || $transaction->harga_beli_satuan <= 0) {
                $product = $transaction->product ?? Product::find($transaction->product_id);
                if ($product) {
                    $transaction->harga_beli_satuan = $product->harga_beli_per_satuan ?? 0;
                }
            }

            // Hitung total harga otomatis jika 0
            if (empty($transaction->total_harga) || $transaction->total_harga <= 0) {
                $subtotal = ($transaction->jumlah * ($transaction->harga_beli_satuan ?? 0)) - ($transaction->diskon ?? 0);
                $ppn = $subtotal * (($transaction->ppn_persen ?? 0) / 100);
                $transaction->total_harga = max(0, $subtotal + $ppn);
            }
        });

        static::created(function ($transaction) {
            $product = $transaction->product;
            if ($product) {
                $product->stok_aktual += $transaction->jumlah;
                $product->save();
            }
        });

        static::deleted(function ($transaction) {
            $product = $transaction->product;
            if ($product) {
                $product->stok_aktual -= $transaction->jumlah;
                if ($product->stok_aktual < 0) {
                    $product->stok_aktual = 0;
                }
                $product->save();
            }
        });
    }
}
