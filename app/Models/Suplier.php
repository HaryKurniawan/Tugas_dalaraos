<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suplier extends Model
{
    use HasFactory;

    protected $fillable = ['nama_suplier', 'kontak', 'alamat', 'tipe'];

    public function stok_barang_kerings()
    {
        return $this->hasMany(StokBarangKering::class);
    }
}
