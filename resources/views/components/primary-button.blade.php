<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-accent text-white uppercase tracking-wide text-xs font-semibold px-7 py-3.5 hover:bg-accent-dark transition-colors']) }}>
    {{ $slot }}
</button>
