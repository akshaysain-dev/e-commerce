{{--
    Partial: vendor/partials/_status_dropdown.blade.php
    Variables: $item (OrderItem), $st (string status)
--}}
<div class="ve-dd-menu">
    <div class="ve-dd-head">
        <strong>Update Status</strong>
        <span>Change delivery progress</span>
    </div>
    <div class="ve-dd-body">

        @foreach([
            ['pending',    '⏳', 'Pending'],
            ['processing', '🔄', 'Processing'],
            ['paid',       '💳', 'Paid'],
            ['shipped',    '📦', 'Shipped'],
            ['delivered',  '✅', 'Delivered'],
        ] as [$val, $icon, $label])
        <form action="{{ route('vendor.orders.update.status', $item->order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="{{ $val }}">
            <button type="submit" class="ve-s-btn">
                <span>{{ $icon }} {{ $label }}</span>
                @if($st === $val)
                    <span class="chk"><i class="fas fa-check"></i></span>
                @endif
            </button>
        </form>
        @endforeach

        <form action="{{ route('vendor.orders.update.status', $item->order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="ve-s-btn ve-danger">
                <span>❌ Cancelled</span>
                @if($st === 'cancelled')
                    <span class="chk"><i class="fas fa-check"></i></span>
                @endif
            </button>
        </form>

    </div>
</div>