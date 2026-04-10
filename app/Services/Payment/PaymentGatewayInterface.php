<?php
namespace App\Services\Payment;

use Illuminate\Support\Collection;

interface PaymentGatewayInterface
{
    public function initiate(array $pendingOrder, Collection $cartItems): mixed;
}