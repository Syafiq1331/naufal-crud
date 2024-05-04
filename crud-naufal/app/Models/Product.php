<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'price_discount',
        'size',
        'stock',
        'thumbnail',
        'status',
        'kode_product',
        'purchase_price'
    ];

    public function photos()
    {
        return $this->hasMany(ProductPhotos::class);
    }
}
