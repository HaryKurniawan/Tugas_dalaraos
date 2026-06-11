<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'nama_menu',
        'harga',
        'deskripsi',
        'kategori',
        'badge',
        'isi_paket',
        'gambar',
    ];

    protected $casts = [
        'isi_paket' => 'array',
    ];
}
