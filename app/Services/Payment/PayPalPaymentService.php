<?php
namespace App\Services\Payment;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Collection;

class PayPalPaymentService implements PaymentGatewayInterface
{
    public function initiate(array $pendingOrder, Collection $cartItems): mixed
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $pendingOrder['temp_order_id'],
                "amount" => [
                    "currency_code" => "USD",
                    "value"         => number_format($pendingOrder['total_amount'], 2, '.', ''),
                ],
            ]],
        ]);

        if (isset($response['error']) || !isset($response['id'])) {
            throw new \Exception($response['message'] ?? 'PayPal error. Try again.');
        }

        session(['paypal_order_id' => $response['id']]);

        return view('frontend.paypal_page', [
            'totalAmount'   => $pendingOrder['total_amount'],
            'tempOrderId'   => $pendingOrder['temp_order_id'],
            'paypalOrderId' => $response['id'],
            'clientId'      => config('paypal.sandbox.client_id'),
        ]);
    }
}