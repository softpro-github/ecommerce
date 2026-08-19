<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Orders</h1>
        <a href="{{ route('admin.orders.export', ['search' => $search, 'status' => $statusFilter, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
           class="bg-black text-white px-4 py-2 text-xs uppercase tracking-widest">
            Export CSV
        </a>
    </div>

    @if(session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 text-sm">{{ session('status') }}</div>
    @endif

    <div class="bg-white rounded shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
        <div class="md:col-span-2">
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Search</label>
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="Order #, name, email, phone, tx ref…" class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Status</label>
            <select wire:model.live="statusFilter" class="w-full border rounded px-3 py-2 text-sm">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
                <option value="refunded">Refunded</option>
                <option value="failed">Failed</option>
            </select>
        </div>
        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">From</label>
            <input type="date" wire:model.live="dateFrom" class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div class="flex gap-2">
            <div class="flex-1">
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">To</label>
                <input type="date" wire:model.live="dateTo" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <button wire:click="resetFilters" class="border rounded px-3 py-2 text-xs uppercase tracking-widest text-neutral-500 hover:text-black hover:border-black transition-colors">
                Reset
            </button>
        </div>
    </div>

    <div class="bg-white rounded shadow">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 text-left text-xs uppercase tracking-widest text-neutral-500">
                <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-4 py-3">
                            #{{ $order->id }}
                            @if($order->tx_ref)
                                <br><span class="text-[10px] text-neutral-400">{{ $order->tx_ref }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $order->shipping_name }}<br><span class="text-xs text-neutral-400">{{ $order->customer_email }}</span></td>
                        <td class="px-4 py-3">&#8358;{{ number_format($order->total) }}</td>
                        <td class="px-4 py-3">
                            <select wire:change="updateStatus({{ $order->id }}, $event.target.value)" class="border rounded px-2 py-1 text-xs">
                                @foreach(['pending','paid','processing','shipped','delivered','cancelled','refunded','failed'] as $s)
                                    <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="text-neutral-600 hover:text-black text-xs uppercase tracking-widest mr-3">
                                Download
                            </a>
                            <button wire:click="view({{ $order->id }})" class="text-blue-600 text-xs uppercase tracking-widest">
                                {{ $viewingId === $order->id ? 'Hide' : 'View' }}
                            </button>
                        </td>
                    </tr>
                    @if($viewingId === $order->id)
                        <tr>
                            <td colspan="6" class="px-4 py-4 bg-neutral-50">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-widest text-neutral-500 mb-2">Shipping Address</p>
                                        <p class="text-sm">{{ $order->shipping_name }}</p>
                                        <p class="text-sm">{{ $order->shipping_phone }}</p>
                                        <p class="text-sm">{{ $order->fullShippingAddress() }}</p>
                                    </div>

                                    @unless($order->ships_to_customer_address)
                                        <div>
                                            <p class="text-xs uppercase tracking-widest text-neutral-500 mb-2">Customer's Own Address</p>
                                            <p class="text-sm">
                                                {{ collect([$order->customer_street, $order->customer_city, $order->customer_state, $order->customer_country])->filter()->implode(', ') }}
                                            </p>
                                        </div>
                                    @endunless

                                    <div>
                                        <p class="text-xs uppercase tracking-widest text-neutral-500 mb-2">Payment</p>
                                        <p class="text-sm">Ref: {{ $order->tx_ref ?: '—' }}</p>
                                        <p class="text-sm">Transaction ID: {{ $order->flutterwave_tx_id ?: '—' }}</p>
                                        <p class="text-sm">Currency: {{ $order->currency }}</p>
                                    </div>

                                    @if($order->coupon)
                                        <div>
                                            <p class="text-xs uppercase tracking-widest text-neutral-500 mb-2">Coupon</p>
                                            <p class="text-sm">{{ $order->coupon->code }} (&#8358;{{ number_format($order->discount) }} off)</p>
                                        </div>
                                    @endif
                                </div>

                                <p class="text-xs uppercase tracking-widest text-neutral-500 mb-2">Items</p>
                                <ul class="text-sm space-y-1 mb-4">
                                    @foreach($order->items as $item)
                                        <li>
                                            {{ $item->qty }} &times; {{ $item->product_name }}
                                            — &#8358;{{ number_format($item->subtotal) }}
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="text-sm max-w-xs ml-auto space-y-1">
                                    <div class="flex justify-between text-neutral-500"><span>Subtotal</span><span>&#8358;{{ number_format($order->subtotal) }}</span></div>
                                    @if($order->discount > 0)
                                        <div class="flex justify-between text-neutral-500"><span>Discount</span><span>-&#8358;{{ number_format($order->discount) }}</span></div>
                                    @endif
                                    <div class="flex justify-between text-neutral-500"><span>Shipping</span><span>&#8358;{{ number_format($order->shipping_fee) }}</span></div>
                                    <div class="flex justify-between font-semibold border-t pt-1"><span>Total</span><span>&#8358;{{ number_format($order->total) }}</span></div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-neutral-400 text-sm">No orders match your filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $orders->links() }}</div>
    </div>
</div>
