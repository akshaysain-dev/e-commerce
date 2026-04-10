<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
use App\Models\Product;

class GuestCart extends Model
{
    //
    protected $fillable = ['ip_address', 'product_id', 'product_variant_id', 'quantity'];

    
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
