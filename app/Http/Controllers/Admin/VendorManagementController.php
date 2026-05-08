<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;

class VendorManagementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Vendor Listing
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $vendors = Vendor::with('user')
                    ->latest()
                    ->get();

        return view('admin.vendor.index', compact('vendors'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Vendor Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->user->status = $request->status;

        $vendor->user->save();

        return back()->with('success', 'Vendor status updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Commission
    |--------------------------------------------------------------------------
    */

    public function updateCommission(Request $request, $id)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100'
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->commission_rate = $request->commission_rate;

        $vendor->save();

        return back()->with('success', 'Commission updated successfully.');
    }
}