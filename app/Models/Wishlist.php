<?php

namespace App\Models;
use App\Models\product;
use App\Models\Customer;
use App\Models\ProductVariant;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['customer_id', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
	
	public function firstVariant()
    {
        return $this->hasOne(ProductVariant::class)->latestOfMany();
    }
}