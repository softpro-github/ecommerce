<x-layouts.storefront title="Cart">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="page-title text-center mb-12">Your Cart</h1>

        @if(session('status'))
            <div class="mb-8 rounded-full border border-accent text-accent text-sm px-4 py-3 text-center">
                {{ session('status') }}
            </div>
        @endif

        @if($items->isEmpty())
            <p class="text-muted text-center">Your cart is empty. <a href="{{ route('shop.index') }}" class="text-accent">Continue shopping</a>.</p>
        @else
            <div class="divide-y divide-black/10 border-t border-b border-black/10">
                @foreach($items as $item)
                    <div class="flex items-center justify-between py-6 gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-panel overflow-hidden shrink-0">
                                @if($item['product']->images->first())
                                    <img src="{{ asset('storage/' . $item['product']->images->first()->path) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('shop.show', $item['product']->slug) }}" class="text-sm text-ink hover:text-accent">{{ $item['product']->name }}</a>
                                @if($item['variant'])
                                    <p class="text-muted text-xs mt-1">{{ trim(($item['variant']->size ?? '').' '.($item['variant']->color ?? '')) }}</p>
                                @endif
                                <p class="text-muted text-xs mt-1">Qty: {{ $item['qty'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="text-accent font-medium">&#8358;{{ number_format($item['product']->price * $item['qty']) }}</span>
                            <form method="POST" action="{{ route('cart.remove', $item['key']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-muted hover:text-ink text-xs uppercase tracking-widest">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between mt-8">
                <span class="uppercase tracking-widest text-sm text-muted">Total</span>
                <span class="text-xl text-accent font-semibold">&#8358;{{ number_format($total) }}</span>
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('checkout.index') }}" class="btn-primary">Proceed To Checkout</a>
            </div>
        @endif
    </div>

</x-layouts.storefront>
