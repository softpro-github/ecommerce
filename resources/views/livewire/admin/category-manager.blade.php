<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Categories</h1>
        @unless($showForm)
            <button wire:click="create" class="bg-black text-white px-5 py-2 text-sm uppercase tracking-widest">Add Category</button>
        @endunless
    </div>

    @if(session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 text-sm">{{ session('status') }}</div>
    @endif

    @if($showForm)
        <form wire:submit="save" class="bg-white rounded shadow p-6 mb-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Name</label>
                    <input type="text" wire:model="name" class="w-full border rounded px-3 py-2">
                    @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Parent Category</label>
                    <select wire:model="parent_id" class="w-full border rounded px-3 py-2">
                        <option value="">— None (Top Level) —</option>
                        @foreach($categoryList as $cat)
                            @if($cat->id !== $editingId)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Sort Order</label>
                    <input type="number" wire:model="sort_order" class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="enabled"> Enabled</label>
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Description</label>
                <textarea wire:model="description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
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
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Parent</th>
                    <th class="px-4 py-3">Enabled</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($categoryList as $cat)
                    <tr>
                        <td class="px-4 py-3">{{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}</td>
                        <td class="px-4 py-3">{{ $cat->parent?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $cat->enabled ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <button wire:click="edit({{ $cat->id }})" class="text-blue-600 text-xs uppercase tracking-widest">Edit</button>
                            <button wire:click="delete({{ $cat->id }})" wire:confirm="Delete this category?" class="text-red-600 text-xs uppercase tracking-widest">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
