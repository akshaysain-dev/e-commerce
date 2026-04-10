<?php

namespace App\Models;
use App\Models\Category;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\Rating;
use App\Models\ProductType;
use App\Models\Tag;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'category_id','user_id','name','description','status','image','images','product_type_id','refunded_amount',
    ];

    protected $casts = [
        'images' => 'array',
        'status' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function firstVariant()
    {
        return $this->hasOne(ProductVariant::class)->latestOfMany();
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function isWishlisted()
    {
        if (session()->has('customer_id')) {
            return Wishlist::where('customer_id', session('customer_id'))
                           ->where('product_id', $this->id)
                           ->exists();
        }
        
        return false;
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function type() {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
}
