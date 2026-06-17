<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProdukPos;

class ProdukPosSeeder extends Seeder
{
    public function run(): void
    {
        ProdukPos::create([
            'sku' => 'pos1',
            'nama_produk' => 'Nasi Goreng Spesial',
            'harga' => 25000,
            'kategori' => 'Dine-in',
            'deskripsi' => 'Nasi goreng dengan telur, sosis, dan ayam',
        ]);

        ProdukPos::create([
            'sku' => 'pos2',
            'nama_produk' => 'Es Teh Manis',
            'harga' => 5000,
            'kategori' => 'Minuman',
            'deskripsi' => 'Teh manis dingin yang menyegarkan',
        ]);

        ProdukPos::create([
            'sku' => 'pos3',
            'nama_produk' => 'Pisang Goreng Keju',
            'harga' => 15000,
            'kategori' => 'Snack',
            'deskripsi' => 'Pisang goreng manis dengan taburan keju dan coklat',
        ]);
    }
}
