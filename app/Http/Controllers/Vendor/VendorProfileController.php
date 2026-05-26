<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('vendor')->user();

        $vendor = Vendor::where('user_id', $user->id)->first();

        return view('vendor.profile.index', compact(
            'user',
            'vendor'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('vendor')->user();

        $vendor = Vendor::where('user_id', $user->id)->first();

        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required',
            'shop_name' => 'required',
            'address' => 'required',
            'gst_number' => 'nullable',
            'pan_number' => 'nullable',
            'bank_name' => 'nullable',
            'account_number' => 'nullable',
            'ifsc_code' => 'nullable',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp',

        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {

            $user->password = Hash::make($request->password);
        }

        $user->save();

        $vendor->shop_name = $request->shop_name;
        $vendor->phone = $request->phone;
        $vendor->address = $request->address;
        $vendor->gst_number = $request->gst_number;
        $vendor->pan_number = $request->pan_number;
        $vendor->bank_name = $request->bank_name;
        $vendor->account_number = $request->account_number;
        $vendor->ifsc_code = $request->ifsc_code;

        if ($request->hasFile('logo')) {

            $logo = $request->file('logo')->store('vendors', 'public');

            $vendor->logo = $logo;
        }

        $vendor->save();

        return redirect()
            ->back()
            ->with('success', 'Profile updated successfully.');
    }
}