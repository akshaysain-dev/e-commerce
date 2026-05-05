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

class OrderService
{
    public function createOrder(array $data, Collection $cartItems): Order
    {
        $order = null;

        DB::transaction(function () use ($data, $cartItems, &$order) {

            $order = Order::create([
                'customer_id'      => $data['customer_id'],
                'unique_order_id'  => $data['temp_order_id'],
                'total_amount'     => $data['total_amount'],
                'status'           => $data['status']           ?? 'pending',
                'payment_method'   => $data['payment_method'],
                'address'          => $data['address'],
                'transaction_id'   => $data['transaction_id']   ?? null,
                'card_holder_name' => $data['card_holder_name'] ?? null,
                'card_last_four'   => $data['card_last_four']   ?? null,
				'coupon_code'      => $data['coupon_code'] ?? null,
				'discount_amount'  => $data['discount_amount'] ?? '0',
            ]);

            foreach ($cartItems as $item) {
                $variant = ProductVariant::findOrFail($item->product_variant_id);

                if ($variant->stock < $item->quantity) {
                    throw new \Exception("Insufficient stock for: {$variant->name}");
                }

                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity'           => $item->quantity,
                    'price'              => $item->variant->margin_price,
                    'main_price'         => $item->variant->price,
                ]);

                $variant->decrement('stock', $item->quantity);
            }

            Cart::where('customer_id', $data['customer_id'])->delete();
        });

        $customer = Customer::find($data['customer_id']);

        if ($customer && $customer->email) {
            Mail::to($customer->email)->send(new OrderPlacedMail($order));
        }

        return $order;
    }

    public function sendNotification(int $customerId, int $orderId, $tempOrderId): void
    {
        Notification::create([
            'customer_id' => $customerId,
            'title'       => 'New Order Placed',
            'message'     => 'Your order #' . $tempOrderId . ' has been successfully placed.',
        ]);
    }
}