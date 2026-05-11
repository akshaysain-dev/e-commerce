<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Product;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        private StripeClient $stripe
    ) {}

    public function createOrder(array $data, Collection $cartItems): Order
    {
        $order = null;

        DB::transaction(function () use ($data, $cartItems, &$order) {

            $order = Order::create([

                'customer_id' =>
                    $data['customer_id'],

                'unique_order_id' =>
                    $data['temp_order_id'],

                'total_amount' =>
                    $data['total_amount'],

                'status' =>
                    $data['status'] ?? 'pending',

                'payment_method' =>
                    $data['payment_method'],

                'address' =>
                    $data['address'],

                'transaction_id' =>
                    $data['transaction_id'] ?? null,

                'card_holder_name' =>
                    $data['card_holder_name'] ?? null,

                'card_last_four' =>
                    $data['card_last_four'] ?? null,

                'coupon_code' =>
                    $data['coupon_code'] ?? null,

                'discount_amount' =>
                    $data['discount_amount'] ?? 0,

                'admin_earning' => 0,

                'vendor_earning' => 0,

                'stripe_transfer_group' =>
                    'ORDER_' .
                    $data['temp_order_id'],
            ]);

            foreach ($cartItems as $item) {

                $variant = ProductVariant::findOrFail(
                    $item->product_variant_id
                );

                if (
                    $variant->stock <
                    $item->quantity
                ) {

                    throw new \Exception(
                        "Insufficient stock for variant ID: {$variant->id}"
                    );
                }

                $product = Product::find(
                    $item->product_id
                );

                if (!$product) {

                    throw new \Exception(
                        "Product not found ID: {$item->product_id}"
                    );
                }

                $vendor = Vendor::where(
                    'user_id',
                    $product->user_id
                )->first();

                Log::info('Vendor Fetch', [

                    'product_id' =>
                        $product->id,

                    'product_user_id' =>
                        $product->user_id,

                    'vendor_found' =>
                        $vendor ? true : false,

                    'vendor_id' =>
                        $vendor?->id,
                ]);

                $commissionRate =
                    $vendor?->commission_rate ?? 0;

                $itemTotal =
                    $variant->margin_price *
                    $item->quantity;

                $adminCommission =
                    ($itemTotal * $commissionRate) / 100;

                $vendorAmount =
                    $itemTotal - $adminCommission;

                OrderItem::create([

                    'order_id' =>
                        $order->id,

                    'product_id' =>
                        $product->id,

                    'vendor_id' =>
                        $vendor?->id,

                    'product_variant_id' =>
                        $variant->id,

                    'quantity' =>
                        $item->quantity,

                    'price' =>
                        $variant->margin_price,

                    'main_price' =>
                        $variant->price,

                    'admin_commission' =>
                        round(
                            $adminCommission,
                            2
                        ),

                    'vendor_amount' =>
                        round(
                            $vendorAmount,
                            2
                        ),

                    'payout_status' =>
                        'paid',
                ]);

                $order->admin_earning +=
                    $adminCommission;

                $order->vendor_earning +=
                    $vendorAmount;

                $variant->decrement(
                    'stock',
                    $item->quantity
                );
            }

            $order->save();

            Cart::where(
                'customer_id',
                $data['customer_id']
            )->delete();
        });

        $customer = Customer::find(
            $data['customer_id']
        );

        if (
            $customer &&
            $customer->email
        ) {

            Mail::to($customer->email)
                ->send(
                    new OrderPlacedMail($order)
                );
        }

        return $order;
    }

    public function sendNotification(
        int $customerId,
        int $orderId,
        $tempOrderId
    ): void {

        Notification::create([

            'customer_id' =>
                $customerId,

            'title' =>
                'New Order Placed',

            'message' =>
                'Your order #' .
                $tempOrderId .
                ' has been successfully placed.',
        ]);
    }
}