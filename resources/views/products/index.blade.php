<x-layouts.storefront :title="$title">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12 gap-2">
            <div>
                <h1 class="page-title">{{ $title }}</h1>
                <p class="text-muted text-sm mt-2">Showing {{ $products->count() }} of {{ $products->total() }} results</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            <div class="lg:col-span-3 lg:order-1 order-2">
                @if($products->isEmpty())
                    <p class="text-muted">No products found in this category yet.</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-5 gap-y-10">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-14 flex justify-center [&_.rounded-md]:rounded-full [&_span]:rounded-full [&_a]:rounded-full">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

            <aside class="lg:col-span-1 order-1 lg:order-2">
                <h3 class="font-semibold text-ink mb-4">Categories</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('shop.index') }}" class="hover:text-accent {{ !isset($category) ? 'text-accent font-medium' : 'text-muted' }}">All Products</a></li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('shop.category', $cat->slug) }}" class="hover:text-accent {{ isset($category) && $category->id === $cat->id ? 'text-accent font-medium' : 'text-muted' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>
        </div>
    </div>

</x-layouts.storefront>
