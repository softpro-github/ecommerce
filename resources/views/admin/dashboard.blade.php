<x-layouts.admin title="Dashboard">

    <h1 class="text-2xl font-bold mb-8">Dashboard</h1>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
        <div class="bg-white rounded shadow p-6 text-center">
            <p class="text-3xl font-bold">{{ \App\Models\Product::count() }}</p>
            <p class="text-xs uppercase tracking-widest text-neutral-500 mt-2">Products</p>
        </div>
        <div class="bg-white rounded shadow p-6 text-center">
            <p class="text-3xl font-bold">{{ \App\Models\Order::count() }}</p>
            <p class="text-xs uppercase tracking-widest text-neutral-500 mt-2">Orders</p>
        </div>
        <div class="bg-white rounded shadow p-6 text-center">
            <p class="text-3xl font-bold">{{ \App\Models\User::where('role', 'customer')->count() }}</p>
            <p class="text-xs uppercase tracking-widest text-neutral-500 mt-2">Customers</p>
        </div>
        <div class="bg-white rounded shadow p-6 text-center">
            <p class="text-3xl font-bold">&#8358;{{ number_format(\App\Models\Order::where('status', 'paid')->sum('total')) }}</p>
            <p class="text-xs uppercase tracking-widest text-neutral-500 mt-2">Revenue</p>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6">
        <h2 class="font-semibold mb-4">Recent Orders</h2>
        <table class="w-full text-sm">
            <thead class="text-left text-xs uppercase tracking-widest text-neutral-500">
                <tr>
                    <th class="py-2">Order</th>
                    <th class="py-2">Customer</th>
                    <th class="py-2">Total</th>
                    <th class="py-2">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse(\App\Models\Order::latest()->take(5)->get() as $order)
                    <tr>
                        <td class="py-2">#{{ $order->id }}</td>
                        <td class="py-2">{{ $order->shipping_name }}</td>
                        <td class="py-2">&#8358;{{ number_format($order->total) }}</td>
                        <td class="py-2">{{ ucfirst($order->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-neutral-400">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
