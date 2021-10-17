<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'cart_id',
        'product_id',
        'product_amount',
        'created_at',
        'updated_at',
    ];

    public function carts(){
        return $this->belongsTo(Cart::class);
    }

    public function products(){
        return $this->belongsTo(Product::class);
    }
}
