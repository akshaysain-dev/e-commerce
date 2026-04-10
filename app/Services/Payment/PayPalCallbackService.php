<?php
namespace App\Services\Payment;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Order;
use App\Models\Cart;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Models\Coupon;

class PayPalCallbackService
{
    public function __construct(private OrderService $orderService) {}

    public function handleSuccess(Request $request): mixed
    {
        $paypalOrderId = $request->get('order_id');

        if (!$paypalOrderId) {
            return redirect()->route('checkout.index')->with('error', 'Invalid PayPal response.');
        }

        $pending = session('pending_order');

        if (!$pending) {
            return redirect()->route('checkout.index')->with('error', 'Session expired. Please try again.');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($paypalOrderId);

        if (!isset($response['status']) || $response['status'] !== 'COMPLETED') {
            return redirect()->route('checkout.index')->with('error', 'PayPal payment not completed.');
        }

        $transactionId = $response['purchase_units'][0]['payments']['captures'][0]['id'] ?? $paypalOrderId;
        $payerName     = trim(
            ($response['payer']['name']['given_name'] ?? '') . ' ' .
            ($response['payer']['name']['surname']     ?? '')
        );

        $existing = Order::where('unique_order_id', $pending['temp_order_id'])->first();
        if ($existing) {
            return redirect()->route('order.success')->with('success', 'Order already placed!');
        }

        $cartItems = Cart::with(['product', 'variant'])
                         ->where('customer_id', $pending['customer_id'])
                         ->get();

        $order = $this->orderService->createOrder([
            ...$pending,
            'status'           => 'paid',
            'payment_method'   => 'PAYPAL',
            'transaction_id'   => $transactionId,
            'card_holder_name' => $payerName,
            'card_last_four'   => '1111',
        ], $cartItems);

        session()->forget(['pending_order', 'paypal_order_id']);

        $pendingOrder = session('pending_order');

        if (!empty($pendingOrder['coupon_code'])) {
            Coupon::where('code', $pendingOrder['coupon_code'])
                ->update(['is_used' => true,'used_by' => $customerId]);
        }
    
        session()->forget(['pending_order', 'applied_coupon']);

        return redirect()->route('order.success')->with([
            'success'        => 'Payment successful! Order placed.',
            'order_id'       => $order->unique_order_id,
            'total_amount'   => $order->total_amount,
            'payment_method' => 'PAYPAL',
            'transaction_id' => $transactionId,
        ]);
    }
}