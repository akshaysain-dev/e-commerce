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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\EmailOtpMail;
use App\Models\EmailOtp;
use App\Services\ZohoService;

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

        // Clean old unverified entries for this email
        DB::table('customer_verifications')->where('email', $request->email)->delete();

        $token = Str::random(64);

        DB::table('customer_verifications')->insert([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'address'     => $request->address,
            'city'        => $request->city,
            'state'       => $request->state,
            'country'     => $request->country,
            'postal_code' => $request->postal_code,
            'token'       => $token,
            'created_at'  => now(),
        ]);

        // Redirect to verification choice page with email in session
        session(['pending_verification_email' => $request->email]);

        return redirect()->route('verification.choice');
    }


   /*  public function store(Request $request)
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
    } */

    // ===============================================================================================

    // Show the choice page
    public function verificationChoice()
    {
        if (!session('pending_verification_email')) {
            return redirect()->route('customer.register');
        }
        return view('frontend.verify-choice');
    }

    // Send email verification link
    public function sendVerificationLink(Request $request)
    {
        $email = session('pending_verification_email');
        if (!$email) return redirect()->route('customer.register');

        $record = DB::table('customer_verifications')->where('email', $email)->first();
        if (!$record) return redirect()->route('customer.register');

        $link = url('/verify-email/' . $record->token);

        Mail::raw("Click to verify your email: " . $link, function ($message) use ($email) {
            $message->to($email)->subject('Verify Your Email');
        });

        // Session pe email rakho — link click hone tak
        return redirect()->route('verification.choice')
                        ->with('success', 'Verification link sent! Please check your inbox.');
    }

    // Send OTP
    public function sendOtp(Request $request)
    {
        $email = session('pending_verification_email');
        if (!$email) return redirect()->route('customer.register');

        $otp = rand(100000, 999999);

        DB::table('customer_verifications')
            ->where('email', $email)
            ->update(['otp' => $otp]);

        Mail::raw("Your OTP for verification is: " . $otp, function ($message) use ($email) {
            $message->to($email)->subject('Your OTP Code');
        });

        return redirect()->route('verification.otpForm')->with('otp_sent', true);
    }

    // Show OTP input form
    public function showOtpForm()
    {
        if (!session('pending_verification_email')) {
            return redirect()->route('customer.register');
        }
        return view('frontend.verify-otp');
    }

    // Verify OTP
    public function verifyOtp(Request $request, ZohoService $zoho)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('pending_verification_email');
        if (!$email) return redirect()->route('customer.register');

        $record = DB::table('customer_verifications')
            ->where('email', $email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // ✅ Create actual customer
        $customer = Customer::create([
            'name'        => $record->name,
            'email'       => $record->email,
            'phone'       => $record->phone,
            'password'    => $record->password,
            'area'        => $record->address,
            'city'        => $record->city,
            'state'       => $record->state,
            'country'     => $record->country,
            'postal_code' => $record->postal_code,
        ]);

        $zoho->sendCustomer($customer);

        // ✅ Delete temp record
        DB::table('customer_verifications')->where('email', $email)->delete();

        // ✅ Login session
        session([
            'customer_id'   => $customer->id,
            'customer_name' => $customer->name,
        ]);

        // ✅ Welcome coupon
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

        // ✅ Welcome notification
        Notification::create([
            'customer_id' => $customer->id,
            'title'       => 'Welcome on MyShop.',
            'message'     => 'Your coupon is ' . $welcomeCode,
        ]);

        // ✅ Clear session & redirect
        session()->forget('pending_verification_email');

        return redirect()->route('home')->with('success', 'OTP verified & registration complete!');
    }

    // Verify via email link
    /* public function verifyEmail($token)
    {
        $record = DB::table('customer_verifications')->where('token', $token)->first();

        if (!$record) {
            return redirect()->route('login_customer')->with('error', 'Invalid or expired verification link.');
        }

        DB::table('customers')->insert([
            'name'        => $record->name,
            'email'       => $record->email,
            'phone'       => $record->phone,
            'password'    => $record->password,
            'address'     => $record->address,
            'city'        => $record->city,
            'state'       => $record->state,
            'country'     => $record->country,
            'postal_code' => $record->postal_code,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        DB::table('customer_verifications')->where('token', $token)->delete();

        return redirect()->route('login_customer')->with('success', 'Email verified! You can now login.');
    } */


    // ================================================================================================

    public function verifyEmail($token, ZohoService $zoho)
    {
        $record = DB::table('customer_verifications')->where('token', $token)->first();

        if (!$record) {
            return redirect()->route('customer_login')->with('error', 'Invalid or expired link');
        }

        // create actual customer
        $customer = Customer::create([
            'name'        => $record->name,
            'email'       => $record->email,
            'phone'       => $record->phone,
            'password'    => $record->password,
            'area'        => $record->address,
            'city'        => $record->city,
            'state'       => $record->state,
            'country'     => $record->country,
            'postal_code' => $record->postal_code,
        ]);

        $zoho->sendCustomer($customer);

        // delete temp record
        DB::table('customer_verifications')->where('email', $record->email)->delete();

        // login
        session([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name
        ]);

        // coupon + notification same as before
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
            'title' => 'Welcome on MyShop.',
            'message' => 'Your coupon is '.$welcomeCode,
        ]);

        return redirect()->route('home')->with('success', 'Email verified & registration complete');
    }
    
    public function customer_profile()
    {
        $customer = Customer::find(session('customer_id'));
        return view('frontend.customer.profile', compact('customer'));
    }

    public function customer_profile_update(Request $request, ZohoService $zoho)
    {
        $id = session('customer_id');
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|digits:10',
            'email'    => 'required|email',
            'password' => 'nullable|min:6|confirmed'
        ]);

        // ✅ EMAIL CHANGE — OTP flow
        if ($request->email !== $customer->email) {

            // Purane OTP delete karo (duplicate records avoid karne ke liye)
            EmailOtp::where('customer_id', $customer->id)->delete();

            $otp = rand(100000, 999999);

            EmailOtp::create([
                'customer_id' => $customer->id,
                'email'       => $request->email,
                'otp'         => $otp,
                'otp_for'     => "update",
                'expires_at'  => now()->addMinutes(5),
            ]);

            Mail::to($request->email)->send(new EmailOtpMail($otp));

            // Baaki form data session mein save karo taaki OTP verify hone ke baad update ho
            session([
                'pending_profile' => [
                    'name'     => $request->name,
                    'phone'    => $request->phone,
                    'address'  => $request->address,
                    'city'     => $request->city,
                    'state'    => $request->state,
                    'postal_code' => $request->postal_code,
                    'country'  => $request->country,
                    'password' => $request->password,
                ]
            ]);

            return response()->json(['otp_sent' => true]);
        }

        // ✅ NORMAL UPDATE (email same hai)
        $customer->name         = $request->name;
        $customer->phone        = $request->phone;
        $customer->area         = $request->address;
        $customer->city         = $request->city;
        $customer->state        = $request->state;
        $customer->postal_code  = $request->postal_code;
        $customer->country      = $request->country;

        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
        }

        $customer->save();

        if ($customer->zoho_id) {
            $zoho->updateCustomer($customer);
        } else {
            $zoho->sendCustomer($customer);
        }

        session(['customer_name' => $customer->name]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function verifyEmailOtp(Request $request, ZohoService $zoho)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $customerId = session('customer_id');

        $otpRecord = EmailOtp::where('customer_id', $customerId)
            ->where('otp', $request->otp)
            ->where('otp_for','update')
            ->latest()
            ->first();

        // ✅ Wrong OTP
        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ], 422);
        }

        // ✅ Expired OTP
        if ($otpRecord->expires_at < now()) {
            $otpRecord->delete();
            return response()->json([
                'success' => false,
                'message' => 'OTP expired. Please request a new one.'
            ], 422);
        }

        // ✅ EMAIL + PROFILE UPDATE
        $customer        = Customer::find($customerId);
        $customer->email = $otpRecord->email;

        $pending = session('pending_profile', []);

        if (!empty($pending['name']))        $customer->name        = $pending['name'];
        if (!empty($pending['phone']))       $customer->phone       = $pending['phone'];
        if (isset($pending['address']))      $customer->area        = $pending['address'];
        if (isset($pending['city']))         $customer->city        = $pending['city'];
        if (isset($pending['state']))        $customer->state       = $pending['state'];
        if (isset($pending['postal_code'])) $customer->postal_code = $pending['postal_code'];
        if (isset($pending['country']))      $customer->country     = $pending['country'];

        if (!empty($pending['password'])) {
            $customer->password = Hash::make($pending['password']);
        }

        $customer->save();

        if ($customer->zoho_id) {
            $zoho->updateCustomer($customer);
        } else {
            $zoho->sendCustomer($customer);
        }
        // Cleanup
        $otpRecord->delete();
        session()->forget('pending_profile');

        return response()->json([
            'success' => true,
            'message' => 'Email verified and profile updated successfully!'
        ]);
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

    public function delete_customer($id, ZohoService $zoho)
    {
        $customer = Customer::find($id);
        
        if ($customer) {

            // ✅ Step 1: Zoho se delete (IMPORTANT)
            if (!empty($customer->zoho_id)) {
                $zoho->deleteCustomer($customer);
            }

            // ✅ Step 2: Laravel DB se delete
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
