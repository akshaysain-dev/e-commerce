<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaxOrShippingCharge;


class TaxOrShippingChargeController extends Controller
{
    public function index(){
        $currentSettings = TaxOrShippingCharge::first();
        return view('admin.tax-shipping.index',compact('currentSettings'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tax' => 'required',
            'shipping_charge' => 'required',
            'max_charge_for_shipping' => 'required',
        ]);

       // dd($request->all());
        if (!$request->tax_id) {
            // Create new record if no ID is present
            TaxOrShippingCharge::create($request->all());
            $message = 'Details added successfully!';
        } else {
            // Find the specific record and update it
            $setting = TaxOrShippingCharge::findOrFail($request->tax_id);
            $setting->update($request->all());
            $message = 'Details updated successfully!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function delete($id)
    {
        // Find the record by ID or fail with a 404 error if not found
        $setting = TaxOrShippingCharge::findOrFail($id);
        $setting->delete();
        return redirect()->back()->with('success', 'Tax/Shipping setting deleted successfully!');
    }

}
