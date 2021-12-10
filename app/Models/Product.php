<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_id',
        'dealer',
        'category_name',
        'brand',
        'product_name',
        'product_id',
        'ean',
        'description',
        'new_price',
        'gl_price',
        'freight',
        'stock_number',
        'image_url',
        'item_url'
    ];
}
