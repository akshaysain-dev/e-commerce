<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\Notification;
use App\Services\SaleService;
use App\Models\GuestCart;
use App\Models\TaxOrShippingCharge;

class cartController extends Controller
{
    public function __construct(private SaleService $saleService) {}

    public function addToCart(Request $request, $product_id, $variant_id)
    {
        $variant = ProductVariant::findOrFail($variant_id);
        $quantity = $request->query('quantity', 1);

        // 1. Stock Check (Always happens first)
        if ($variant->stock < $quantity) {
            return back()->with('error', 'Only ' . $variant->stock . ' units left in stock.');
        }

        $customerId = session('customer_id');

        if ($customerId) {
            // --- LOGGED IN USER LOGIC (Uses your 'carts' table) ---
            $cartItem = Cart::where('customer_id', $customerId)
                            ->where('product_variant_id', $variant_id)
                            ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $quantity);
            } else {
                Cart::create([
                    'customer_id'        => $customerId,
                    'product_variant_id' => $variant_id,
                    'product_id'         => $product_id,
                    'quantity'           => $quantity,
                ]);
                
                Notification::create([
                    'customer_id' => $customerId,
                    'title'       => 'New Product Added in Cart',
                    'message'     => 'New product Added in Cart for processing in checkout.',
                ]);
            }
        } else {
            // --- GUEST USER LOGIC (Uses a 'guest_carts' table with IP) ---
            $guestItem = GuestCart::where('ip_address', $request->ip())
                                ->where('product_variant_id', $variant_id)
                                ->first();

            if ($guestItem) {
                $guestItem->increment('quantity', $quantity);
            } else {
                GuestCart::create([
                    'ip_address'         => $request->ip(),
                    'product_id'         => $product_id,
                    'product_variant_id' => $variant_id,
                    'quantity'           => $quantity,
                ]);
            }
        }

        return redirect()->route('cart_index')->with('success', 'Item added to cart!');
    }


    public function index(Request $request)
    {
        $tax_shipping = TaxOrShippingCharge::first();
        $customerId = session('customer_id');

        if ($customerId) {
            $cartItems = Cart::with(['product.category', 'variant.attributeValues.attribute'])
                ->where('customer_id', $customerId)
                ->get();
        } else {
            $cartItems = GuestCart::with(['product.category', 'variant.attributeValues.attribute'])
                ->where('ip_address', $request->ip())
                ->get();
        }

        $grandTotal    = 0;
        $originalTotal = 0;
        $totalSaved    = 0;
        $itemsWithSale = [];

        foreach ($cartItems as $item) {

            $variant       = $item->variant; 
            $originalPrice = (float) $variant->margin_price;
            $qty           = (int) $item->quantity;

            $itemOriginal  = $originalPrice * $qty;

            $sale = $this->saleService->getActiveSaleForProduct($item->product);

            if ($sale) {
                $discountedPrice = $sale->applyDiscount($originalPrice);
                $itemDiscounted  = round($discountedPrice * $qty, 2);
                $itemSaved       = round($itemOriginal - $itemDiscounted, 2);
            } else {
                $discountedPrice = $originalPrice;
                $itemDiscounted  = $itemOriginal;
                $itemSaved       = 0;
            }

            $originalTotal += $itemOriginal;
            $grandTotal    += $itemDiscounted;
            $totalSaved    += $itemSaved;

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

        // =========================
        // ✅ TAX CALCULATION (18%)
        // =========================
        $taxRate = $tax_shipping->tax;
        $tax = ($grandTotal * $taxRate) / 100;

        // =========================
        // ✅ SHIPPING
        // =========================
        $shipping = $grandTotal > $tax_shipping->max_charge_for_shipping ? 0 : 50;

        // =========================
        // ✅ FINAL TOTAL
        // =========================
        $finalTotal = $grandTotal + $tax + $shipping;

        return view('frontend.cart', [
            'cartItems'     => $cartItems,
            'itemsWithSale' => $itemsWithSale,
            'grandTotal'    => round($grandTotal, 2),
            'originalTotal' => round($originalTotal, 2),
            'totalSaved'    => round($totalSaved, 2),

            // ✅ NEW VALUES
            'tax'           => round($tax, 2),
            'shipping'      => $shipping,
            'finalTotal'    => round($finalTotal, 2),
        ]);
    }


    public function destroy(Request $request, $id)
    {
        if(session('customer_id')){
            $cartItem = Cart::where('id', $id)
            ->where('customer_id', session('customer_id'))
            ->first();

            if (!$cartItem) {
                return back()->with('error', 'Item not found or already removed.');
            }

            $cartItem->delete();
        }
        else{
            $cartItem = GuestCart::where('id', $id)
            ->where('ip_address', $request->ip())
            ->first();
            if (!$cartItem) {
                return back()->with('error', 'Item not found or already removed.');
            }

            $cartItem->delete();
        }
        

        return back()->with('success', 'Item removed from your cart.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        if(session('customer_id')){
            $cartItem = Cart::where('id', $id)
            ->where('customer_id', session('customer_id'))
            ->firstOrFail();

            if ($request->quantity > $cartItem->variant->stock) {
                return back()->with('error', 'Only ' . $cartItem->variant->stock . ' items are available in stock.');
            }
        }
        else{
            $cartItem = GuestCart::where('id', $id)->where('ip_address', $request->ip())->firstOrFail();
            if ($request->quantity > $cartItem->variant->stock) {
                return back()->with('error', 'Only ' . $cartItem->variant->stock . ' items are available in stock.');
            }
        }
        

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Quantity updated!');
    }
}