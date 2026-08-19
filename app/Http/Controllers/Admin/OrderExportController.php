<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderExportController extends Controller
{
    public function export(Request $request): StreamedResponse
    {
        $query = Order::query()
            ->with('items')
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('date_from'), fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($request->query('date_to'), fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->when($request->query('search'), function ($q, $term) {
                $q->where(function ($q) use ($term) {
                    $q->where('shipping_name', 'like', "%{$term}%")
                        ->orWhere('customer_email', 'like', "%{$term}%")
                        ->orWhere('shipping_phone', 'like', "%{$term}%")
                        ->orWhere('tx_ref', 'like', "%{$term}%")
                        ->orWhere('flutterwave_tx_id', 'like', "%{$term}%");

                    if (is_numeric($term)) {
                        $q->orWhere('id', (int) $term);
                    }
                });
            })
            ->latest();

        $filename = 'orders-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'Order #', 'Date', 'Status', 'Customer Name', 'Email', 'Phone',
                'Items', 'Subtotal', 'Discount', 'Shipping', 'Total', 'Currency',
                'Payment Ref', 'Shipping Address',
            ]);

            $query->chunk(200, function ($orders) use ($out) {
                foreach ($orders as $order) {
                    fputcsv($out, [
                        $order->id,
                        $order->created_at->format('Y-m-d H:i'),
                        ucfirst($order->status),
                        $order->shipping_name,
                        $order->customer_email,
                        $order->shipping_phone,
                        $order->items->map(fn ($i) => "{$i->qty}x {$i->product_name}")->implode('; '),
                        $order->subtotal,
                        $order->discount,
                        $order->shipping_fee,
                        $order->total,
                        $order->currency,
                        $order->tx_ref,
                        $order->fullShippingAddress(),
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
