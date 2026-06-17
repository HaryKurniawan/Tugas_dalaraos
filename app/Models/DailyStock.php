<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_pos_id',
        'tanggal',
        'stok_awal',
        'stok_terjual',
        'stok_sisa',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function produkPos()
    {
        return $this->belongsTo(ProdukPos::class, 'produk_pos_id');
    }

    public function scopeToday($query)
    {
        return $query->where('tanggal', today()->toDateString());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }
}
