<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FlutterwaveWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $signature = $request->header('verif-hash');

        if (! $signature || $signature !== config('flutterwave.secret_hash')) {
            abort(401);
        }

        $payload = $request->all();
        $data = $payload['data'] ?? [];

        $order = Order::query()->where('tx_ref', $data['tx_ref'] ?? null)->first();

        if (! $order) {
            return response('ok');
        }

        if (($data['status'] ?? null) === 'successful'
            && (float) ($data['amount'] ?? 0) >= (float) $order->total
            && ($data['currency'] ?? null) === $order->currency
            && $order->status !== 'paid'
        ) {
            DB::transaction(function () use ($order, $data) {
                $order->update([
                    'status' => 'paid',
                    'flutterwave_tx_id' => (string) ($data['id'] ?? ''),
                ]);

                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->decrement('stock_qty', min($item->qty, $item->variant->stock_qty));
                    } elseif ($item->product) {
                        $item->product->decrement('stock_qty', min($item->qty, $item->product->stock_qty));
                    }
                }
            });

            try {
                Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        Log::info('Flutterwave webhook processed', ['tx_ref' => $data['tx_ref'] ?? null]);

        return response('ok');
    }
}
