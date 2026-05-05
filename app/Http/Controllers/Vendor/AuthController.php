<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        $credentials = [
            'email'     => $request->email,
            'password'  => $request->password,
            'role'      => 'vendor',
        ];

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | Check Vendor Status
            |--------------------------------------------------------------------------
            */

            if ($user->status != 'approved') {

                Auth::logout();

                return back()->with('error', 'Your vendor account is not approved yet.');
            }

            return redirect()->route('vendor.dashboard');
        }

        return back()->with('error', 'Invalid credentials.');
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor Logout
    |--------------------------------------------------------------------------
    */

    public function logout()
    {
        Auth::logout();

        return redirect()->route('vendor.login');
    }
}