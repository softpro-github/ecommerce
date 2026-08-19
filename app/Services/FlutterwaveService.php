<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class FlutterwaveService
{
    public function initiatePayment(Order $order): string
    {
        $response = Http::withToken(config('flutterwave.secret_key'))
            ->post(config('flutterwave.base_url').'/payments', [
                'tx_ref' => $order->tx_ref,
                'amount' => (string) $order->total,
                'currency' => $order->currency,
                'redirect_url' => route('checkout.callback'),
                'customer' => [
                    'email' => $order->customer_email,
                    'phonenumber' => $order->shipping_phone,
                    'name' => $order->shipping_name,
                ],
                'customizations' => [
                    'title' => config('app.name'),
                    'description' => "Payment for order #{$order->id}",
                ],
            ])
            ->throw()
            ->json();

        return $response['data']['link'];
    }

    public function verifyTransaction(string $transactionId): array
    {
        return Http::withToken(config('flutterwave.secret_key'))
            ->get(config('flutterwave.base_url')."/transactions/{$transactionId}/verify")
            ->throw()
            ->json();
    }
}
