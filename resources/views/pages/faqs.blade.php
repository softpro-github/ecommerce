<x-layouts.storefront title="FAQs">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="page-title text-center mb-12">FAQs</h1>

        <div class="space-y-8">
            @forelse($faqs as $faq)
                <div class="rounded-2xl bg-panel p-6">
                    <h3 class="text-sm font-semibold text-accent mb-2">{{ $faq->question }}</h3>
                    <p class="text-muted">{{ $faq->answer }}</p>
                </div>
            @empty
                <p class="text-center text-muted">No FAQs yet.</p>
            @endforelse
        </div>
    </div>

</x-layouts.storefront>
