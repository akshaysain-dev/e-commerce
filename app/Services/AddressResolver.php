<?php
namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;

class AddressResolver
{
    public function resolve(Request $request, int $customerId): string
    {
        if ($request->address_type === 'new') {
            $request->validate(['address' => 'required|string|max:255']);
            return $request->address;
        }

        $customer = Customer::findOrFail($customerId);

        return trim(
            $customer->area    . ', ' .
            $customer->city    . ', ' .
            $customer->state   . ', ' .
            $customer->country . ' - ' .
            $customer->postal_code
        );
    }
}