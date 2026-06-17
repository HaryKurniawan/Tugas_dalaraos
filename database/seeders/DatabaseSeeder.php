<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('aa'),
        ]);

        User::factory()->create([
            'name' => 'Pemilik',
            'email' => 'pemilik@pemilik.com',
            'password' => bcrypt('aa'),
        ]);

        $this->call(KecamatanSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(ProdukPosSeeder::class);
    }
}
