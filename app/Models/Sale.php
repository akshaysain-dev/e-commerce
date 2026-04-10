<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sale extends Model
{
    protected $fillable = [
        'name',
        'discount',
        'type',
        'scope',
        'scope_id',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'is_active' => 'boolean',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'scope_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'scope_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'scope_id');
    }


    public function isActive(): bool
    {
        return $this->is_active
            && now()->greaterThanOrEqualTo($this->starts_at)
            && now()->lessThanOrEqualTo($this->ends_at);
    }

    public function applyDiscount(float $price): float
    {
        if ($this->type === 'percent') {
            return round($price - ($price * $this->discount / 100), 2);
        }
        return round(max(0, $price - $this->discount), 2);
    }


    public function getDiscountLabelAttribute(): string
    {
        return $this->type === 'percent'
            ? $this->discount . '% OFF'
            : '₹' . number_format($this->discount, 2) . ' OFF';
    }

    
    public function getScopeLabelAttribute(): string
    {
        return match ($this->scope) {
            'category'     => optional($this->category)->name    ?? 'N/A',
            'product_type' => optional($this->productType)->name ?? 'N/A',
            'tag'          => optional($this->tag)->name         ?? 'N/A',  // ← 'tags' → 'tag' fix
            default        => '—',
        };
    }

 
    public function getScopeIconAttribute(): string
    {
        return match ($this->scope) {
            'category'     => '🏷',
            'product_type' => '📦',
            'tag'          => '🔖',
            default        => '📌',
        };
    }



    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('starts_at', '<=', now())
                     ->where('ends_at',   '>=', now());
    }
}