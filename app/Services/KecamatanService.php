<?php

namespace App\Services;

use App\Models\Kecamatan;
use App\Models\OngkirKecamatan;
use Illuminate\Support\Facades\DB;

class KecamatanService
{
    /**
     * Get all kecamatan with their ongkir
     */
    public function getAll()
    {
        return Kecamatan::with('ongkir')->orderBy('nama')->get();
    }

    /**
     * Store new kecamatan and its ongkir
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $kecamatan = Kecamatan::create(['nama' => $data['nama']]);
            
            if (isset($data['ongkir'])) {
                OngkirKecamatan::create([
                    'kecamatan_id' => $kecamatan->id,
                    'ongkir' => $data['ongkir']
                ]);
            }
            
            return $kecamatan;
        });
    }

    /**
     * Update existing kecamatan and its ongkir
     */
    public function update(Kecamatan $kecamatan, array $data)
    {
        return DB::transaction(function () use ($kecamatan, $data) {
            $kecamatan->update(['nama' => $data['nama']]);
            
            if (isset($data['ongkir'])) {
                OngkirKecamatan::updateOrCreate(
                    ['kecamatan_id' => $kecamatan->id],
                    ['ongkir' => $data['ongkir']]
                );
            }
            
            return $kecamatan;
        });
    }

    /**
     * Delete a kecamatan (its ongkir should cascade or be deleted manually)
     */
    public function delete(Kecamatan $kecamatan)
    {
        return DB::transaction(function () use ($kecamatan) {
            $kecamatan->ongkir()->delete();
            return $kecamatan->delete();
        });
    }
}
