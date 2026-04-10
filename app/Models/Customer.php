<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\order;
use App\Models\Notification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    //
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'area',
        'city',
        'state',
        'country',
        'postal_code',
        'status',
        'google_id',
        'avatar',
    ];
    public function orders() {
        return $this->hasMany(order::class);
    }

    public function notifications() {
        return $this->hasMany(Notification::class, 'customer_id');
    }
}
