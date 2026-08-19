<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderInvoiceController extends Controller
{
    public function download(Order $order)
    {
        $order->load(['items', 'user', 'coupon']);

        $pdf = Pdf::loadView('admin.orders.invoice', ['order' => $order])
            ->setPaper('a4');

        return $pdf->download("invoice-order-{$order->id}.pdf");
    }
}
