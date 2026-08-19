<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #111; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        .header-table td { vertical-align: top; }
        .brand { font-size: 20px; font-weight: bold; letter-spacing: 1px; }
        .muted { color: #777; }
        .accent { color: #b8860b; }
        .section-title { text-transform: uppercase; letter-spacing: 1px; font-size: 10px; color: #777; margin-bottom: 4px; }
        .items-table th { text-align: left; text-transform: uppercase; font-size: 9px; letter-spacing: 1px; color: #777; border-bottom: 1px solid #ddd; padding: 6px 0; }
        .items-table td { padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
        .text-right { text-align: right; }
        .totals-table td { padding: 3px 0; }
        .totals-table .grand { font-size: 14px; font-weight: bold; border-top: 1px solid #111; padding-top: 8px; }
        .status-badge { display: inline-block; padding: 3px 10px; border: 1px solid #111; text-transform: uppercase; font-size: 9px; letter-spacing: 1px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="brand">CityStyleWears</div>
                <div class="muted">Your Style Our Priority</div>
            </td>
            <td class="text-right">
                <div class="section-title">Invoice</div>
                <div style="font-size: 16px; font-weight: bold;">#{{ $order->id }}</div>
                <div class="muted">{{ $order->created_at->format('M d, Y') }}</div>
                <div style="margin-top: 6px;"><span class="status-badge">{{ ucfirst($order->status) }}</span></div>
            </td>
        </tr>
    </table>

    <table class="header-table" style="margin-top: 28px;">
        <tr>
            <td width="50%">
                <div class="section-title">Bill To</div>
                <div>{{ $order->shipping_name }}</div>
                <div>{{ $order->customer_email }}</div>
                <div>{{ $order->shipping_phone }}</div>
            </td>
            <td width="50%">
                <div class="section-title">Ship To</div>
                <div>{{ $order->shipping_name }}</div>
                <div>{{ $order->fullShippingAddress() }}</div>
            </td>
        </tr>
    </table>

    <table class="header-table" style="margin-top: 20px;">
        <tr>
            <td width="50%">
                <div class="section-title">Payment Reference</div>
                <div>{{ $order->tx_ref ?: '—' }}</div>
            </td>
            <td width="50%">
                <div class="section-title">Transaction ID</div>
                <div>{{ $order->flutterwave_tx_id ?: '—' }}</div>
            </td>
        </tr>
    </table>

    <table class="items-table" style="margin-top: 28px;">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">&#8358;{{ number_format($item->unit_price) }}</td>
                    <td class="text-right">&#8358;{{ number_format($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table" style="margin-top: 16px;">
        <tr>
            <td width="70%"></td>
            <td width="15%" class="muted">Subtotal</td>
            <td width="15%" class="text-right">&#8358;{{ number_format($order->subtotal) }}</td>
        </tr>
        @if($order->discount > 0)
        <tr>
            <td></td>
            <td class="muted">Discount @if($order->coupon)({{ $order->coupon->code }})@endif</td>
            <td class="text-right">-&#8358;{{ number_format($order->discount) }}</td>
        </tr>
        @endif
        <tr>
            <td></td>
            <td class="muted">Shipping</td>
            <td class="text-right">&#8358;{{ number_format($order->shipping_fee) }}</td>
        </tr>
        <tr>
            <td></td>
            <td class="grand">Total</td>
            <td class="text-right grand accent">&#8358;{{ number_format($order->total) }}</td>
        </tr>
    </table>

    <p class="muted" style="margin-top: 40px; font-size: 10px;">
        Thank you for shopping with CityStyleWears. This is a computer-generated invoice.
    </p>
</body>
</html>
