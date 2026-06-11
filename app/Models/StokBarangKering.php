<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBarangKering extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'nama_barang',
        'suplier_id',
        'jumlah_stok',
        'satuan',
        'tanggal_expired',
        'lokasi_penyimpanan',
        'harga_beli',
        'harga_jual'
    ];

    public function suplier()
    {
        return $this->belongsTo(Suplier::class);
    }
}
