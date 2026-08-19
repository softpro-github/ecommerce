@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border border-black/15 px-4 py-3 text-sm focus:border-accent focus:ring-accent']) }}>
