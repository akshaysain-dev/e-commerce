<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ProductType;

class Margin extends Model
{
    protected $fillable = ['type_id', 'name', 'percentage'];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }
	public function percentage() {
		return $this->hasMany(...); 
	}

}
