<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">FAQs</h1>
        @unless($showForm)
            <button wire:click="create" class="bg-black text-white px-5 py-2 text-sm uppercase tracking-widest">Add FAQ</button>
        @endunless
    </div>

    @if(session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 text-sm">{{ session('status') }}</div>
    @endif

    @if($showForm)
        <form wire:submit="save" class="bg-white rounded shadow p-6 mb-8 space-y-6 max-w-2xl">
            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Question</label>
                <input type="text" wire:model="question" class="w-full border rounded px-3 py-2">
                @error('question') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Answer</label>
                <textarea wire:model="answer" rows="4" class="w-full border rounded px-3 py-2"></textarea>
                @error('answer') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Sort Order</label>
                    <input type="number" wire:model="sort_order" class="w-full border rounded px-3 py-2">
                    <p class="text-neutral-400 text-xs mt-1">Lower numbers show first.</p>
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="enabled"> Enabled (shown on site)</label>
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
                    <th class="px-4 py-3">Question</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($faqs as $faq)
                    <tr>
                        <td class="px-4 py-3">{{ $faq->question }}</td>
                        <td class="px-4 py-3">{{ $faq->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs uppercase tracking-widest {{ $faq->enabled ? 'bg-green-100 text-green-700' : 'bg-neutral-200 text-neutral-600' }}">
                                {{ $faq->enabled ? 'Enabled' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <button wire:click="edit({{ $faq->id }})" class="text-blue-600 text-xs uppercase tracking-widest">Edit</button>
                            <button wire:click="delete({{ $faq->id }})" wire:confirm="Delete this FAQ?" class="text-red-600 text-xs uppercase tracking-widest">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-neutral-400 text-sm">No FAQs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
