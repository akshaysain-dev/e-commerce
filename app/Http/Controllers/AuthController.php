<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\GuestCart;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /* public function showLogin()
    {
        return view('admin.login');
    } */

    public function admin_login(Request $request)
    {
        // Validate inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $request->session()->put('admin_id', $user->id);
            $request->session()->put('admin_name', $user->name);

            return redirect()->route('admin_dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password',
        ]);
    }

    public function admin_logout(Request $request)
    {
        $request->session()->forget(['admin_id', 'admin_name']);
        return redirect()->route('admin');
    }

    public function admin_dashboard(Request $request)
    {
        $validStatuses = ['paid', 'processing', 'shipped', 'delivered'];

        // 🔥 Profit / Revenue Chart (Last 7 Days with NO missing dates)
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            $orders = Order::with(['items.variant'])
                ->whereIn('status', $validStatuses)
                ->whereDate('created_at', $date)
                ->get();

            $revenue = 0;
            $cost = 0;

            foreach ($orders as $order) {
                $revenue += $order->total_amount;

                foreach ($order->items as $item) {

                    // ✅ Correct price detection (VERY IMPORTANT)
                    $price = $item->variant->price ?? $item->price ?? 0;

                    $cost += ($price * $item->quantity);
                }
            }

            $profit = $revenue - $cost;

            $chartData[] = [
                'date' => $date,
                'revenue' => (float) $revenue,
                'cost' => (float) $cost,
                'profit' => (float) $profit
            ];
        }

        // 📦 Orders Chart (Last 7 Days)
        $ordersChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereIn('status', $validStatuses)
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 👥 Customers Chart (Last 7 Days)
        $customersChart = Customer::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Sales Chart
      $saleChart = Order::whereIn('status', $validStatuses)->where('created_at', '>=', Carbon::now()->subDays(6))->sum('total_amount');
        
        // 📊 Dashboard Data
        $data = [
            'products_count'   => Product::count(),
            'categories_count' => Category::count(),
            'customers_count'  => Customer::count(),

            'orders_count'     => Order::where('status', 'pending')->count(),

            'recentOrders'     => Order::with('customer')->latest()->take(6)->get(),

            'totalRevenue'     => Order::whereIn('status', $validStatuses)->sum('total_amount'),

            'totalRevenueToday'=> Order::whereIn('status', $validStatuses)
                                        ->whereDate('created_at', Carbon::today())
                                        ->sum('total_amount'),

            'ordersToday'      => Order::whereIn('status', $validStatuses)
                                        ->whereDate('created_at', Carbon::today())
                                        ->count(),

            'newCustomers'     => Customer::whereDate('created_at', Carbon::today())->count(),

            'lowStockAlert'    => ProductVariant::where('stock', 0)->count(),

            'lowStockToday'    => ProductVariant::whereDate('updated_at', Carbon::today())
                                                ->where('stock', 0)
                                                ->count(),

            // 🔥 Charts
            'profitChart'      => $chartData,
            'ordersChart'      => $ordersChart,
            'customersChart'   => $customersChart,
            'saleChart'        => $saleChart,
        ];

        return view('admin.dashboard', $data);
    }

    public function customer_login_form(){
        if(session('customer_id')){
            return redirect()->route('home');
        }
        return view('frontend.customer.login');
    }

    public function customer_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $customer = Customer::where('email', $request->email)->where('status', 1)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            
            $request->session()->put('customer_id', $customer->id);
            $request->session()->put('customer_name', $customer->name);

            // --- MERGE GUEST CART (BY IP) TO REAL CART ---
            $userIp = $request->ip();
            $guestItems = GuestCart::where('ip_address', $userIp)->get();

            if ($guestItems->isNotEmpty()) {
                foreach ($guestItems as $item) {
                    // Check if this variant already exists in the real cart for this customer
                    $existingCartItem = Cart::where('customer_id', $customer->id)
                                            ->where('product_variant_id', $item->product_variant_id)
                                            ->first();

                    if ($existingCartItem) {
                        // Update quantity in the real cart
                        $existingCartItem->increment('quantity', $item->quantity);
                    } else {
                        // Move the item to the real cart
                        Cart::create([
                            'customer_id'        => $customer->id,
                            'product_id'         => $item->product_id,
                            'product_variant_id' => $item->product_variant_id,
                            'quantity'           => $item->quantity,
                        ]);
                    }

                    // Delete the guest record after processing
                    $item->delete();
                }   
            }
            // ---------------------------------------------

            Notification::create([
                'customer_id' => $customer->id,
                'title' => 'Login Your account.',
                'message' => 'Your account has been logged in on a new device.',
            ]);

            return redirect()->route('home')->with('success', 'Welcome back, ' . $customer->name);
        }
        else{
            return back()->with("error","Email and password is Wrong");
        }

    }


    public function customer_logout(Request $request)
    {
        $request->session()->forget(['customer_id', 'customer_name']);
        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }
	
	public function stockAlert(){
		$lowStockproducts = ProductVariant::with('product')->where('stock', 0)->paginate(8);
	
		return view('admin.lowStockProducts',compact('lowStockproducts'));
	}

    public function chartData(Request $request)
    {
        $days = $request->days;
        $validStatuses = ['paid', 'processing', 'shipped', 'delivered'];

        // 🔥 ALL TIME
        if ($days == 'all') {

            $ordersData = Order::with(['items.variant'])
                ->whereIn('status', $validStatuses)
                ->get()
                ->groupBy(function ($order) {
                    return Carbon::parse($order->created_at)->format('Y-m-d');
                });

            $customersData = Customer::selectRaw("
                    DATE(created_at) as date,
                    COUNT(*) as customers
                ")
                ->groupBy('date')
                ->pluck('customers', 'date');

            $allDates = collect($ordersData->keys())
                ->merge($customersData->keys())
                ->unique()
                ->sort();

            $finalData = [];

            foreach ($allDates as $date) {

                $dailyOrders = $ordersData[$date] ?? collect([]);

                $revenue = 0;
                $cost = 0;

                foreach ($dailyOrders as $order) {
                    $revenue += $order->total_amount;

                    foreach ($order->items as $item) {
                        $price = $item->variant->price ?? $item->price ?? 0;
                        $cost += ($price * $item->quantity);
                    }
                }

                $profit = $revenue - $cost;

                $salesData = Order::whereIn('status', $validStatuses)
                    ->selectRaw("DATE(created_at) as date, SUM(total_amount) as sales")
                    ->groupBy('date')
                    ->pluck('sales', 'date');

                $finalData[] = [
                    'date' => $date,
                    'revenue' => $revenue,
                    'cost' => $cost,
                    'profit' => $profit,
                    'orders' => $dailyOrders->count(),
                    'customers' => $customersData[$date] ?? 0,
                    'sales'     => $salesData[$date] ?? 0,
                ];
            }

            return response()->json(array_values($finalData));
        }

        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {

            $date = now()->subDays($i)->format('Y-m-d');

            $orders = Order::with(['items.variant'])
                ->whereIn('status', $validStatuses)
                ->whereDate('created_at', $date)
                ->get();

            $revenue = 0;
            $cost = 0;

            foreach ($orders as $order) {
                $revenue += $order->total_amount;

                foreach ($order->items as $item) {
                    $price = $item->variant->price ?? $item->price ?? 0;
                    $cost += ($price * $item->quantity);
                }
            }

            $profit = $revenue - $cost;

            $saleChart = Order::whereIn('status', $validStatuses)->whereDate('created_at', $date)->sum('total_amount');

            $data[] = [
                'date' => $date,
                'revenue' => $revenue,
                'cost' => $cost,
                'profit' => $profit,
                'orders' => $orders->count(),
                'customers' => Customer::whereDate('created_at', $date)->count(),
                'sales'    =>  $saleChart,
            ];
        }

        return response()->json($data);
    }
}