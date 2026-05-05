<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'user_id',
        'shop_name',
        'phone',
        'logo',
        'address',
        'gst_number',
        'pan_number',
        'bank_name',
        'account_number',
        'ifsc_code',
        'commission_rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
