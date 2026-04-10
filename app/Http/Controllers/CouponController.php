<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Cart;
use App\Services\SaleService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code'  => 'required|string',
            'total' => 'required|numeric',
        ]);

        $saleService = app(SaleService::class);
        $cartItems   = Cart::with(['product.category', 'product'])
                           ->where('customer_id', session('customer_id'))
                           ->get();

        foreach ($cartItems as $item) {
            if ($saleService->getActiveSaleForProduct($item->product)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon cannot be applied when items are already on sale.',
                ]);
            }
        }

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code.',
            ]);
        }

        if ($coupon->generated_for) {
            if ($coupon->generated_for !== session('customer_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'This Coupon is not For you.',
                ]);
            }
        }

        $discountedTotal = $coupon->applyDiscount((float) $request->total);
        $savedAmount     = $request->total - $discountedTotal;

        session(['applied_coupon' => $coupon->code]);

        return response()->json([
            'success'          => true,
            'message'          => 'Coupon applied successfully!',
            'original_total'   => (float) $request->total,
            'discounted_total' => round($discountedTotal, 2),
            'saved_amount'     => round($savedAmount, 2),
            'discount_info'    => $coupon->type === 'percent'
                                    ? $coupon->discount . '% off'
                                    : '₹' . $coupon->discount . ' off',
        ]);
    }

    public function remove()
    {
        session()->forget('applied_coupon');
        return response()->json(['success' => true, 'message' => 'Coupon removed.']);
    }
}