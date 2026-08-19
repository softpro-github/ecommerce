@props(['product', 'dark' => false])

<div class="group">
    <a href="{{ route('shop.show', $product->slug) }}" class="block">
        <div class="aspect-square bg-{{ $dark ? 'white/5' : 'panel' }} overflow-hidden flex items-center justify-center relative">
            @if($product->images->first())
                <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <span class="text-xs uppercase tracking-widest {{ $dark ? 'text-white/20' : 'text-black/20' }}">No Image</span>
            @endif

            @if($product->status === 'sold_out' || $product->stock_qty === 0)
                <span class="absolute top-3 left-3 bg-black text-white text-[10px] uppercase tracking-widest px-3 py-1 rounded-full">Sold Out</span>
            @elseif($product->compare_at_price)
                <span class="absolute top-3 left-3 bg-accent text-white text-[10px] uppercase tracking-widest px-3 py-1 rounded-full">Sale</span>
            @endif
        </div>

        <div class="mt-4 text-center space-y-1">
            <h3 class="text-sm {{ $dark ? 'text-white/80' : 'text-muted' }} group-hover:text-accent transition-colors">{{ $product->name }}</h3>
            <div class="flex items-center justify-center gap-2 text-sm">
                <span class="{{ $dark ? 'text-white' : 'text-ink' }} font-medium">&#8358;{{ number_format($product->price) }}</span>
                @if($product->compare_at_price)
                    <span class="{{ $dark ? 'text-white/40' : 'text-muted' }} line-through text-xs">&#8358;{{ number_format($product->compare_at_price) }}</span>
                @endif
            </div>
        </div>
    </a>

    @if($product->status !== 'sold_out' && $product->stock_qty > 0)
        <div class="text-center mt-3 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
            @if($product->variants->isNotEmpty())
                <a href="{{ route('shop.show', $product->slug) }}" class="inline-flex items-center gap-1.5 text-xs px-4 py-2 rounded-full transition-colors {{ $dark ? 'bg-white text-black hover:bg-accent hover:text-white' : 'bg-black text-white hover:bg-accent' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.994-4.694 2.598-7.16.121-.494-.263-.968-.772-.968H5.25M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                    Select Options
                </a>
            @else
                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 text-xs px-4 py-2 rounded-full transition-colors {{ $dark ? 'bg-white text-black hover:bg-accent hover:text-white' : 'bg-black text-white hover:bg-accent' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.994-4.694 2.598-7.16.121-.494-.263-.968-.772-.968H5.25M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                        Add to cart
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>
