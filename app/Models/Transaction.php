<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'no_invoice',
        'transaction_date',
        'total_price',
        'paid_amount',
        'change_amount',
        'payment_method',
    ];
    
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
    
}
