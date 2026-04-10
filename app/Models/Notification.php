<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'title',
        'message',
        'is_read',
    ];
}
