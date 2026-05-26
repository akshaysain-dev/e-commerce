<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;

class VendorEarningController extends Controller
{
    public function index()
    {
       // dd(auth('vendor')->id());
        $vendor = Vendor::where('user_id', auth('vendor')->id())->first();

        if (!$vendor) {

            abort(404, 'Vendor not found');

        }

        $orderItems = OrderItem::with([
                'product.firstVariant',
                'order.customer'
            ])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->get();

        //dd($orderItems->toArray());
        $totalEarning = $orderItems->sum('vendor_amount');

        return view('vendor.earnings.index', compact(
            'orderItems',
            'totalEarning'
        ));
    }
}