<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\GuestCart;
use App\Models\Cart;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

   public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // 1. Check by google_id
            $customer = Customer::where('google_id', $googleUser->getId())->first();

            if (!$customer) {

                // 2. Check by email
                $customer = Customer::where('email', $googleUser->getEmail())->first();

                if ($customer) {
                    // Update existing user
                    $customer->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                } else {
                    // 3. Create new user
                    $customer = Customer::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => bcrypt(Str::random(16)),
                        'status' => 1
                    ]);
                }
            }

            // ❗ OPTIONAL: Check status (same as normal login)
            if ($customer->status != 1) {
                return redirect()->route('customer_login')->with('error', 'Account inactive');
            }

            // 4. Login user
            Auth::guard('web')->login($customer, true);

            // ✅ Set session manually (same as customer_login)
            $request->session()->put('customer_id', $customer->id);
            $request->session()->put('customer_name', $customer->name);

            // ✅ --- MERGE GUEST CART ---
            $userIp = $request->ip();
            $guestItems = GuestCart::where('ip_address', $userIp)->get();

            if ($guestItems->isNotEmpty()) {
                foreach ($guestItems as $item) {

                    $existingCartItem = Cart::where('customer_id', $customer->id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->first();

                    if ($existingCartItem) {
                        $existingCartItem->increment('quantity', $item->quantity);
                    } else {
                        Cart::create([
                            'customer_id'        => $customer->id,
                            'product_id'         => $item->product_id,
                            'product_variant_id' => $item->product_variant_id,
                            'quantity'           => $item->quantity,
                        ]);

                         $welcomeCode = 'WELCOME' . strtoupper(substr(uniqid(), -6));

                        $coupon = Coupon::create([
                            'name'            => 'Welcome Gift — ' . $customer->name,
                            'code'            => $welcomeCode,
                            'discount'        => 10,
                            'type'            => 'percent',
                            'expires_in_days' => 30,
                            'expires_at'      => Carbon::now()->addDays(30),
                            'is_used'         => false,
                            'is_active'       => true,
                            'generated_for'   => $customer->id,   
                            'used_by'         => null,
                        ]);

                         Notification::create([
                            'customer_id' => $customer->id,
                            'title' => 'Reward Coupon.',
                            'message' => 'Your 10% login discount coupon code is: ' . $coupon->code,
                        ]);
                    }

                    $item->delete();
                }
            }
            // ✅ ------------------------

           

            // ✅ Login Notification
            Notification::create([
                'customer_id' => $customer->id,
                'title' => 'Login Your account.',
                'message' => 'Your account has been logged in via Google.',
            ]);

            // ✅ Coupon Notification (FIXED)
           

            return redirect()->route('home')->with('success', 'Welcome back, ' . $customer->name);

        } catch (\Exception $e) {
            return redirect()->route('customer_login')->with('error', 'Google Login Failed');
        }
    }
}