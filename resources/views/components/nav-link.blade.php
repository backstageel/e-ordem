@props(['active', 'href'])

@php
$classes = ($active ?? false)
            ? 'nav-link active'
            : 'nav-link';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

