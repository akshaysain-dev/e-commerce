<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            color: #2d3436; 
            line-height: 1.4; 
            margin: 0;
            padding: 40px;
            background-color: #fff;
        }
        
        /* Header & Logo */
        .header-container { margin-bottom: 40px; }
        .brand-section { float: left; width: 50%; }
        .invoice-section { float: right; width: 50%; text-align: right; }
        .brand-title { color: #0984e3; font-size: 32px; font-weight: 800; margin: 0; letter-spacing: -1px; }
        .brand-sub { font-size: 11px; color: #636e72; margin-top: 5px; text-transform: uppercase; }
        
        .invoice-label { font-size: 24px; font-weight: 300; color: #b2bec3; margin: 0; }
        .invoice-id { font-size: 16px; font-weight: bold; margin-top: 5px; }

        .clearfix { clear: both; }

        /* Modern Info Grid */
        .info-grid { width: 100%; margin-bottom: 40px; border-top: 1px solid #dfe6e9; padding-top: 20px; }
        .info-col { vertical-align: top; width: 33%; }
        .label { color: #b2bec3; font-size: 10px; font-weight: bold; text-transform: uppercase; margin-bottom: 8px; display: block; }
        .val { font-size: 13px; font-weight: 500; }

        /* Highlighted Delivery Section */
        .delivery-box { 
            background-color: #f1f2f6; 
            padding: 15px; 
            border-radius: 8px; 
            border-left: 4px solid #0984e3;
        }
        .status-badge { 
            display: inline-block;
            background: #0984e3; 
            color: #fff; 
            padding: 2px 10px; 
            border-radius: 12px; 
            font-size: 10px; 
            font-weight: bold;
        }

        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th { 
            text-align: left; 
            padding: 12px; 
            border-bottom: 2px solid #2d3436; 
            font-size: 11px; 
            text-transform: uppercase; 
            color: #636e72;
        }
        .items-table td { padding: 15px 12px; border-bottom: 1px solid #f1f2f6; font-size: 13px; }
        .item-name { font-weight: bold; color: #2d3436; }
        .item-meta { font-size: 11px; color: #4b5261; }

        /* Totals Area */
        .summary-container { margin-top: 30px; float: right; width: 300px; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { padding: 8px 0; font-size: 14px; }
        .summary-table .grand-total-row td { 
            padding-top: 15px; 
            border-top: 1px solid #dfe6e9; 
            font-size: 20px; 
            font-weight: bold; 
            color: #0984e3; 
        }

        .footer { 
            position: absolute; 
            bottom: 40px; 
            left: 40px; 
            right: 40px; 
            text-align: center; 
            font-size: 11px; 
            color: #b2bec3;
            border-top: 1px solid #f1f2f6;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="brand-section">
            <h1 class="brand-title">MYSHOP</h1>
            <p class="brand-sub">123 Business Avenue, Saharanpur, UP 247001</p>
        </div>
        <div class="invoice-section">
            <h2 class="invoice-label">INVOICE</h2>
            <div class="invoice-id">#{{ $order->unique_order_id }}</div>
            <div style="font-size: 12px; color: #636e72;">Date: {{ $order->created_at->format('d M, Y') }}</div>
        </div>
        <div class="clearfix"></div>
    </div>

    <table class="info-grid">
        <tr>
            <td class="info-col">
                <span class="label">Billed To</span>
                <div class="val">
                    <strong>{{ $customer->name ?? 'Customer' }}</strong><br>
                    {{ $order->address }}<br>
                    Ph: {{ $customer->phone ?? 'N/A' }}
                </div>
            </td>
            <td class="info-col">
                <span class="label">Order Status</span>
                <div class="val">
                    <span class="status-badge">{{ strtoupper($order->status) }}</span>
                </div>
            </td>
            <td class="info-col">
                <div class="delivery-box">
                    <span class="label" style="color: #0984e3;">Delivered Date</span>
                    <div class="val" style="font-size: 16px; color: #2d3436;">
                        {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M, Y') : 'Processing' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="width: 60px;">Qty</th>
                <th style="width: 100px;">Price</th>
                <th style="width: 100px; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    <div class="item-name">{{ $item->product->name }}</div>
                    @php $attrValue = $item->variant->attributeValues->first(); @endphp
                    @if($attrValue)
                        <div class="item-meta">{{ $attrValue->attribute->name }}: {{ $item->variant->name }}</div>
                    @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td>Rs. {{ number_format($item->price, 2) }}</td>
                <td style="text-align: right; font-weight: bold;">Rs. {{ number_format($item->quantity * $item->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-container">
        <table class="summary-table">
            <tr>
                <td style="color: #636e72;">Subtotal</td>
                <td style="text-align: right;">Rs. {{ number_format(($order->total_amount) + ($order->discount_amount), 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <td style="color: #e17055;">Discount ({{ $order->coupon_code }})</td>
                <td style="text-align: right; color: #e17055;">-Rs. {{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="grand-total-row">
                <td>Total</td>
                <td style="text-align: right;">Rs. {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <strong>Thank you for your business!</strong><br>
        For support, please reach out to support@myshop.com. This is a computer-generated invoice.
    </div>
</body>
</html>
