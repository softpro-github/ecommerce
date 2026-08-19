<x-layouts.storefront title="Payment Failed">

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <p class="text-red-500 uppercase tracking-widest text-xs mb-4">Payment Failed</p>
        <h1 class="page-title mb-6">Something Went Wrong</h1>
        <p class="text-muted mb-10">Your payment for order #{{ $order->id }} could not be confirmed. Please try again or contact support.</p>
        <a href="{{ route('cart.index') }}" class="btn-dark">Back To Cart</a>
    </div>

</x-layouts.storefront>
