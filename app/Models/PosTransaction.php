<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'type',
        'total_amount',
        'payment_method',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(PosTransactionItem::class);
    }
}
