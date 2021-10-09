<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_name',
        'product_link',
        'product_price',
        'product_percent',
        'product_describe',
        'category_id',
    ];

    public function collections(){
        return $this->hasMany(Collection::class,'product_id','id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
