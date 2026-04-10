<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminManageController extends Controller
{
    //
	public function getRevenue()
	{
		$orders = Order::with(['items.variant'])
			->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
			->get();

		$totalPaidByCustomers = $orders->sum('total_amount'); 
		
		$totalBaseCost = 0;
		foreach ($orders as $order) {
			foreach ($order->items as $item) {
				$totalBaseCost += ($item->price * $item->quantity);
			}
		}

		$totalMargin = $totalPaidByCustomers - $totalBaseCost;

		$dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as daily_total')
			->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
			->groupBy('date')
			->orderBy('date', 'DESC')
			->get();

		return view('admin.revenue', compact(
			'orders', 
			'totalPaidByCustomers', 
			'totalBaseCost', 
			'totalMargin', 
			'dailyRevenue'
		));
	}
	
	public function downloadRevenue()
	{
		$orders = Order::with(['items.variant'])
			->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
			->get();

		$totalPaidByCustomers = $orders->sum('total_amount'); 
		
		$totalBaseCost = 0;
		foreach ($orders as $order) {
			foreach ($order->items as $item) {
				$basePrice = $item->variant ? $item->variant->price : $item->price;
				$totalBaseCost += ($basePrice * $item->quantity); 
			}
		}

		$totalMargin = $totalPaidByCustomers - $totalBaseCost;

		$dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as daily_total')
			->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
			->groupBy('date')
			->orderBy('date', 'DESC')
			->get();

		$data = [
			'orders' => $orders,
			'totalPaidByCustomers' => $totalPaidByCustomers,
			'totalBaseCost' => $totalBaseCost,
			'totalMargin' => $totalMargin,
			'dailyRevenue' => $dailyRevenue,
			'generatedAt' => now()->format('d M Y H:i'),
		];

		$pdf = Pdf::loadView('admin.revenue_pdf', $data);

		return $pdf->download('Revenue_Report_' . now()->format('Y-m-d') . '.pdf');
	}
}
