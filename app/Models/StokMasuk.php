<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe_barang',
        'barang_id',
        'nama_barang',
        'jumlah',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
