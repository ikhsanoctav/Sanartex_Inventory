<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class TempProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = ['Hoodie', 'Hoodie kombinasi', 'Crewneck', 'Hoodie anak', 'Setelan anak'];
        foreach($products as $p) {
            Product::firstOrCreate(
                ['nama' => $p],
                [
                    'kategori' => 'Pakaian',
                    'satuan' => 'Pcs',
                    'stok_aktual' => rand(10, 100),
                    'batas_minimum' => 10,
                    'batas_maksimum' => 200,
                    'kategori_dt' => 'Regular',
                    'status_stok' => 'NORMAL',
                ]
            );
        }
    }
}
