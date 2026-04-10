<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class SalesExport implements FromCollection, WithHeadings
{
    protected $from, $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function collection()
    {
        $query = Order::query()
            ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered']);

        if ($this->from && $this->to) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->from)->startOfDay(),
                Carbon::parse($this->to)->endOfDay()
            ]);
        }

        $orders = $query->get([
            'id',
            'unique_order_id',
            'total_amount',
            'status',
            'created_at'
        ]);

        // ✅ Total Calculate
        $total = $orders->sum('total_amount');

        // ✅ Convert to collection (array format)
        $data = $orders->map(function ($order) {
            return [
                $order->id,
                $order->unique_order_id,
                $order->total_amount,
                ucfirst($order->status),
                $order->created_at->format('Y-m-d')
            ];
        });

        // ✅ Add empty row (spacing)
        $data->push(['', '', '', '', '']);

        // ✅ Add total row
        $data->push([
            '',
            'TOTAL',
            $total,
            '',
            ''
        ]);

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Order ID',
            'Total Amount',
            'Status',
            'Date'
        ];
    }
}