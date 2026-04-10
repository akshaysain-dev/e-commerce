<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\orderItem;
use App\Models\customer;


class order extends Model
{
    protected static function booted()
    {
        /* static::created(function ($order) {
            $userName = strtoupper(substr(str_replace(' ', '', $order->user->name), 0, 5));
            $order->unique_order_id = "ORDR-{$userName}-{$order->id}";
            $order->save();
        }); */
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function items() {
        return $this->hasMany(orderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected $fillable = [
        'customer_id',
        'unique_order_id',
        'total_amount',
        'status',
        'payment_method',
        'address',
        'transaction_id',
        'card_holder_name',
        'card_last_four',
        'coupon_code',      
        'discount_amount',
		'delivery_date',
    ];

}
