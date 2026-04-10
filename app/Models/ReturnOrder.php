<?php

namespace App\Models;
use App\Models\Order;
use App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    //
	protected $fillable = [
		'order_id', 
		'customer_id', 
		'reason', 
		'bank_name', 
		'account_number', 
		'ifsc_code', 
		'account_holder_name', 
		'status',
		'refund_amount',
		'stripe_refund_id'
	];
	public function order(){
		return $this->belongsTo(Order::class);
	}
	public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
