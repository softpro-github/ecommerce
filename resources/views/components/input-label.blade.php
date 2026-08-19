@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs uppercase tracking-widest text-muted mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
