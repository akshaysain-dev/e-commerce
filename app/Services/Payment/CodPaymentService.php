<?php
namespace App\Services\Payment;

use App\Services\OrderService;
use Illuminate\Support\Collection;
use App\Models\Coupon;


class CodPaymentService implements PaymentGatewayInterface
{
    public function __construct(private OrderService $orderService) {}

    public function initiate(array $pendingOrder, Collection $cartItems): mixed
    {
        $order = $this->orderService->createOrder([
            ...$pendingOrder,
            'status'         => 'pending',
            'payment_method' => 'COD',
        ], $cartItems);

        $this->orderService->sendNotification($pendingOrder['customer_id'], $order->id, $pendingOrder['temp_order_id']);

        $pendingOrder = session('pending_order');

        if (!empty($pendingOrder['coupon_code'])) {
            Coupon::where('code', $pendingOrder['coupon_code'])
                ->update(['is_used' => true, 'used_by' => $pendingOrder['customer_id']]);
        }

        session()->forget(['pending_order', 'applied_coupon']);

        return redirect()->route('order.success')->with('success', 'Order placed successfully!');
    }
}