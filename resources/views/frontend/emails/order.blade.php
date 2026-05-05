<h2>🎉 Order Confirmed</h2>

<p>Hi {{ $order->customer->name ?? 'Customer' }},</p>

<p>Your order has been placed successfully!</p>

<hr>

<h3>🧾 Order Details</h3>
<p><strong>Order ID:</strong> {{ $order->unique_order_id }}</p>
<p><strong>Total Amount:</strong> ₹{{ $order->total_amount }}</p>
<p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
<p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

<hr>

<h3>📦 Products</h3>

<table width="100%" border="1" cellspacing="0" cellpadding="8">
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>
                {{ $item->product->name ?? 'Product' }}
                <br>
                <small>{{ $item->variant->name ?? '' }}</small>
            </td>
            <td>{{ $item->quantity }}</td>
            <td>₹{{ $item->price }}</td>
            <td>₹{{ $item->price * $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr>

<h3>💰 Price Summary</h3>
<p><strong>Subtotal:</strong> ₹{{ $order->total_amount + ($order->discount_amount ?? 0) }}</p>
<p><strong>Discount:</strong> ₹{{ $order->discount_amount ?? 0 }}</p>
<p><strong>Final Total:</strong> ₹{{ $order->total_amount }}</p>

<hr>

<h3>📍 Delivery Address</h3>
<p>{{ $order->address }}</p>

<hr>

<h3>🔗 View Your Order</h3>

<p>
    <a href="{{ route('order', $order->id) }}" 
       style="background:#28a745;color:#fff;padding:10px 15px;text-decoration:none;">
       View Order Details
    </a>
</p>

<hr>

<p>Thank you for shopping with us ❤️</p>