<x-layouts.storefront title="Order Confirmed">

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <p class="font-script text-3xl text-accent mb-4">Payment Successful</p>
        <h1 class="page-title mb-6">Thank You For Your Order</h1>
        <p class="text-muted mb-2">Order #{{ $order->id }}</p>
        <p class="text-muted mb-10">A confirmation has been sent to {{ $order->customer_email }}.</p>
        <a href="{{ route('shop.index') }}" class="btn-primary">Continue Shopping</a>
    </div>

</x-layouts.storefront>
