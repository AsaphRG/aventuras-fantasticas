@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-cinzel font-bold text-xs text-amber-500/80 uppercase tracking-widest mb-1']) }}>
    {{ $value ?? $slot }}
</label>
