<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    protected $fillable = [
        'rating_id',
        'customer_id',
        'admin_id',
        'body',
        'author_name',
        'is_seller'
    ];

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }
}