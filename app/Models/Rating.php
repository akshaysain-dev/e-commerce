<?php

namespace App\Models;
use App\Models\Product;
use App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    //
    protected $fillable = [
        'customer_id', 
        'product_id', 
        'rating', 
        'review', 
        'status',
        'title',
        'helpful_yes',
        'helpful_no',
        'is_verified_purchase',
        'images', // ✅ ADD THIS
    ];

    protected $casts = [
        'images' => 'array',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function replies()
    {
        return $this->hasMany(ReviewReply::class)->latest();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
