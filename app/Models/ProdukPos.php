<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukPos extends Model
{
    use HasFactory;

    protected $table = 'produk_pos';

    protected $fillable = [
        'sku',
        'nama_produk',
        'harga',
        'kategori',
        'deskripsi',
        'gambar',
    ];
}
