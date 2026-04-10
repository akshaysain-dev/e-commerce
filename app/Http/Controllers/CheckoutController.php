<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Customer;
use App\Services\SaleService;
use Stripe\StripeClient;
use App\Models\TaxOrShippingCharge;

class CheckoutController extends Controller
{
    public function __construct(private SaleService $saleService) {}

    public function index()
    {
        $cartItems = Cart::where('customer_id', session('customer_id'))
                        ->with(['product.category', 'variant.attributeValues.attribute'])
                        ->get();

        $saleResult  = $this->saleService->applyToCart($cartItems);
        $subtotal    = $saleResult['discounted_total'];

        // 1. Fetch the tax and shipping settings
        $settings = TaxOrShippingCharge::first();

        // 2. Initialize variables (defaults if no settings exist yet)
        $taxRate        = $settings->tax ?? 0;
        $shippingCharge = $settings->shipping_charge ?? 0;
        $freeShipping   = $settings->max_charge_for_shipping ?? 0;

        // 3. Calculate Shipping (Apply free shipping if subtotal > threshold)
        if ($subtotal >= $freeShipping && $freeShipping > 0) {
            $shippingCharge = 0;
        }

        // 4. Calculate Tax amount
        $taxAmount = ($subtotal * $taxRate) / 100;

        // 5. Final Grand Total
        $totalAmount = $subtotal + $taxAmount + $shippingCharge;

        $customer = Customer::findOrFail(session('customer_id'));

        return view('frontend.checkout', compact(
            'cartItems',
            'totalAmount',
            'subtotal',  
            'taxAmount',  
            'shippingCharge', 
            'saleResult',
            'customer'
        ));
    }

    public function stripeCheckout(Request $request)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        $cartItems = Cart::where('customer_id', session('customer_id'))
                         ->with(['product', 'variant'])
                         ->get();

        $saleResult  = $this->saleService->applyToCart($cartItems);
        $totalAmount = $saleResult['discounted_total'];

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'inr',
                    'product_data' => ['name' => 'Online Store Order'],
                    'unit_amount'  => (int) round($totalAmount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode'          => 'payment',
            'success_url'   => route('order.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'    => route('checkout.index'),
            'customer_email'=> Customer::find(session('customer_id'))->email,
            'metadata'      => ['customer_id' => session('customer_id')],
        ]);

        return redirect($session->url);
    }

    public function razorpayCallback()
    {
        return "Callback function of Razorpay.";
    }
}