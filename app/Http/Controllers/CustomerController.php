<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Notification;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Exports\CustomerOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TaxOrShippingCharge;

class CustomerController extends Controller
{
    //
    public function register(){
        return view('frontend.customer.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:customers,email',
            'phone'       => 'required|digits:10',
            'password'    => 'required|min:6|confirmed',
            'address'     => 'required|string|max:500',
            'city'        => 'required|string|max:100',
            'state'       => 'required|string|max:100',
            'country'     => 'required|string|max:100',
            'postal_code' => 'required|numeric|digits_between:4,10',
        ]);

        $customer = Customer::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'area'        => $request->address,
            'city'        => $request->city,
            'state'       => $request->state,
            'country'     => $request->country,
            'postal_code' => $request->postal_code,
        ]);

        session(['customer_id'   => $customer->id]);
        session(['customer_name' => $customer->name]);

        // ── Welcome Coupon Auto-Generate ──────────────────────────
        $welcomeCode = 'WELCOME' . strtoupper(substr(uniqid(), -6));

        Coupon::create([
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
            'title'       => 'Welcome on MyShop.',
            'message'     => 'Your account has been activated now and yor First Order Discount in 20% Coupon is '. $welcomeCode,
        ]);

        return redirect()->route('home')
                        ->with('success', 'Registration successful! Welcome ' . $customer->name)
                        ->with('welcome_coupon', $welcomeCode);
    }
    public function customer_profile()
    {
        $customer = Customer::find(session('customer_id'));
        return view('frontend.customer.profile', compact('customer'));
    }

    public function customer_profile_update(Request $request)
    {
        $id = session('customer_id');
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|max:20',
            'password' => 'nullable|min:6|confirmed'
        ]);

        // Update all fields
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->area = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->postal_code = $request->postal_code;

        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
        }

        $customer->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function admin_view(){
        $customers = Customer::with('orders')->get(); 
        return view('admin.customers.index',compact('customers'));
    }

    public function updateStatus(Request $request)
    {
        $customer = Customer::findOrFail($request->id);
        $customer->status = $request->status;
        $customer->save();

        return response()->json(['success' => 'Status updated successfully.']);
    }

    public function delete_customer($id){
        $customer = Customer::find($id);
        
        if ($customer) {
            $customer->delete();
            return back()->with('success', 'Customer deleted successfully!');
        }

        return back()->with('error', 'Record not found.');
    }

    public function myOrders()
    {
        $orders = Order::with(['orderItems.product', 'orderItems.variant.attributeValues.attribute'])
                    ->where('customer_id', session('customer_id'))
                    ->latest()
                    ->paginate(10);

        return view('frontend.my-orders', compact('orders'));
    }
	
	public function view_order($id)
    {
        $order = Order::with(['orderItems.product', 'orderItems.variant.attributeValues.attribute'])
                    ->where('customer_id', session('customer_id'))->where('id',$id)->firstOrFail();

        return view('frontend.order-details', compact('order'));
    }

    public function customer_orders(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $query = Order::with(['orderItems.product', 'orderItems.variant.attributeValues.attribute'])
            ->where('customer_id', $id);

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        if ($request->payment_status) {
            $query->where('status', $request->payment_status);
        }

        // 🔍 Order ID Filter (optional)
        if ($request->order_id) {
            $query->where('id', $request->order_id);
        }

        $orders = $query->latest()->get();

        return view('admin.customers.orders', compact('orders', 'customer'));
    }

    public function exportCustomerOrders(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        return Excel::download( new CustomerOrdersExport($id, $request->all()), 'customer_orders_'.$customer->name.'.xlsx');
    }
}
