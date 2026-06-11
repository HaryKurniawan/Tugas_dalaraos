<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosTransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pos_transaction_id',
        'item_type',
        'item_id',
        'item_name',
        'qty',
        'price_per_item',
        'subtotal',
    ];

    public function transaction()
    {
        return $this->belongsTo(PosTransaction::class, 'pos_transaction_id');
    }
}
