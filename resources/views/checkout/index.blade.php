<x-layouts.storefront title="Checkout">

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="page-title text-center mb-12">Checkout</h1>

        @if($errors->any())
            <div class="mb-8 rounded-2xl border border-red-300 bg-red-50 text-red-600 text-sm px-4 py-3">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-10 divide-y divide-black/10 border-t border-b border-black/10">
            @foreach($items as $item)
                <div class="flex items-center justify-between py-4">
                    <span class="text-sm text-ink">
                        {{ $item['product']->name }}
                        @if($item['variant'])
                            <span class="text-muted">({{ trim(($item['variant']->size ?? '').' '.($item['variant']->color ?? '')) }})</span>
                        @endif
                        &times; {{ $item['qty'] }}
                    </span>
                    <span class="text-accent">&#8358;{{ number_format($item['unit_price'] * $item['qty']) }}</span>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between text-sm text-muted mb-2">
            <span>Subtotal</span>
            <span>&#8358;{{ number_format($subtotal) }}</span>
        </div>
        <div class="flex justify-between text-sm text-muted mb-10">
            <span>Shipping</span>
            <span>&#8358;{{ number_format($shippingFee) }}</span>
        </div>

        <form method="POST" action="{{ route('checkout.store') }}" class="space-y-5" x-data="{ useMyAddress: {{ old('use_customer_address_for_shipping', '1') ? 'true' : 'false' }} }">
            @csrf

            <div>
                <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Full Name</label>
                <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name ?? '') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Email</label>
                <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Phone</label>
                <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
            </div>

            <div class="pt-2">
                <h2 class="text-xs uppercase tracking-widest text-muted mb-3">Address</h2>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Country</label>
                        <input type="text" name="customer_country" value="{{ old('customer_country', 'Nigeria') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">State</label>
                        <input type="text" name="customer_state" value="{{ old('customer_state') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Town</label>
                        <input type="text" name="customer_city" value="{{ old('customer_city') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Street Address *</label>
                        <input type="text" name="customer_street" value="{{ old('customer_street') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="use_customer_address_for_shipping" value="1" x-model="useMyAddress" class="rounded border-black/20 text-accent focus:ring-accent">
                    Use this address for shipping
                </label>
            </div>

            <div x-show="!useMyAddress" x-cloak class="pt-2">
                <h2 class="text-xs uppercase tracking-widest text-muted mb-3">Shipping Address</h2>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Country</label>
                        <input type="text" name="shipping_country" value="{{ old('shipping_country', 'Nigeria') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">State</label>
                        <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Town</label>
                        <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Street Address *</label>
                        <input type="text" name="shipping_street" value="{{ old('shipping_street') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Coupon Code (optional)</label>
                <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 uppercase focus:border-accent focus:ring-accent">
            </div>

            <button type="submit" class="btn-primary w-full">Pay With Flutterwave</button>
        </form>
    </div>

</x-layouts.storefront>
