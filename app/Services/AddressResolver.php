<?php
namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;

class AddressResolver
{
   public function resolve(Request $request, int $customerId): string
    {
        // ✅ If user selects NEW address
        if ($request->address_type == 'new') {

            $request->validate([
                'new_phone'    => 'required|string|max:15',
                'new_address'  => 'required|string|max:255',
                'new_city'     => 'required|string|max:100',
                'new_state'    => 'required|string|max:100',
                'new_pincode'  => 'required|string|max:10',
            ]);

            // ✅ Combine into single string (for DB storage)
            return trim(
                $request->new_phone . ', ' .
                $request->new_address . ', ' .
                $request->new_city . ', ' .
                $request->new_state . ' - ' .
                $request->new_pincode
            );
        }

        // ✅ Otherwise use saved address
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