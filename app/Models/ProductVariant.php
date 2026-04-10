<?php

namespace App\Models;
use App\Models\Product;
use App\Models\AttributeValue;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    //
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $fillable = [
        'product_id', 
        'name', 
        'price', 
        'stock', 
        'sku',
        'attribute_id',
		'margin_price',
    ];

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }

    public function attributeValues() 
    {
        return $this->belongsToMany(
            AttributeValue::class, 
            'attribute_value_product_variant', 
            'variant_id', 
            'attribute_value_id'
        );
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
