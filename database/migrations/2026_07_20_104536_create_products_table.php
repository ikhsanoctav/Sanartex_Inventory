<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori');
            $table->string('satuan');
            $table->integer('stok_aktual')->default(0);
            $table->integer('batas_minimum');
            $table->integer('batas_maksimum');
            $table->string('kategori_dt')->nullable();
            $table->string('status_stok')->default('NORMAL');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
