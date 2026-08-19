<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmed</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f5f5; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td style="background-color:#000000; padding:32px; text-align:center;">
                            <img src="{{ ($p = \App\Models\Setting::get('logo_white_path')) ? asset('storage/'.$p) : asset('images/logo-white.png') }}" alt="CityStyleWears" style="height:56px; width:auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="color:#d4af37; text-transform:uppercase; letter-spacing:2px; font-size:11px; margin:0 0 8px;">Payment Successful</p>
                            <h1 style="font-size:22px; margin:0 0 24px; color:#111;">Thank you for your order!</h1>

                            <p style="font-size:14px; color:#555; margin:0 0 4px;">Order #{{ $order->id }}</p>
                            <p style="font-size:14px; color:#555; margin:0 0 24px;">Placed on {{ $order->created_at->format('M d, Y') }}</p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #eee; border-bottom:1px solid #eee; margin-bottom:24px;">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td style="padding:12px 0; font-size:14px; color:#111; border-bottom:1px solid #f0f0f0;">
                                            {{ $item->product_name }} &times; {{ $item->qty }}
                                        </td>
                                        <td style="padding:12px 0; font-size:14px; color:#d4af37; text-align:right; border-bottom:1px solid #f0f0f0;">
                                            &#8358;{{ number_format($item->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                <tr>
                                    <td style="font-size:13px; color:#777; padding:2px 0;">Subtotal</td>
                                    <td style="font-size:13px; color:#777; text-align:right; padding:2px 0;">&#8358;{{ number_format($order->subtotal) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <td style="font-size:13px; color:#777; padding:2px 0;">Discount</td>
                                    <td style="font-size:13px; color:#777; text-align:right; padding:2px 0;">-&#8358;{{ number_format($order->discount) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="font-size:13px; color:#777; padding:2px 0;">Shipping</td>
                                    <td style="font-size:13px; color:#777; text-align:right; padding:2px 0;">&#8358;{{ number_format($order->shipping_fee) }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size:16px; color:#111; font-weight:bold; padding:12px 0 0;">Total</td>
                                    <td style="font-size:16px; color:#d4af37; font-weight:bold; text-align:right; padding:12px 0 0;">&#8358;{{ number_format($order->total) }}</td>
                                </tr>
                            </table>

                            <p style="font-size:13px; color:#777; text-transform:uppercase; letter-spacing:1px; margin:0 0 6px;">Delivery Address</p>
                            <p style="font-size:14px; color:#111; margin:0 0 24px;">
                                {{ $order->shipping_name }}<br>
                                {{ $order->shipping_phone }}<br>
                                {{ $order->fullShippingAddress() }}
                            </p>

                            <p style="font-size:13px; color:#999; margin:24px 0 0;">
                                We'll notify you when your order ships. If you have any questions, reach out via our Customer Care page.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#000000; padding:20px; text-align:center;">
                            <p style="color:#777; font-size:11px; margin:0; text-transform:uppercase; letter-spacing:1px;">
                                &copy; {{ date('Y') }} CityStyleWears &middot; Your Style Our Priority
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
