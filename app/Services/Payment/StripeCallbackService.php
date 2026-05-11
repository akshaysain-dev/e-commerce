<?php

namespace App\Services\Payment;

use Stripe\StripeClient;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Coupon;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StripeCallbackService
{
    public function __construct(
        private StripeClient $stripe,
        private OrderService $orderService,
    ) {}

    public function handleSuccess(Request $request): mixed
    {
        $paymentIntentId = $request->get('payment_intent');

        if (!$paymentIntentId) {

            return redirect()
                ->route('checkout.index')
                ->with('error', 'Invalid payment request.');
        }

        $paymentIntent = $this->stripe->paymentIntents->retrieve(
            $paymentIntentId,
            [
                'expand' => ['payment_method'],
            ]
        );

        if ($paymentIntent->status !== 'succeeded') {

            return redirect()
                ->route('checkout.index')
                ->with('error', 'Payment was not completed.');
        }

        $customerId = $paymentIntent->metadata->customer_id ?? null;

        $address = $paymentIntent->metadata->address ?? null;

        $tempOrderId = $paymentIntent->metadata->temp_order_id ?? null;

        $transactionId = $paymentIntent->id;

        $cardHolderName =
            $paymentIntent->payment_method->billing_details->name
            ?? null;

        $cardLastFour =
            $paymentIntent->payment_method->card->last4
            ?? null;

        $totalAmount = $paymentIntent->amount_received / 100;

        $existingOrder = Order::where(
            'unique_order_id',
            $tempOrderId
        )->first();

        if ($existingOrder) {

            return redirect()
                ->route('order.success')
                ->with('success', 'Order already placed!');
        }

        $cartItems = Cart::with([
                'product',
                'variant',
            ])
            ->where('customer_id', $customerId)
            ->get();

        if ($cartItems->isEmpty()) {

            return redirect()
                ->route('order.success')
                ->with('success', 'Order already processed!');
        }

        $pendingOrder = session('pending_order', []);

        DB::beginTransaction();

        try {

            Log::info('Stripe payment success callback', [

                'payment_intent' => $paymentIntentId,

                'customer_id' => $customerId,

                'cart_count' => $cartItems->count(),

                'temp_order_id' => $tempOrderId,
            ]);

            $order = $this->orderService->createOrder(

                [

                    'customer_id' => $customerId,

                    'temp_order_id' => $tempOrderId,

                    'total_amount' => $totalAmount,

                    'status' => 'paid',

                    'payment_method' => 'STRIPE',

                    'address' => $address,

                    'transaction_id' => $transactionId,

                    'card_holder_name' => $cardHolderName,

                    'card_last_four' => $cardLastFour ?? '1111',

                    'coupon_code' =>
                        $pendingOrder['coupon_code']
                        ?? null,

                    'discount_amount' =>
                        $pendingOrder['discount_amount']
                        ?? 0,

                    'admin_earning' =>
                        $pendingOrder['admin_earning']
                        ?? 0,

                    'vendor_earning' =>
                        $pendingOrder['vendor_earning']
                        ?? 0,

                    'stripe_transfer_group' =>
                        $pendingOrder['stripe_transfer_group']
                        ?? null,

                ],

                $cartItems
            );

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Stripe callback failed', [

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),
            ]);

            throw $e;
        }

        $this->orderService->sendNotification(
            $customerId,
            $order->id,
            $tempOrderId
        );

        if (!empty($pendingOrder['coupon_code'])) {

            Coupon::where(
                'code',
                $pendingOrder['coupon_code']
            )->update([

                'is_used' => true,

                'used_by' => $customerId,
            ]);
        }

        session()->forget([
            'pending_order',
            'applied_coupon',
        ]);

        return redirect()
            ->route('order.success')
            ->with([

                'success' =>
                    'Payment successful! Order placed.',

                'order_id' =>
                    $order->unique_order_id,

                'total_amount' =>
                    $order->total_amount,

                'payment_method' =>
                    'STRIPE',

                'transaction_id' =>
                    $order->transaction_id,
            ]);
    }
}