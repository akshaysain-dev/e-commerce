<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerOrdersExport implements FromCollection, WithHeadings
{
    protected $customer_id, $filters;

    public function __construct($customer_id, $filters = [])
    {
        $this->customer_id = $customer_id;
        $this->filters = $filters;
    }

    public function collection()
    {
        return Order::with(['orderItems.product', 'orderItems.variant.attributeValues.attribute'])
            ->where('customer_id', $this->customer_id)
            ->latest()
            ->get()
            ->flatMap(function ($order) {

                return $order->orderItems->map(function ($item) use ($order) {

                    return [
                        'Order ID' => $order->unique_order_id ?? $order->id,
                        'Product' => $item->product->name ?? '',
                        'Variant' => $item->variant 
                            ? $item->variant->attributeValues->pluck('value')->join(', ')
                            : '',
                        'Qty' => $item->quantity,
                        'Price' => $item->price,
                        'Total' => $item->price * $item->quantity,
                        'Payment Status' => $order->status,
                        'Order Date' => $order->created_at->format('Y-m-d'),
                        'Delivery Address' => $order->address,
                    ];
                });

            });
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Product',
            'Variant',
            'Qty',
            'Price',
            'Total',
            'Payment Status',
            'Order Date',
            'Delivery Address',
        ];
    }
}
