<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_in_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_in_transactions', 'no_nota')) {
                $table->string('no_nota')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('stock_in_transactions', 'harga_beli_satuan')) {
                $table->decimal('harga_beli_satuan', 15, 2)->default(0)->after('jumlah');
            }
            if (!Schema::hasColumn('stock_in_transactions', 'diskon')) {
                $table->decimal('diskon', 15, 2)->default(0)->after('harga_beli_satuan');
            }
            if (!Schema::hasColumn('stock_in_transactions', 'ppn_persen')) {
                $table->decimal('ppn_persen', 5, 2)->default(0)->after('diskon');
            }
            if (!Schema::hasColumn('stock_in_transactions', 'total_harga')) {
                $table->decimal('total_harga', 15, 2)->default(0)->after('ppn_persen');
            }
            if (!Schema::hasColumn('stock_in_transactions', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('LUNAS')->after('total_harga'); // LUNAS, TEMPO, DP
            }
            if (!Schema::hasColumn('stock_in_transactions', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->default('Transfer Bank')->after('status_pembayaran'); // Transfer Bank, Cash, Bilyet Giro, Tempo
            }
            if (!Schema::hasColumn('stock_in_transactions', 'jatuh_tempo')) {
                $table->date('jatuh_tempo')->nullable()->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('stock_in_transactions', 'bukti_nota')) {
                $table->string('bukti_nota')->nullable()->after('keterangan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_in_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'no_nota',
                'harga_beli_satuan',
                'diskon',
                'ppn_persen',
                'total_harga',
                'status_pembayaran',
                'metode_pembayaran',
                'jatuh_tempo',
                'bukti_nota',
            ]);
        });
    }
};
