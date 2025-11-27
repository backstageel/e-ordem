@props(['type', 'title', 'message', 'icon', 'link' => null, 'linkText' => null, 'dismissible' => true])

@php
    $alertClass = match($type) {
        'success' => 'alert-success',
        'warning' => 'alert-warning',
        'danger' => 'alert-danger',
        'info' => 'alert-info',
        default => 'alert-primary',
    };

    // Convert Font Awesome icons to Tabler Icons if needed
    $iconClass = str_replace('fas fa-', 'ti ti-', $icon ?? '');
    $iconClass = str_replace('fa-', 'ti-', $iconClass);
@endphp

<div class="alert {{ $alertClass }} alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        @if($icon)
        <i class="{{ $iconClass }} me-3"></i>
        @endif
        <div class="flex-grow-1">
            <h6 class="alert-heading mb-1">{{ $title }}</h6>
            <p class="mb-0">{{ $message }}</p>
            @if($link)
            <a href="{{ $link }}" class="btn btn-sm btn-outline-{{ $type }} mt-2">
                {{ $linkText ?? 'Ver Detalhes' }}
            </a>
            @endif
        </div>
    </div>
    @if($dismissible)
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>

