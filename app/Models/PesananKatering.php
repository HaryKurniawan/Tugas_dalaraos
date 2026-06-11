<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PesananKatering extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'nama_pelanggan',
        'no_telpon',
        'tanggal_acara',
        'jumlah_porsi',
        'alamat',
        'kecamatan',
        'menu',
        'menu_id',
        'total',
        'ongkir',
        'metode_kirim',
        'catatan',
        'sarikaya_qty',
        'puding_coklat_qty',
        'jeruk_qty',
        'pisang_qty',
        'bukti_transfer',
        'status_pembayaran',
        'catatan_admin',
        'status_pesanan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = 'ORD-' . strtoupper(Str::random(6));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu_rel()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
