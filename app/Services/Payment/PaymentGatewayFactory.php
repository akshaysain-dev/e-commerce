<?php
namespace App\Services\Payment;

use InvalidArgumentException;

class PaymentGatewayFactory
{
    public function __construct(
        private StripePaymentService   $stripe,
        private PayPalPaymentService   $paypal,
        private RazorpayPaymentService $razorpay,
        private CodPaymentService      $cod,
    ) {}

    public function make(string $method): PaymentGatewayInterface
    {
        return match(strtoupper($method)) {
            'STRIPE'   => $this->stripe,
            'PAYPAL'   => $this->paypal,
            'RAZORPAY' => $this->razorpay,
            'COD'      => $this->cod,
            default    => throw new InvalidArgumentException("Unknown payment method: {$method}"),
        };
    }
}