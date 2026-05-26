<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;
use Illuminate\Http\Request;

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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([

            'status' => 'required'

        ]);

        $order = Order::findOrFail($id);

        $order->status = $request->status;

        $order->save();

        return redirect()
            ->back()
            ->with('success', 'Order status updated successfully.');
    }
}