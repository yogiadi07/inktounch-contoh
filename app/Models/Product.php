<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
    'category_id',
    'purchase_price',
    'selling_price',
    'stock',
    'sku',
    'unit', // ← tambahkan ini
    'image',
];


public function category()
{
return $this->belongsTo(Category::class);
}
}
