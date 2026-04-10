<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Customer;
use App\Services\CartService;
use App\Services\AddressResolver;
use App\Services\SaleService;
use App\Services\Payment\PaymentGatewayFactory;
use App\Services\Payment\StripeCallbackService;
use App\Services\Payment\PayPalCallbackService;
use Stripe\StripeClient;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\TaxOrShippingCharge;

class OrderController extends Controller
{
    public function __construct(
        private CartService             $cartService,
        private AddressResolver         $addressResolver,
        private SaleService             $saleService,
        private PaymentGatewayFactory   $paymentFactory,
        private StripeCallbackService   $stripeCallback,
        private PayPalCallbackService   $paypalCallback,
    ) {}

   public function placeOrder(Request $request)
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            return back()->with('error', 'Please login again.');
        }

        $cartItems = $this->cartService->getItems($customerId);

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $address = $this->addressResolver->resolve($request, $customerId);

        // ── Step 1: Sale apply ─────────────────────
        $saleResult  = $this->saleService->applyToCart($cartItems);
        $subtotal    = $saleResult['discounted_total']; // Using this as the base for tax/shipping

        if ($subtotal <= 0) {
            return back()->with('error', 'Cart total is invalid.');
        }

        // ── Step 2: Coupon apply ────────────
        $discountAmount = 0;
        $appliedCoupon  = null;
        $couponCode     = session('applied_coupon') ?? $request->input('coupon_code');

        if ($couponCode) {
            $coupon = Coupon::where('code', strtoupper($couponCode))->first();
            if ($coupon && $coupon->isValid()) {
                $discountedSubtotal = $coupon->applyDiscount($subtotal);
                $discountAmount     = $subtotal - $discountedSubtotal;
                $subtotal           = $discountedSubtotal;
                $appliedCoupon      = $coupon->code;
            }
        }

        // ── Step 3: Apply Tax & Shipping ─────────────────────
        $settings = TaxOrShippingCharge::first();
        
        $taxRate        = $settings->tax ?? 0;
        $shippingCharge = $settings->shipping_charge ?? 0;
        $freeThreshold  = $settings->max_charge_for_shipping ?? 0;

        // Check for Free Shipping
        if ($subtotal >= $freeThreshold && $freeThreshold > 0) {
            $shippingCharge = 0;
        }

        // Calculate Tax
        $taxAmount   = ($subtotal * $taxRate) / 100;
        $totalAmount = $subtotal + $taxAmount + $shippingCharge;

        // ── Step 4: Finalize Pending Order ───────────────────
        $tempOrderId  = 'ORD-' . strtoupper(uniqid());
        $pendingOrder = [
            'customer_id'     => $customerId,
            'address'         => $address,
            'subtotal'        => $subtotal, 
            'tax_amount'      => $taxAmount,    
            'shipping_amount' => $shippingCharge, 
            'total_amount'    => $totalAmount,  
            'discount_amount' => $discountAmount,
            'coupon_code'     => $appliedCoupon,
            'temp_order_id'   => $tempOrderId,
        ];

        session(['pending_order' => $pendingOrder]);

        try {
            $gateway = $this->paymentFactory->make($request->payment_method);
            return $gateway->initiate($pendingOrder, $cartItems);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', 'Invalid payment method selected.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function stripeSuccess(Request $request)
    {
        try {
            return $this->stripeCallback->handleSuccess($request);
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function paypalSuccess(Request $request)
    {
        try {
            return $this->paypalCallback->handleSuccess($request);
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function admin_orders(Request $request)
    {
        $query = Order::with([
            'customer',
            'orderItems.product',
            'orderItems.variant.attributeValues.attribute',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('admin.orders.all-orders', [
            'orders'       => $query->paginate(10)->withQueryString(),
            'pendingCount' => Order::where('status', 'pending')->count(),
            'totalCount'   => Order::count(),
        ]);
    }

    public function update_order_status(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled',
        ]);

	if($request->status == 'delivered'){
		$order->update([
			'status' => $request->status, 
			'delivery_date' => now()
		]);
	}
	else{
        $order->update(['status' => $request->status]);
	}

        return back()->with('success', 'Order #' . $order->unique_order_id . ' updated to "' . ucfirst(str_replace('_', ' ', $request->status)) . '".');
    }

	public function cancel_order($id, Request $request)
	{
		$request->validate([
			'payment_method' => 'required',
		]);

		$method = $request->payment_method;
		$order = Order::where('customer_id', session('customer_id'))
					  ->where('id', $id)
					  ->firstOrFail();

		if ($method == "COD") {
			if ($order->status === 'pending') {
				$order->update(['status' => 'cancelled']);
				return back()->with('success', 'Order has been cancelled successfully.');
			}
		} 
		
		elseif ($method == "STRIPE") {
			if ($order->status === 'pending') {
				try {
					$stripe = new StripeClient(config('services.stripe.secret'));

					$stripe->refunds->create([
						'payment_intent' => $order->transaction_id,
					]);

					$order->update(['status' => 'cancelled']);
					
					return back()->with('success', 'Order cancelled and payment refunded via Stripe.');
				} catch (\Exception $e) {
					return back()->with('error', 'Stripe Error: ' . $e->getMessage());
				}
			}
			else{
				try {
					$amount = $order->total_amount;
					$refunded_amount = (round($amount * 0.97, 2))*100; 
					$stripe = new StripeClient(config('services.stripe.secret'));

					$stripe->refunds->create([
						'payment_intent' => $order->transaction_id,
						'amount'         => $refunded_amount,
					]);

					$order->update(['status' => 'cancelled']);
					
					return back()->with('success', 'Order cancelled and payment refunded via Stripe.');
				} catch (\Exception $e) {
					return back()->with('error', 'Stripe Error: ' . $e->getMessage());
				}
			}
		}

		return back()->with('error', 'This order cannot be cancelled at this stage.');
	}
	
	public function downloadInvoice($order_id)
	{
		$order = Order::where('customer_id', session('customer_id'))->with(['orderItems.product', 'orderItems.variant.attributeValues.attribute'])->findOrFail($order_id);
		$customer = Customer::findOrFail(session('customer_id'));

		$pdf = Pdf::loadView('frontend.invoice_pdf', compact('order','customer'));

		return $pdf->download('Invoice-'.$order->unique_order_id.'.pdf');
	}
}