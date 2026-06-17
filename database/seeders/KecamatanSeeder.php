<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\OngkirKecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Mandalajati' => 5000,
            'Antapani' => 5000,
            'Kiaracondong' => 8000,
            'Cibeunying Kidul' => 8000,
            'Arcamanik' => 8000,
            'Cinambo' => 10000,
            'Ujungberung' => 10000,
            'Panyileukan' => 10000,
            'Gedebage' => 12000,
            'Cibiru' => 12000,
            'Buahbatu' => 12000,
            'Batununggal' => 12000,
            'Bandung Wetan' => 12000,
            'Coblong' => 15000,
            'Lengkong' => 15000,
            'Sumur Bandung' => 15000,
            'Regol' => 15000,
            'Bandung Kidul' => 15000,
            'Cicendo' => 18000,
            'Andir' => 18000,
            'Astana Anyar' => 18000,
            'Cidadap' => 20000,
            'Sukajadi' => 20000,
            'Sukasari' => 20000,
            'Babakan Ciparay' => 20000,
            'Bojongloa Kaler' => 20000,
            'Bojongloa Kidul' => 20000,
            'Bandung Kulon' => 22000,
        ];

        foreach ($data as $nama => $ongkir) {
            $kecamatan = Kecamatan::create(['nama' => $nama]);
            OngkirKecamatan::create([
                'kecamatan_id' => $kecamatan->id,
                'ongkir' => $ongkir,
            ]);
        }
    }
}
