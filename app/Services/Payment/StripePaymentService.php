<?php
namespace App\Services\Payment;

use Stripe\StripeClient;
use Illuminate\Support\Collection;

class StripePaymentService implements PaymentGatewayInterface
{
    public function __construct(private StripeClient $stripe) {}

    public function initiate(array $pendingOrder, Collection $cartItems): mixed
    {
        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount'                    => $pendingOrder['total_amount'] * 100,
            'currency'                  => 'inr',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata'                  => [
                'temp_order_id' => $pendingOrder['temp_order_id'],
                'customer_id'   => $pendingOrder['customer_id'],
                'address'       => $pendingOrder['address'],
            ],
        ]);

        return view('frontend.payment-page', [
            'clientSecret' => $paymentIntent->client_secret,
            'stripeKey'    => config('services.stripe.key'),
            'totalAmount'  => $pendingOrder['total_amount'],
            'tempOrderId'  => $pendingOrder['temp_order_id'],
        ]);
    }
}