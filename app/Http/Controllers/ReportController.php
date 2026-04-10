<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer')->latest();

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay()
            ]);
        }

        $query->whereIn('status', [
            'paid',
            'processing',
            'shipped',
            'delivered'
        ]);

        $orders = $query->get();

        $total = $orders->sum('total_amount');

        $count = $orders->count();

        return view('admin.reports.index', compact('orders', 'total', 'count'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new SalesExport($request->from, $request->to),
            'sales-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}