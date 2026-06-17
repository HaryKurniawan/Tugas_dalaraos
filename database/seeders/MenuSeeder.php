<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Paket Katering
        Menu::create([
            'sku' => 'm8',
            'nama_menu' => 'Paket Khas Sunda – Timbel Standard',
            'harga' => 35000,
            'kategori' => 'Katering',
            'badge' => '⭐ Paling Populer',
            'isi_paket' => ['Nasi Timbel / Liwet', 'Lauk Utama: Daging / Ayam / Ikan', 'Pendamping: Perkedel / Pepes Tahu', 'Sayuran: Tumis Khas Sunda', 'Lalab segar & Sambal Khas'],
            'gambar' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80',
        ]);

        Menu::create([
            'sku' => 'm9',
            'nama_menu' => 'Paket Khas Sunda Premium (Double Lauk)',
            'harga' => 49000,
            'kategori' => 'Katering',
            'badge' => '👑 Mewah & Komplet',
            'isi_paket' => ['Nasi Timbel / Liwet / Tutug Oncom', 'Lauk Sapi: Gepuk / Daging Serundeng', 'Lauk Kedua: Ayam Bakar / Goreng', 'Pendamping: Perkedel / Pepes Tahu', 'Sayuran: Tumis Pilihan Spesial'],
            'gambar' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=600&q=80',
        ]);

        Menu::create([
            'sku' => 'm10',
            'nama_menu' => 'Paket Nasi Putih Standard',
            'harga' => 35000,
            'kategori' => 'Katering',
            'badge' => '✅ Pilihan Praktis',
            'isi_paket' => ['Nasi Putih Premium Pulen', 'Lauk Utama: Daging / Ayam / Ikan', 'Pendamping: Lauk Tambahan Gurih', 'Sayuran: Tumis / Sayur Berkuah', 'Pelengkap: Sambal Waroeng'],
            'gambar' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=600&q=80',
        ]);

        Menu::create([
            'sku' => 'm12',
            'nama_menu' => 'Paket Nasi Tumpeng Kuning',
            'harga' => 42000,
            'kategori' => 'Katering',
            'badge' => '🎊 Spesial Syukuran',
            'isi_paket' => ['Nasi Kuning Harum', 'Lauk Utama: Ayam Bakar / Goreng', 'Lauk Samping: Semur Telur Pindang', 'Sayuran: Urap Sayur Bumbu Kelapa', 'Pendamping: Perkedel Kentang'],
            'gambar' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=600&q=80',
        ]);

        // 2. Desserts
        Menu::create([
            'sku' => 'ds1',
            'nama_menu' => 'Kue Sarikaya',
            'harga' => 5000,
            'kategori' => 'Dessert',
            'badge' => '🍰 Manis',
            'deskripsi' => 'Kue sarikaya tradisional yang manis dan lembut.',
            'gambar' => '',
        ]);

        Menu::create([
            'sku' => 'ds2',
            'nama_menu' => 'Pudding Fla',
            'harga' => 6000,
            'kategori' => 'Dessert',
            'badge' => '🍮 Lembut',
            'deskripsi' => 'Pudding coklat dengan fla vanilla yang creamy.',
            'gambar' => '',
        ]);

        Menu::create([
            'sku' => 'ds3',
            'nama_menu' => 'Jeruk Segar',
            'harga' => 5000,
            'kategori' => 'Dessert',
            'badge' => '🍊 Segar',
            'deskripsi' => 'Buah jeruk segar pilihan.',
            'gambar' => '',
        ]);

        Menu::create([
            'sku' => 'ds4',
            'nama_menu' => 'Pisang',
            'harga' => 2500,
            'kategori' => 'Dessert',
            'badge' => '🍌 Praktis',
            'deskripsi' => 'Pisang segar pilihan.',
            'gambar' => '',
        ]);
    }
}
