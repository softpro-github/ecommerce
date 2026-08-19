<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);

        $items = collect($cart)->map(function ($line, $key) {
            if (! is_array($line)) {
                return null;
            }

            $product = Product::find($line['product_id']);

            if (! $product) {
                return null;
            }

            $variant = $line['variant_id'] ? ProductVariant::find($line['variant_id']) : null;

            return [
                'key' => $key,
                'product' => $product,
                'variant' => $variant,
                'qty' => $line['qty'],
            ];
        })->filter()->values();

        $total = $items->sum(fn ($item) => $item['product']->price * $item['qty']);

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $qty = max(1, (int) $request->input('qty', 1));
        $variantId = $request->input('variant_id') ?: null;

        if ($product->variants->isNotEmpty() && ! $variantId) {
            return back()->withErrors(['variant' => 'Please select a size/color option.']);
        }

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $product->id)->findOrFail($variantId);

            if ($variant->stock_qty < 1) {
                return back()->withErrors(['variant' => 'That option is out of stock.']);
            }
        }

        $key = 'p'.$product->id.($variantId ? '-v'.$variantId : '');

        $cart = session('cart', []);
        $cart[$key] = [
            'product_id' => $product->id,
            'variant_id' => $variantId,
            'qty' => ($cart[$key]['qty'] ?? 0) + $qty,
        ];
        session(['cart' => $cart]);

        return back()->with('status', "{$product->name} added to cart.");
    }

    public function remove(string $key): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        return back()->with('status', 'Item removed from cart.');
    }
}
