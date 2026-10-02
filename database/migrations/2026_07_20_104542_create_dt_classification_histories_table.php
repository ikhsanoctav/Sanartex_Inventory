<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dt_classification_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_analisis');
            $table->integer('frekuensi');
            $table->decimal('avg_unit', 10, 2);
            $table->decimal('rasio_keluar_masuk', 10, 2);
            $table->string('kategori_hasil');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dt_classification_histories');
    }
};
