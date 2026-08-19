<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Services\FlutterwaveService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private function buildCartItems(): \Illuminate\Support\Collection
    {
        $cart = session('cart', []);

        return collect($cart)->map(function ($line) {
            if (! is_array($line)) {
                return null;
            }

            $product = Product::find($line['product_id']);

            if (! $product) {
                return null;
            }

            $variant = $line['variant_id'] ? ProductVariant::find($line['variant_id']) : null;
            $unitPrice = $variant?->price_override ?? $product->price;

            return [
                'product' => $product,
                'variant' => $variant,
                'qty' => $line['qty'],
                'unit_price' => $unitPrice,
            ];
        })->filter()->values();
    }

    public function index(): View|RedirectResponse
    {
        $items = $this->buildCartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $items->sum(fn ($item) => $item['unit_price'] * $item['qty']);
        $shippingFee = (float) Setting::get('shipping_fee', '0');

        return view('checkout.index', compact('items', 'subtotal', 'shippingFee'));
    }

    public function store(Request $request, FlutterwaveService $flutterwave): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:50',
            'customer_email' => 'required|email',
            'coupon_code' => 'nullable|string',

            'customer_country' => 'required|string|max:100',
            'customer_state' => 'required|string|max:100',
            'customer_city' => 'required|string|max:100',
            'customer_street' => 'required|string|max:255',

            'use_customer_address_for_shipping' => 'nullable|boolean',

            'shipping_country' => 'required_unless:use_customer_address_for_shipping,1|nullable|string|max:100',
            'shipping_state' => 'required_unless:use_customer_address_for_shipping,1|nullable|string|max:100',
            'shipping_city' => 'required_unless:use_customer_address_for_shipping,1|nullable|string|max:100',
            'shipping_street' => 'required_unless:use_customer_address_for_shipping,1|nullable|string|max:255',
        ]);

        $useCustomerAddress = $request->boolean('use_customer_address_for_shipping');

        if ($useCustomerAddress) {
            $validated['shipping_country'] = $validated['customer_country'];
            $validated['shipping_state'] = $validated['customer_state'];
            $validated['shipping_city'] = $validated['customer_city'];
            $validated['shipping_street'] = $validated['customer_street'];
        }

        $items = $this->buildCartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $items->sum(fn ($item) => $item['unit_price'] * $item['qty']);
        $shippingFee = (float) Setting::get('shipping_fee', '0');

        $discount = 0;
        $coupon = null;

        if (! empty($validated['coupon_code'])) {
            $coupon = Coupon::query()->where('code', strtoupper($validated['coupon_code']))->first();

            if ($coupon && $coupon->isValid() && (! $coupon->min_order_amount || $subtotal >= $coupon->min_order_amount)) {
                $discount = $coupon->type === 'percent'
                    ? $subtotal * ($coupon->value / 100)
                    : min($coupon->value, $subtotal);
            } else {
                $coupon = null;
            }
        }

        $total = max(0, $subtotal - $discount) + $shippingFee;

        $order = DB::transaction(function () use ($validated, $items, $subtotal, $discount, $shippingFee, $total, $coupon, $useCustomerAddress) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'coupon_id' => $coupon?->id,
                'tx_ref' => 'CSW-'.Str::upper(Str::random(12)),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'currency' => 'NGN',
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'customer_email' => $validated['customer_email'],
                'customer_country' => $validated['customer_country'],
                'customer_state' => $validated['customer_state'],
                'customer_city' => $validated['customer_city'],
                'customer_street' => $validated['customer_street'],
                'ships_to_customer_address' => $useCustomerAddress,
                'shipping_country' => $validated['shipping_country'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_address' => $validated['shipping_street'],
            ]);

            foreach ($items as $item) {
                $variantLabel = $item['variant']
                    ? ' ('.trim(($item['variant']->size ?? '').' '.($item['variant']->color ?? '')).')'
                    : '';

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_variant_id' => $item['variant']?->id,
                    'product_name' => $item['product']->name.$variantLabel,
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['unit_price'] * $item['qty'],
                ]);
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            return $order;
        });

        session(['pending_order_id' => $order->id]);

        try {
            $paymentLink = $flutterwave->initiatePayment($order);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            report($e);

            return redirect()->route('checkout.index')
                ->withErrors(['payment' => 'Unable to reach the payment gateway right now. Please try again shortly.']);
        }

        return redirect()->away($paymentLink);
    }

    public function callback(Request $request, FlutterwaveService $flutterwave): View|RedirectResponse
    {
        $txRef = $request->query('tx_ref');
        $transactionId = $request->query('transaction_id');
        $status = $request->query('status');

        $order = Order::query()->where('tx_ref', $txRef)->first();

        if (! $order) {
            abort(404);
        }

        if ($status !== 'successful' || ! $transactionId) {
            $order->update(['status' => 'failed']);

            return view('checkout.failed', compact('order'));
        }

        $verification = $flutterwave->verifyTransaction($transactionId);

        $data = $verification['data'] ?? [];
        $verified = ($verification['status'] ?? null) === 'success'
            && ($data['status'] ?? null) === 'successful'
            && ($data['tx_ref'] ?? null) === $order->tx_ref
            && (float) ($data['amount'] ?? 0) >= (float) $order->total
            && ($data['currency'] ?? null) === $order->currency;

        if (! $verified) {
            $order->update(['status' => 'failed']);

            return view('checkout.failed', compact('order'));
        }

        if ($order->status !== 'paid') {
            DB::transaction(function () use ($order, $data) {
                $order->update([
                    'status' => 'paid',
                    'flutterwave_tx_id' => (string) $data['id'],
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

        session()->forget(['cart', 'pending_order_id']);

        return view('checkout.success', compact('order'));
    }
}
