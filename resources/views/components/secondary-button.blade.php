<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full border border-black/20 text-ink uppercase tracking-wide text-xs font-semibold px-7 py-3.5 hover:border-black hover:bg-black hover:text-white transition-colors']) }}>
    {{ $slot }}
</button>
