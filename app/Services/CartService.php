<?php
namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Collection;

class CartService
{
    public function getItems(int $customerId): Collection
    {
        return Cart::with(['product', 'variant'])
                   ->where('customer_id', $customerId)
                   ->get();
    }

    public function getTotal(Collection $items): float
    {
        return $items->sum(fn($item) => $item->variant->price * $item->quantity);
    }
}