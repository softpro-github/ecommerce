<x-layouts.storefront :title="$product->name">

    <div class="bg-panel">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <nav class="text-xs text-muted mb-8 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-accent">Home</a>
            <span>/</span>
            @if($product->category)
                <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-accent">{{ $product->category->name }}</a>
                <span>/</span>
            @endif
            <span class="text-ink">{{ $product->name }}</span>
        </nav>

        @if(session('status'))
            <div class="mb-8 rounded-full border border-accent text-accent text-sm px-4 py-3 text-center">
                {{ session('status') }}
            </div>
        @endif

        @error('variant')
            <div class="mb-8 rounded-full border border-red-400 bg-red-50 text-red-500 text-sm px-4 py-3 text-center">
                {{ $message }}
            </div>
        @enderror

        <div x-data="{ active: 0, images: {{ $product->images->pluck('path')->map(fn($p) => asset('storage/'.$p))->toJson() }} }" class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="flex gap-4">
                @if($product->images->count() > 1)
                    <div class="flex flex-col gap-3">
                        <template x-for="(img, i) in images" :key="i">
                            <button @click="active = i" class="w-16 h-16 rounded-xl overflow-hidden border-2 bg-white" :class="active === i ? 'border-accent' : 'border-transparent'">
                                <img :src="img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                @endif

                <div class="flex-1 aspect-square rounded-2xl bg-white overflow-hidden flex items-center justify-center">
                    @if($product->images->isNotEmpty())
                        <img :src="images[active]" class="w-full h-full object-cover">
                    @else
                        <span class="text-muted text-xs uppercase tracking-widest">No Image Yet</span>
                    @endif
                </div>
            </div>

            <div
                @if($product->variants->isNotEmpty())
                    x-data="{
                        variants: {{ $product->variants->map(fn ($v) => ['id' => $v->id, 'size' => $v->size, 'color' => $v->color, 'stock' => $v->stock_qty])->values()->toJson() }},
                        sizes: {{ $product->variants->pluck('size')->filter()->unique()->values()->toJson() }},
                        colors: {{ $product->variants->pluck('color')->filter()->unique()->values()->toJson() }},
                        selectedSize: null,
                        selectedColor: null,
                        init() {
                            if (this.sizes.length) this.selectedSize = this.sizes[0];
                            if (this.colors.length) this.selectedColor = this.colors[0];
                        },
                        get selectedVariant() {
                            return this.variants.find(v =>
                                (this.sizes.length === 0 || v.size === this.selectedSize) &&
                                (this.colors.length === 0 || v.color === this.selectedColor)
                            ) || null;
                        },
                        variantStockFor(size, color) {
                            const v = this.variants.find(v => (size === null || v.size === size) && (color === null || v.color === color));
                            return v ? v.stock : 0;
                        }
                    }"
                @endif
            >
                <h1 class="page-title mb-3">{{ $product->name }}</h1>

                <div class="flex items-center gap-3 mb-6">
                    <span class="text-accent text-2xl font-semibold">&#8358;{{ number_format($product->price) }}</span>
                    @if($product->compare_at_price)
                        <span class="text-muted line-through text-lg">&#8358;{{ number_format($product->compare_at_price) }}</span>
                    @endif
                </div>

                @if($product->description)
                    <p class="text-muted leading-relaxed mb-8">{{ $product->description }}</p>
                @endif

                @if($product->variants->isEmpty())
                    @if($product->status !== 'sold_out' && $product->stock_qty > 0)
                        <div class="mb-8">
                            <p class="text-xs text-muted mb-2">{{ $product->stock_qty }} in stock</p>
                            <div class="h-1.5 rounded-full bg-black/10 overflow-hidden max-w-xs">
                                <div class="h-full bg-accent rounded-full" style="width: {{ min(100, round($product->stock_qty / 50 * 100)) }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if($product->status === 'sold_out' || $product->stock_qty === 0)
                        <p class="rounded-full bg-panel text-muted text-xs uppercase tracking-widest inline-block px-5 py-3">Sold Out</p>
                    @else
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="flex items-center gap-4">
                            @csrf
                            <input type="number" name="qty" value="1" min="1" class="w-20 rounded-full border border-black/15 text-center py-3 text-ink">
                            <button type="submit" class="btn-primary">Add To Cart</button>
                        </form>
                    @endif
                @else
                    <template x-if="sizes.length">
                        <div class="mb-6">
                            <h3 class="text-xs uppercase tracking-widest text-muted mb-3">Size</h3>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="s in sizes" :key="s">
                                    <button
                                        type="button"
                                        @click="selectedSize = s"
                                        :disabled="variantStockFor(s, colors.length ? selectedColor : null) < 1"
                                        :class="selectedSize === s ? 'bg-black text-white border-black' : 'border-black/15 text-ink hover:border-black'"
                                        class="rounded-full border px-4 py-2 text-xs transition-colors disabled:opacity-30 disabled:cursor-not-allowed disabled:line-through"
                                        x-text="s"
                                    ></button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="colors.length">
                        <div class="mb-6">
                            <h3 class="text-xs uppercase tracking-widest text-muted mb-3">Color</h3>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="c in colors" :key="c">
                                    <button
                                        type="button"
                                        @click="selectedColor = c"
                                        :disabled="variantStockFor(sizes.length ? selectedSize : null, c) < 1"
                                        :class="selectedColor === c ? 'bg-black text-white border-black' : 'border-black/15 text-ink hover:border-black'"
                                        class="rounded-full border px-4 py-2 text-xs transition-colors disabled:opacity-30 disabled:cursor-not-allowed disabled:line-through"
                                        x-text="c"
                                    ></button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div class="mb-8">
                        <p class="text-xs text-muted mb-2" x-text="selectedVariant ? selectedVariant.stock + ' in stock' : 'Out of stock'"></p>
                        <div class="h-1.5 rounded-full bg-black/10 overflow-hidden max-w-xs">
                            <div class="h-full bg-accent rounded-full" :style="`width: ${selectedVariant ? Math.min(100, Math.round(selectedVariant.stock / 50 * 100)) : 0}%`"></div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('cart.add', $product) }}" class="flex items-center gap-4">
                        @csrf
                        <input type="hidden" name="variant_id" :value="selectedVariant?.id">
                        <input type="number" name="qty" value="1" min="1" class="w-20 rounded-full border border-black/15 text-center py-3 text-ink">
                        <button type="submit" class="btn-primary" :disabled="!selectedVariant" :class="!selectedVariant && 'opacity-40 cursor-not-allowed'">Add To Cart</button>
                    </form>
                @endif

                <p class="text-muted text-xs mt-8">SKU: {{ $product->sku }} @if($product->category) &middot; Category: {{ $product->category->name }} @endif</p>
            </div>
        </div>
    </div>
    </div>

    @if($related->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <h2 class="section-heading mb-8">You May Also Like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-5 gap-y-10">
                @foreach($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </div>
    @endif

</x-layouts.storefront>
