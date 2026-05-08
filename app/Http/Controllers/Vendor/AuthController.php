<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Vendor Register Page
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('vendor.auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor Register Store
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6|confirmed',

            'shop_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string',

            'gst_number'    => 'nullable|string|max:100',
            'pan_number'    => 'nullable|string|max:100',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),

            'role'      => 'vendor',
            'status'    => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Vendor
        |--------------------------------------------------------------------------
        */

        Vendor::create([
            'user_id'       => $user->id,
            'shop_name'     => $request->shop_name,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'gst_number'    => $request->gst_number,
            'pan_number'    => $request->pan_number,
        ]);

        return redirect()
                ->route('vendor.login')
                ->with('success', 'Vendor registration submitted. Wait for admin approval.');
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor Login Page
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('vendor.auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Find Vendor User
        |--------------------------------------------------------------------------
        */

        $user = User::where('email', $request->email)
            ->where('role', 'vendor')
            ->first();

        if (!$user) {

            return back()->with(
                'error',
                'Vendor account not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Password Check
        |--------------------------------------------------------------------------
        */

        if (!Hash::check($request->password, $user->password)) {

            return back()->with(
                'error',
                'Invalid password.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'approved') {

            if ($user->status === 'pending') {

                return back()->with(
                    'error',
                    'Your vendor account is pending admin approval.'
                );
            }

            if ($user->status === 'rejected') {

                return back()->with(
                    'error',
                    'Your vendor account has been rejected.'
                );
            }

            return back()->with(
                'error',
                'Account is inactive.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Vendor Profile Check
        |--------------------------------------------------------------------------
        */

        $vendor = Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {

            return back()->with(
                'error',
                'Vendor profile not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Login Vendor Guard
        |--------------------------------------------------------------------------
        */

        Auth::guard('vendor')->login($user);

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('vendor.dashboard')
            ->with(
                'success',
                'Welcome back!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('vendor.login');
    }


    public function dashboard()
    {
        $user = Auth::guard('vendor')->user();

        if (!$user) {
            return redirect()->route('vendor.login');
        }

        $vendor = \App\Models\Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {
            return redirect()->route('vendor.login')
                ->with('error', 'Vendor profile not found.');
        }

        $totalProducts = 0;
        $totalOrders   = 0;
        $totalSales    = 0;
        $earnings      = 0;

        return view('vendor.dashboard', compact(
            'user',
            'vendor',
            'totalProducts',
            'totalOrders',
            'totalSales',
            'earnings'
        ));
    }


    public function connectStripe()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $user = Auth::guard('vendor')->user();

        if (!$user) {
            return redirect()->route('vendor.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Get Vendor
        |--------------------------------------------------------------------------
        */

        $vendor = Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {
            return back()->with('error', 'Vendor not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Create Stripe Express Account
        |--------------------------------------------------------------------------
        */

        if (!$vendor->stripe_account_id) {

            $account = Account::create([
                'type' => 'express',
                'country' => 'US',
                'email' => $user->email,

                'capabilities' => [
                    'card_payments' => [
                        'requested' => true
                    ],

                    'transfers' => [
                        'requested' => true
                    ],
                ],
            ]);

            $vendor->stripe_account_id = $account->id;
            $vendor->save();
        }
     

        /*
        |--------------------------------------------------------------------------
        | Save Stripe Account ID in vendors table
        |--------------------------------------------------------------------------
        */

        $vendor->stripe_account_id = $account->id;
        $vendor->save();

        /*
        |--------------------------------------------------------------------------
        | Create Onboarding Link
        |--------------------------------------------------------------------------
        */

        $accountLink = AccountLink::create([
            'account' => $account->id,

            'refresh_url' => route('vendor.dashboard'),

            'return_url' => route('vendor.stripe.return'),

            'type' => 'account_onboarding',
        ]);

        return redirect($accountLink->url);
    }

    public function stripeSuccess()
    {
        $user = Auth::guard('vendor')->user();

        $vendor = Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {

            return redirect()
                ->route('vendor.dashboard')
                ->with('error', 'Vendor not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Stripe Account
        |--------------------------------------------------------------------------
        */

        Stripe::setApiKey(config('services.stripe.secret'));

        $account = Account::retrieve(
            $vendor->stripe_account_id
        );

        /*
        |--------------------------------------------------------------------------
        | Check Onboarding Completed
        |--------------------------------------------------------------------------
        */

        if (
            $account->details_submitted
            &&
            $account->charges_enabled
        ) {

            $vendor->update([
                'stripe_onboarded' => true
            ]);

            return redirect()
                ->route('vendor.dashboard')
                ->with(
                    'success',
                    'Stripe account connected successfully.'
                );
        }

        return redirect()
            ->route('vendor.dashboard')
            ->with(
                'error',
                'Stripe onboarding incomplete.'
            );
    }

    public function stripeReturn()
    {
        $user = Auth::guard('vendor')->user();

        $vendor = Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {
            return redirect()->route('vendor.dashboard');
        }

        $vendor->stripe_onboarded = 1;
        $vendor->save();

        return redirect()
            ->route('vendor.dashboard')
            ->with('success', 'Stripe connected successfully.');
    }
}