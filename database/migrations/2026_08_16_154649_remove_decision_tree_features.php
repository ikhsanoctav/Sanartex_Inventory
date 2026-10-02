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
        Schema::dropIfExists('dt_classification_histories');
        Schema::dropIfExists('system_settings');
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('kategori_dt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('threshold_frekuensi')->default(3);
            $table->integer('threshold_avg_unit')->default(100);
            $table->decimal('threshold_rasio', 5, 2)->default(0.80);
            $table->timestamps();
        });

        Schema::create('dt_classification_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_analisis');
            $table->integer('frekuensi');
            $table->decimal('avg_unit', 10, 2);
            $table->decimal('rasio_keluar_masuk', 8, 2);
            $table->string('kategori_hasil');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('kategori_dt')->nullable();
        });
    }
};
