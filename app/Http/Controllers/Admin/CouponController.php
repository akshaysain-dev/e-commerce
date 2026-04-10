<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with(['customer_generated', 'customer_used'])
                 ->latest()
                 ->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => 'required|string|unique:coupons,code',
            'discount'        => 'required|numeric|min:1',
            'type'            => 'required|in:fixed,percent',
            'expires_in_days' => 'required|integer|min:1',
        ]);

        Coupon::create([
            'name'            => $request->name,
            'code'            => strtoupper($request->code),
            'discount'        => $request->discount,
            'type'            => $request->type,
            'expires_in_days' => $request->expires_in_days,
            'expires_at' => Carbon::now()->addDays($request->integer('expires_in_days')),
        ]);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon created successfully!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'discount'        => 'required|numeric|min:1',
            'type'            => 'required|in:fixed,percent',
            'expires_in_days' => 'required|integer|min:1',
        ]);

        $coupon->update([
            'name'            => $request->name,
            'discount'        => $request->discount,
            'type'            => $request->type,
            'expires_in_days' => $request->expires_in_days,
            'expires_at'      => \Carbon\Carbon::now()->addDays($request->integer('expires_in_days')),
            'is_active'       => $request->has('is_active'), 
        ]);

        return redirect()->route('admin.coupons.index')
                        ->with('success', 'Coupon updated!');
    }


    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted!');
    }
}