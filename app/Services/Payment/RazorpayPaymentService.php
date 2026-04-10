<?php
namespace App\Services\Payment;

use Razorpay\Api\Api;
use App\Models\Customer;
use Illuminate\Support\Collection;

class RazorpayPaymentService implements PaymentGatewayInterface
{
    public function initiate(array $pendingOrder, Collection $cartItems): mixed
    {
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $razorpayOrder = $api->order->create([
            'receipt'         => $pendingOrder['temp_order_id'],
            'amount'          => $pendingOrder['total_amount'] * 100,
            'currency'        => 'INR',
            'payment_capture' => 1,
        ]);

        session(['razorpay_order_id' => $razorpayOrder['id']]);

        return view('frontend.razorpay_page', [
            'totalAmount'     => $pendingOrder['total_amount'],
            'tempOrderId'     => $pendingOrder['temp_order_id'],
            'razorpayOrderId' => $razorpayOrder['id'],
            'razorpayKey'     => config('services.razorpay.key'),
            'customer'        => Customer::find($pendingOrder['customer_id']),
        ]);
    }
}