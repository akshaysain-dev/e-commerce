<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = ['customer_id', 'email', 'otp', 'otp_for', 'expires_at'];
}
