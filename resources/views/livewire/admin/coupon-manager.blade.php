<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Coupons</h1>
        @unless($showForm)
            <button wire:click="create" class="bg-black text-white px-5 py-2 text-sm uppercase tracking-widest">Add Coupon</button>
        @endunless
    </div>

    @if(session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 text-sm">{{ session('status') }}</div>
    @endif

    @if($showForm)
        <form wire:submit="save" class="bg-white rounded shadow p-6 mb-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Code</label>
                    <input type="text" wire:model="code" class="w-full border rounded px-3 py-2 uppercase">
                    @error('code') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Type</label>
                    <select wire:model="type" class="w-full border rounded px-3 py-2">
                        <option value="percent">Percent Off</option>
                        <option value="fixed">Fixed Amount Off</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Value</label>
                    <input type="number" step="0.01" wire:model="value" class="w-full border rounded px-3 py-2">
                    @error('value') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Min Order Amount (₦)</label>
                    <input type="number" step="0.01" wire:model="min_order_amount" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Usage Limit</label>
                    <input type="number" wire:model="usage_limit" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Expires At</label>
                    <input type="date" wire:model="expires_at" class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="enabled"> Enabled</label>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-black text-white px-6 py-2 text-sm uppercase tracking-widest">Save</button>
                <button type="button" wire:click="cancel" class="border px-6 py-2 text-sm uppercase tracking-widest">Cancel</button>
            </div>
        </form>
    @endif

    <div class="bg-white rounded shadow">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 text-left text-xs uppercase tracking-widest text-neutral-500">
                <tr>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Discount</th>
                    <th class="px-4 py-3">Used</th>
                    <th class="px-4 py-3">Expires</th>
                    <th class="px-4 py-3">Enabled</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($coupons as $coupon)
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $coupon->code }}</td>
                        <td class="px-4 py-3">{{ $coupon->type === 'percent' ? $coupon->value.'%' : '₦'.number_format($coupon->value) }}</td>
                        <td class="px-4 py-3">{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / '.$coupon->usage_limit : '' }}</td>
                        <td class="px-4 py-3">{{ $coupon->expires_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $coupon->enabled ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <button wire:click="edit({{ $coupon->id }})" class="text-blue-600 text-xs uppercase tracking-widest">Edit</button>
                            <button wire:click="delete({{ $coupon->id }})" wire:confirm="Delete this coupon?" class="text-red-600 text-xs uppercase tracking-widest">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
