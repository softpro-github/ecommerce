<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Products</h1>
        @unless($showForm)
            <button wire:click="create" class="bg-black text-white px-5 py-2 text-sm uppercase tracking-widest">Add Product</button>
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
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">SKU</label>
                    <input type="text" wire:model="sku" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Category</label>
                    <select wire:model="category_id" class="w-full border rounded px-3 py-2">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Price (₦)</label>
                    <input type="number" step="0.01" wire:model="price" class="w-full border rounded px-3 py-2">
                    @error('price') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Compare-at Price (₦)</label>
                    <input type="number" step="0.01" wire:model="compare_at_price" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Stock Qty</label>
                    <input type="number" wire:model="stock_qty" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Status</label>
                    <select wire:model="status" class="w-full border rounded px-3 py-2">
                        <option value="draft">Draft</option>
                        <option value="active">Active</option>
                        <option value="sold_out">Sold Out</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-8">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="featured"> Featured</label>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="bestseller"> Bestseller</label>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Description</label>
                <textarea wire:model="description" rows="4" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Sizes &amp; Colors <span class="normal-case text-neutral-400">(optional)</span></label>
                <p class="text-xs text-neutral-400 mb-3">Only needed if this product comes in multiple sizes/colors. Leave blank for a simple single-stock product.</p>

                <div class="mb-4">
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-2">Sizes</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($sizeOptions as $size)
                            <label class="flex items-center gap-1.5 text-sm border rounded px-3 py-1.5 cursor-pointer {{ in_array($size, $selectedSizes) ? 'border-black bg-black text-white' : 'border-neutral-300' }}">
                                <input type="checkbox" wire:model.live="selectedSizes" value="{{ $size }}" class="sr-only">
                                {{ $size }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-3 mb-4">
                    <div class="flex-1 flex gap-2">
                        <input type="text" wire:model="quickColors" placeholder="Colors, comma separated e.g. Black, Red" class="flex-1 border rounded px-3 py-2 text-sm">
                        <button type="button" wire:click="addQuickColors" class="text-xs text-blue-600 uppercase tracking-widest whitespace-nowrap px-2">+ Add Colors</button>
                    </div>
                </div>

                @if(count($variants))
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs uppercase tracking-widest text-neutral-500">Variant Rows</label>
                        <button type="button" wire:click="addVariantRow" class="text-xs text-blue-600 uppercase tracking-widest">+ Add Row Manually</button>
                    </div>
                    @foreach($variants as $index => $variant)
                        <div class="flex gap-3 mb-2 items-center">
                            <input type="text" placeholder="Size" wire:model="variants.{{ $index }}.size" class="border rounded px-3 py-2 w-24">
                            <input type="text" placeholder="Color" wire:model="variants.{{ $index }}.color" class="border rounded px-3 py-2 w-32">
                            <input type="number" placeholder="Stock" wire:model="variants.{{ $index }}.stock_qty" class="border rounded px-3 py-2 w-24">
                            <button type="button" wire:click="removeVariantRow({{ $index }})" class="text-red-500 text-xs">Remove</button>
                        </div>
                    @endforeach
                @else
                    <button type="button" wire:click="addVariantRow" class="text-xs text-blue-600 uppercase tracking-widest">+ Add Row Manually</button>
                @endif
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Add Images</label>
                <input type="file" wire:model="newImages" multiple class="w-full border rounded px-3 py-2">
                @error('newImages.*') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                @if($editingId)
                    <div class="flex gap-3 mt-3 flex-wrap">
                        @foreach(\App\Models\Product::find($editingId)?->images ?? [] as $img)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $img->path) }}" class="w-20 h-20 object-cover rounded border">
                                <button type="button" wire:click="deleteImage({{ $img->id }})" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 text-xs">&times;</button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-black text-white px-6 py-2 text-sm uppercase tracking-widest">Save</button>
                <button type="button" wire:click="cancel" class="border px-6 py-2 text-sm uppercase tracking-widest">Cancel</button>
            </div>
        </form>
    @endif

    <div class="bg-white rounded shadow">
        <div class="p-4 border-b">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..." class="border rounded px-3 py-2 w-full max-w-sm">
        </div>
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 text-left text-xs uppercase tracking-widest text-neutral-500">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-3 flex items-center gap-3">
                            @if($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="w-10 h-10 object-cover rounded">
                            @else
                                <div class="w-10 h-10 bg-neutral-200 rounded"></div>
                            @endif
                            {{ $product->name }}
                        </td>
                        <td class="px-4 py-3">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3">&#8358;{{ number_format($product->price) }}</td>
                        <td class="px-4 py-3">{{ $product->stock_qty }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs uppercase tracking-widest
                                {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-neutral-200 text-neutral-600' }}">
                                {{ $product->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <button wire:click="edit({{ $product->id }})" class="text-blue-600 text-xs uppercase tracking-widest">Edit</button>
                            <button wire:click="delete({{ $product->id }})" wire:confirm="Delete this product?" class="text-red-600 text-xs uppercase tracking-widest">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $products->links() }}</div>
    </div>
</div>
