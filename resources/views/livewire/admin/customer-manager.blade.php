<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Customers</h1>
    </div>

    <div class="bg-white rounded shadow p-4 mb-6">
        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Search by name, email, or phone…" class="w-full max-w-sm border rounded px-3 py-2 text-sm">
    </div>

    <div class="bg-white rounded shadow">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 text-left text-xs uppercase tracking-widest text-neutral-500">
                <tr>
                    <th class="px-4 py-3 cursor-pointer select-none" wire:click="sort('name')">
                        Customer {{ $sortBy === 'name' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
                    </th>
                    <th class="px-4 py-3">Contact</th>
                    <th class="px-4 py-3 cursor-pointer select-none" wire:click="sort('created_at')">
                        Registered {{ $sortBy === 'created_at' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
                    </th>
                    <th class="px-4 py-3 cursor-pointer select-none" wire:click="sort('orders_count')">
                        Orders {{ $sortBy === 'orders_count' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
                    </th>
                    <th class="px-4 py-3 cursor-pointer select-none" wire:click="sort('total_spent')">
                        Total Spent {{ $sortBy === 'total_spent' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
                    </th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($customers as $customer)
                    <tr>
                        <td class="px-4 py-3">{{ $customer->name }}</td>
                        <td class="px-4 py-3">
                            <div>{{ $customer->email }}</div>
                            <div class="text-xs text-neutral-400">{{ $customer->phone ?: '—' }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">{{ $customer->orders_count }}</td>
                        <td class="px-4 py-3">&#8358;{{ number_format($customer->total_spent ?? 0) }}</td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="view({{ $customer->id }})" class="text-blue-600 text-xs uppercase tracking-widest">
                                {{ $viewingId === $customer->id ? 'Hide' : 'View Orders' }}
                            </button>
                        </td>
                    </tr>
                    @if($viewingId === $customer->id)
                        <tr>
                            <td colspan="6" class="px-4 py-4 bg-neutral-50">
                                @if($viewingOrders->isEmpty())
                                    <p class="text-sm text-neutral-400">No orders yet.</p>
                                @else
                                    <table class="w-full text-xs">
                                        <thead class="text-left uppercase tracking-widest text-neutral-500">
                                            <tr>
                                                <th class="pb-2">Order</th>
                                                <th class="pb-2">Date</th>
                                                <th class="pb-2">Status</th>
                                                <th class="pb-2 text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-neutral-200">
                                            @foreach($viewingOrders as $order)
                                                <tr>
                                                    <td class="py-2">#{{ $order->id }}</td>
                                                    <td class="py-2">{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td class="py-2">
                                                        <span class="px-2 py-1 rounded text-[10px] uppercase tracking-widest {{ $order->status === 'paid' || $order->status === 'delivered' ? 'bg-green-100 text-green-700' : 'bg-neutral-200 text-neutral-600' }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="py-2 text-right">&#8358;{{ number_format($order->total) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-neutral-400 text-sm">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $customers->links() }}</div>
    </div>
</div>
