<?php
namespace App\Services\Payment;

use Stripe\StripeClient;
use App\Models\Order;
use App\Models\Cart;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Models\Coupon;

class StripeCallbackService
{
    public function __construct(
        private StripeClient $stripe,
        private OrderService $orderService,
    ) {}

    public function handleSuccess(Request $request): mixed
    {
        $paymentIntentId = $request->get('payment_intent');

        $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId, [
            'expand' => ['payment_method'],
        ]);

        if ($paymentIntent->status !== 'succeeded') {
            return redirect()->route('checkout.index')
                             ->with('error', 'Payment was not completed.');
        }

        $customerId     = $paymentIntent->metadata->customer_id;
        $address        = $paymentIntent->metadata->address;
        $tempOrderId    = $paymentIntent->metadata->temp_order_id;
        $transactionId  = $paymentIntent->id;
        $cardHolderName = $paymentIntent->payment_method->billing_details->name ?? null;
        $cardLastFour   = $paymentIntent->payment_method->card->last4 ?? null;
        $totalAmount    = $paymentIntent->amount_received / 100;

        // Duplicate check
        $existing = Order::where('unique_order_id', $tempOrderId)->first();
        if ($existing) {
            return redirect()->route('order.success')->with('success', 'Order already placed!');
        }

        $cartItems = Cart::with(['product', 'variant'])
                         ->where('customer_id', $customerId)
                         ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('order.success')->with('success', 'Order already processed!');
        }

		$pendingOrder = session('pending_order');
		
        $order = $this->orderService->createOrder([
            'customer_id'      => $customerId,
            'temp_order_id'    => $tempOrderId,
            'total_amount'     => $totalAmount,
            'status'           => 'paid',
            'payment_method'   => 'STRIPE',
            'address'          => $address,
            'transaction_id'   => $transactionId,
            'card_holder_name' => $cardHolderName,
            'card_last_four'   => $cardLastFour ?? '1111',
			'coupon_code'      => $pendingOrder['coupon_code'],
			'discount_amount'  => $pendingOrder['discount_amount'],
        ], $cartItems);

        //session()->forget('pending_order');

        $this->orderService->sendNotification($customerId, $order->id, $tempOrderId);

        if (!empty($pendingOrder['coupon_code'])) {
            Coupon::where('code', $pendingOrder['coupon_code'])
                ->update(['is_used' => true,'used_by' => $customerId]);
        }

        session()->forget(['pending_order', 'applied_coupon']);

        return redirect()->route('order.success')->with([
            'success'        => 'Payment successful! Order placed.',
            'order_id'       => $order->unique_order_id,
            'total_amount'   => $order->total_amount,
            'payment_method' => 'STRIPE',
            'transaction_id' => $order->transaction_id,
        ]);
    }
}