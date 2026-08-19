<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Homepage Slides</h1>
        @unless($showForm)
            <button wire:click="create" class="bg-black text-white px-5 py-2 text-sm uppercase tracking-widest">Add Slide</button>
        @endunless
    </div>

    <div class="flex gap-2 mb-6">
        <button
            wire:click="switchType('hero')"
            class="px-4 py-2 text-xs uppercase tracking-widest rounded {{ $type === 'hero' ? 'bg-black text-white' : 'bg-white border text-neutral-500' }}"
        >Hero Slideshow</button>
        <button
            wire:click="switchType('campaign')"
            class="px-4 py-2 text-xs uppercase tracking-widest rounded {{ $type === 'campaign' ? 'bg-black text-white' : 'bg-white border text-neutral-500' }}"
        >Campaign Lookbook</button>
    </div>

    <p class="text-sm text-neutral-500 mb-6">
        @if($type === 'hero')
            The big rotating banner at the very top of the homepage. Each slide can link to a shop page or product.
        @else
            The rotating lookbook photo section shown after "Explore All Collection" on the homepage.
        @endif
    </p>

    @if(session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 text-sm">{{ session('status') }}</div>
    @endif

    @if($showForm)
        <form wire:submit="save" class="bg-white rounded shadow p-6 mb-8 space-y-6 max-w-2xl">
            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Image</label>
                <input type="file" wire:model="image" class="w-full border rounded px-3 py-2">
                @error('image') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                @if($image)
                    <img src="{{ $image->temporaryUrl() }}" class="mt-3 h-32 object-cover rounded border">
                @elseif($editingId && $slide = \App\Models\Slide::find($editingId))
                    <img src="{{ asset('storage/' . $slide->image_path) }}" class="mt-3 h-32 object-cover rounded border">
                @endif
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Heading</label>
                <input type="text" wire:model="heading" class="w-full border rounded px-3 py-2">
                @error('heading') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            @if($type === 'hero')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Button Text</label>
                        <input type="text" wire:model="cta_text" placeholder="Shop New Arrivals" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Button Link</label>
                        <input type="text" wire:model="cta_link" placeholder="/shop or https://..." class="w-full border rounded px-3 py-2">
                    </div>
                </div>
            @endif

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
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Heading</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($slides as $slide)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ asset('storage/' . $slide->image_path) }}" class="w-16 h-16 object-cover rounded">
                        </td>
                        <td class="px-4 py-3">{{ $slide->heading ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $slide->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs uppercase tracking-widest {{ $slide->enabled ? 'bg-green-100 text-green-700' : 'bg-neutral-200 text-neutral-600' }}">
                                {{ $slide->enabled ? 'Enabled' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <button wire:click="edit({{ $slide->id }})" class="text-blue-600 text-xs uppercase tracking-widest">Edit</button>
                            <button wire:click="delete({{ $slide->id }})" wire:confirm="Delete this slide?" class="text-red-600 text-xs uppercase tracking-widest">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-neutral-400 text-sm">No slides yet. Click "Add Slide" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
