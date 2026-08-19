<x-layouts.storefront>

    @php
        $heroSlides = \App\Models\Slide::where('type', 'hero')->where('enabled', true)->orderBy('sort_order')->get();
        $campaignSlides = \App\Models\Slide::where('type', 'campaign')->where('enabled', true)->orderBy('sort_order')->get();
        $siteTagline = \App\Models\Setting::get('tagline', 'Your Style Our Priority');
        $brandPhilosophyText = \App\Models\Setting::get(
            'brand_philosophy_text',
            'CityStyleWears exists for those who wear their story. Every piece is built with premium materials, bold branding, and an unapologetic streetwear DNA.'
        );
        $newArrivalsHeading = \App\Models\Setting::get('new_arrivals_heading', 'New Arrivals');
        $categoryHeading = \App\Models\Setting::get('category_heading', 'Shop By Category');
    @endphp

    <!-- Hero Slideshow -->
    @if($heroSlides->isNotEmpty())
    <section
        x-data="{ active: 0, count: {{ $heroSlides->count() }}, timer: null, init() { this.timer = setInterval(() => { this.active = (this.active + 1) % this.count }, 4500) } }"
        class="pt-3 pb-4 sm:pt-4 sm:pb-6"
    >
        <div class="relative w-full h-[520px] sm:h-[750px] lg:h-[900px]">
            @foreach($heroSlides as $i => $slide)
                <{{ $slide->cta_link ? 'a' : 'div' }}
                    @if($slide->cta_link) href="{{ $slide->cta_link }}" @endif
                    x-show="active === {{ $i }}"
                    x-transition:enter="transition ease-out duration-1000"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="absolute inset-0 bg-center bg-no-repeat bg-contain drop-shadow-[0_35px_35px_rgba(0,0,0,0.25)]"
                    style="background-image: url('{{ asset('storage/'.$slide->image_path) }}');"
                    @if($i > 0) x-cloak @endif
                ></{{ $slide->cta_link ? 'a' : 'div' }}>
            @endforeach
        </div>

        @php
            $firstCta = $heroSlides->first(fn ($s) => $s->cta_text);
        @endphp
        <div class="text-center mt-4">
            <a href="{{ $firstCta?->cta_link ?: route('shop.index') }}" class="btn-primary">{{ $firstCta?->cta_text ?: 'Shop New Arrivals' }}</a>
        </div>
    </section>
    @endif

    <!-- New Arrivals -->
    @if($newArrivals->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-16">
        <h2 class="section-heading mb-8">{{ $newArrivalsHeading }}</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-10">
            @foreach($newArrivals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('shop.index') }}" class="text-sm text-ink border-b border-ink/30 hover:border-accent hover:text-accent transition-colors pb-0.5">Explore All Collection</a>
        </div>
    </section>
    @endif

    <!-- Campaign Lookbook Slideshow -->
    @if($campaignSlides->isNotEmpty())
    <section
        x-data="{
            active: 0,
            count: {{ $campaignSlides->count() }},
            timer: null,
            paused: false,
            start() { this.timer = setInterval(() => { if (!this.paused) this.next() }, 5000) },
            next() { this.active = (this.active + 1) % this.count },
            prev() { this.active = (this.active - 1 + this.count) % this.count },
        }"
        x-init="start()"
        @mouseenter="paused = true"
        @mouseleave="paused = false"
        class="relative w-full h-[420px] sm:h-[560px] lg:h-[640px] overflow-hidden bg-black"
    >
        @foreach($campaignSlides as $i => $slide)
            <div
                x-show="active === {{ $i }}"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-cover"
                style="background-image: url('{{ asset('storage/'.$slide->image_path) }}'); background-position: center 18%;"
                @if($i > 0) x-cloak @endif
            >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                @if($slide->heading)
                    <div class="absolute inset-0 flex items-end justify-center pb-14 sm:pb-20">
                        <h3 class="font-accent uppercase text-3xl sm:text-5xl lg:text-6xl text-white tracking-wide text-center drop-shadow-lg px-4">
                            {{ $slide->heading }}
                        </h3>
                    </div>
                @endif
            </div>
        @endforeach

        <button @click="prev()" aria-label="Previous slide" class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full border border-white/40 text-white flex items-center justify-center hover:bg-white hover:text-black transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button @click="next()" aria-label="Next slide" class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full border border-white/40 text-white flex items-center justify-center hover:bg-white hover:text-black transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </button>

        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex items-center gap-2">
            @foreach($campaignSlides as $i => $slide)
                <button
                    @click="active = {{ $i }}"
                    :class="active === {{ $i }} ? 'bg-white w-6' : 'bg-white/40 w-1.5'"
                    class="h-1.5 rounded-full transition-all"
                    aria-label="Go to slide {{ $i + 1 }}"
                ></button>
            @endforeach
        </div>
    </section>
    @endif

    <div class="h-16 sm:h-24"></div>

    <!-- Featured / Bestsellers on black -->
    @if($featured->isNotEmpty() || $bestsellers->isNotEmpty())
    <section class="bg-black text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="font-script text-3xl sm:text-4xl text-accent text-center mb-2">{{ $siteTagline }}</p>
            <h2 class="section-heading text-white mb-12">{{ $featured->isNotEmpty() ? 'Featured' : 'Bestsellers' }}</h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-10">
                @foreach(($featured->isNotEmpty() ? $featured : $bestsellers) as $product)
                    <x-product-card :product="$product" :dark="true" />
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('shop.index') }}" class="text-sm text-white border-b border-white/30 hover:border-accent hover:text-accent transition-colors pb-0.5">Explore All Collection</a>
            </div>
        </div>
    </section>
    @endif

    <!-- Categories -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="section-heading mb-8">{{ $categoryHeading }}</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('shop.category', $cat->slug) }}" class="border border-black/10 py-10 text-center text-sm text-ink hover:border-accent hover:text-accent transition-colors rounded-2xl">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </section>

    <!-- Sold Out / Archive -->
    @if($soldOut->isNotEmpty())
    <section class="bg-panel py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="section-heading mb-8 text-muted">Sold Out &mdash; Archive Pieces</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-10 opacity-70">
                @foreach($soldOut as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Brand philosophy -->
    <section class="bg-black text-white py-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <svg class="w-8 h-8 mx-auto mb-6 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M9.5 7C6.5 7 4 9.5 4 12.5S6.5 18 9.5 18c.3 0 .5-.2.5-.5s-.2-.5-.5-.5C7 17 5 15 5 12.5S7 8 9.5 8c.3 0 .5-.2.5-.5S9.8 7 9.5 7zm7 0c-3 0-5.5 2.5-5.5 5.5S13.5 18 16.5 18c.3 0 .5-.2.5-.5s-.2-.5-.5-.5c-2.5 0-4.5-2-4.5-4.5S14 8 16.5 8c.3 0 .5-.2.5-.5s-.2-.5-.5-.5z"/></svg>
            <p class="text-lg sm:text-xl leading-relaxed text-white/80 italic">
                {{ $brandPhilosophyText }}
            </p>
            <p class="font-script text-2xl text-accent mt-6">{{ $siteTagline }}</p>
        </div>
    </section>

</x-layouts.storefront>
