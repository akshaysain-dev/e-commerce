<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    //
    protected $fillable = [
        'customer_id', 
        'product_id', 
        'rating', 
        'review', 
        'title',
        'helpful_yes',
        'helpful_no',
        'is_verified_purchase',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
