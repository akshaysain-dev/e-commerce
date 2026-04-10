<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'name', 'code', 'discount', 'type',
        'expires_in_days', 'expires_at', 'is_used', 'is_active','generated_for', 'used_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used'    => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function isValid(): bool
    {
        return $this->is_active &&
               !$this->is_used &&
               Carbon::now()->lessThanOrEqualTo($this->expires_at);
    }

    public function applyDiscount(float $total): float
    {
        if ($this->type === 'percent') {
            return $total - ($total * $this->discount / 100);
        }
        return max(0, $total - $this->discount);
    }
    
    public function customer_generated()
    {
        return $this->belongsTo(Customer::class, 'generated_for');
    }

    public function customer_used()
    {
        return $this->belongsTo(Customer::class, 'used_by');
    }
}