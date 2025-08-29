<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'no_invoice',
        'cashier_name',
        'transaction_date',
        'total_price',
        'paid_amount',
        'change_amount',
        'payment_method',
    ];
    
    protected $casts = [
        'transaction_date' => 'datetime',
        'total_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];
    
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
    
}
