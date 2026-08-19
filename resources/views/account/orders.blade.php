<x-layouts.storefront title="My Orders">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="page-title text-center mb-12">My Orders</h1>

        @if($orders->isEmpty())
            <p class="text-muted text-center">You haven't placed any orders yet.</p>
        @else
            <div class="divide-y divide-black/10 border-t border-b border-black/10">
                @foreach($orders as $order)
                    <div class="flex items-center justify-between py-6">
                        <div>
                            <p class="text-sm text-ink">Order #{{ $order->id }}</p>
                            <p class="text-muted text-xs mt-1">{{ $order->created_at->format('M d, Y') }} &bull; {{ ucfirst($order->status) }}</p>
                        </div>
                        <span class="text-accent font-medium">&#8358;{{ number_format($order->total) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-layouts.storefront>
