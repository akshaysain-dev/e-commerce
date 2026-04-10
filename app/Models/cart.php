<?php

namespace App\Models;
use App\Models\ProductVariant;
use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'product_id',
        'product_variant_id',
        'quantity'
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
