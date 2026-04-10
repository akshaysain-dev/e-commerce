<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Product;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'color'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            $tag->slug = Str::slug($tag->name);
        });
    }
}