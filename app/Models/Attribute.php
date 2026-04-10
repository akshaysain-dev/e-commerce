<?php

namespace App\Models;
use App\Models\AttributeValue;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    //
    protected $fillable = [
        'name',
    ];
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
