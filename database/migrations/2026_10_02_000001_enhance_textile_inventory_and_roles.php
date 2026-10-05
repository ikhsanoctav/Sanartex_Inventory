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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('admin_gudang')->after('email');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'kode_produk')) {
                $table->string('kode_produk')->nullable()->after('id');
            }
            if (!Schema::hasColumn('products', 'reorder_point')) {
                $table->integer('reorder_point')->nullable()->after('batas_minimum');
            }
            if (!Schema::hasColumn('products', 'lead_time_days')) {
                $table->integer('lead_time_days')->default(7)->after('batas_maksimum');
            }
            if (!Schema::hasColumn('products', 'lokasi_rak')) {
                $table->string('lokasi_rak')->nullable()->after('lead_time_days');
            }
            if (!Schema::hasColumn('products', 'supplier_utama')) {
                $table->string('supplier_utama')->nullable()->after('lokasi_rak');
            }
            if (!Schema::hasColumn('products', 'harga_beli_per_satuan')) {
                $table->decimal('harga_beli_per_satuan', 15, 2)->default(0)->after('supplier_utama');
            }
            if (!Schema::hasColumn('products', 'spesifikasi')) {
                $table->text('spesifikasi')->nullable()->after('harga_beli_per_satuan');
            }
        });

        Schema::table('stock_in_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_in_transactions', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('product_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('stock_in_transactions', 'no_surat_jalan')) {
                $table->string('no_surat_jalan')->nullable()->after('jumlah');
            }
            if (!Schema::hasColumn('stock_in_transactions', 'supplier')) {
                $table->string('supplier')->nullable()->after('no_surat_jalan');
            }
            if (!Schema::hasColumn('stock_in_transactions', 'no_batch_lot')) {
                $table->string('no_batch_lot')->nullable()->after('supplier');
            }
        });

        Schema::table('stock_out_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_out_transactions', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('product_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('stock_out_transactions', 'no_spk_tujuan')) {
                $table->string('no_spk_tujuan')->nullable()->after('jumlah');
            }
            if (!Schema::hasColumn('stock_out_transactions', 'penerima_divisi')) {
                $table->string('penerima_divisi')->nullable()->after('no_spk_tujuan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'avatar']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'kode_produk', 'reorder_point', 'lead_time_days', 
                'lokasi_rak', 'supplier_utama', 'harga_beli_per_satuan', 'spesifikasi'
            ]);
        });

        Schema::table('stock_in_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'no_surat_jalan', 'supplier', 'no_batch_lot']);
        });

        Schema::table('stock_out_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'no_spk_tujuan', 'penerima_divisi']);
        });
    }
};
