<?php

namespace App\Services\Payment;

use Stripe\StripeClient;
use Illuminate\Support\Collection;
use App\Models\Product;
use App\Models\Vendor;

class StripePaymentService implements PaymentGatewayInterface
{
    public function __construct(
        private StripeClient $stripe
    ) {}

    public function initiate(
        array $pendingOrder,
        Collection $cartItems
    ): mixed {

        $firstItem = $cartItems->first();

        $product = Product::find(
            $firstItem->product_id
        );

        $vendor = Vendor::where(
            'user_id',
            $product->user_id
        )->first();

        $paymentIntentData = [

            'amount' => (int) round(
                $pendingOrder['total_amount'] * 100
            ),

            'currency' => 'usd',

            'automatic_payment_methods' => [
                'enabled' => true,
            ],

            'metadata' => [

                'temp_order_id' =>
                    $pendingOrder['temp_order_id'],

                'customer_id' =>
                    $pendingOrder['customer_id'],

                'address' =>
                    $pendingOrder['address'],
            ],
        ];

        if (
            $vendor &&
            !empty($vendor->stripe_account_id) &&
            $vendor->stripe_onboarded == 1
        ) {

            $paymentIntentData[
                'application_fee_amount'
            ] = (int) round(
                $pendingOrder['admin_earning'] * 100
            );

            $paymentIntentData[
                'transfer_data'
            ] = [

                'destination' =>
                    $vendor->stripe_account_id,
            ];
        }

        $paymentIntent =
            $this->stripe->paymentIntents->create(
                $paymentIntentData
            );

        return view(
            'frontend.payment-page',
            [

                'clientSecret' =>
                    $paymentIntent->client_secret,

                'stripeKey' =>
                    config('services.stripe.key'),

                'totalAmount' =>
                    $pendingOrder['total_amount'],

                'tempOrderId' =>
                    $pendingOrder['temp_order_id'],
            ]
        );
    }
}