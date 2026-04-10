<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Tag;

class SaleService
{
    public function getActiveSaleForProduct($product): ?Sale
    {
        $tagIds = $product->relationLoaded('tags')
                ? $product->tags->pluck('id')
                : $product->tags()->pluck('tags.id');

        return Sale::active()
            ->where(function ($q) use ($product, $tagIds) {

                $q->where(function ($q2) use ($product) {
                    $q2->where('scope', 'category')
                       ->where('scope_id', $product->category_id);
                })
                ->orWhere(function ($q2) use ($product) {
                    $q2->where('scope', 'product_type')
                       ->where('scope_id', $product->product_type_id);
                });

                if ($tagIds->isNotEmpty()) {
                    $q->orWhere(function ($q2) use ($tagIds) {
                        $q2->where('scope', 'tag')
                           ->whereIn('scope_id', $tagIds);
                    });
                }
            })
            ->orderByDesc('discount')
            ->first();
    }

    
    public function applyToCart($cartItems): array
    {
        $originalTotal   = 0;
        $discountedTotal = 0;
        $savedAmount     = 0;
        $itemsWithSale   = [];

        foreach ($cartItems as $item) {
            $originalPrice = (float) $item->variant->margin_price;
            $qty           = (int)   $item->quantity;
            $itemOriginal  = $originalPrice * $qty;

            $sale = $this->getActiveSaleForProduct($item->product);

            if ($sale) {
                $discountedPrice = $sale->applyDiscount($originalPrice);
                $itemDiscounted  = round($discountedPrice * $qty, 2);
                $itemSaved       = round($itemOriginal - $itemDiscounted, 2);
            } else {
                $discountedPrice = $originalPrice;
                $itemDiscounted  = $itemOriginal;
                $itemSaved       = 0;
            }

            $originalTotal   += $itemOriginal;
            $discountedTotal += $itemDiscounted;
            $savedAmount     += $itemSaved;

            $itemsWithSale[] = [
                'item'             => $item,
                'original_price'   => $originalPrice,
                'discounted_price' => $discountedPrice,
                'item_original'    => $itemOriginal,
                'item_discounted'  => $itemDiscounted,
                'sale'             => $sale,
                'saved'            => $itemSaved,
                'has_sale'         => $sale !== null,
            ];
        }

        return [
            'original_total'   => round($originalTotal, 2),
            'discounted_total' => round($discountedTotal, 2),
            'saved_amount'     => round($savedAmount, 2),
            'has_any_sale'     => $savedAmount > 0,
            'items'            => $itemsWithSale,
        ];
    }

    public function attachSalesToProducts($products): void
    {
        foreach ($products as $product) {
            $sale      = $this->getActiveSaleForProduct($product);
            $basePrice = $product->variants->min('margin_price') ?? 0;

            $product->active_sale      = $sale;
            $product->base_price       = $basePrice;
            $product->discounted_price = $sale ? $sale->applyDiscount($basePrice) : null;
            $product->sale_badge       = $sale ? $sale->discount_label : null;
        }
    }
}