@props([
    'status', // RegistrationStatus enum instance
    'size' => 'default', // 'sm', 'default', 'lg'
    'showIcon' => true,
    'showDescription' => false,
])

@php
    $color = $status->color();
    $label = $status->label();
    $icon = $status->icon();
    $description = $status->description();

    $sizeClass = match($size) {
        'sm' => 'badge-sm',
        'lg' => 'badge-lg',
        default => '',
    };

    $iconSize = match($size) {
        'sm' => 'fs-10',
        'lg' => 'fs-12',
        default => 'fs-10',
    };

    // Convert Font Awesome icons to Tabler Icons
    $iconClass = str_replace('fas fa-', 'ti ti-', $icon);
    $iconClass = str_replace('fa-', 'ti-', $iconClass);

    // Handle dark color differently (no -light variant)
    $bgClass = $color === 'dark' ? 'bg-dark' : "bg-{$color}-light";
    $textClass = $color === 'dark' ? 'text-white' : "text-{$color}";
@endphp

<span class="badge {{ $bgClass }} {{ $textClass }} {{ $sizeClass }}"
      @if($showDescription) title="{{ $description }}" @endif
      aria-label="{{ $label }}: {{ $description }}">
    @if($showIcon)
        <i class="{{ $iconClass }} {{ $iconSize }} me-1" aria-hidden="true"></i>
    @endif
    {{ $label }}
</span>

