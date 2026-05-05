<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\cart;
use App\Models\order;
use App\Models\ProductVariant;
use App\Models\Product;

class orderItem extends Model
{
    public function order() {
        return $this->belongsTo(order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function variant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'price',
        'main_price'
    ];
}
