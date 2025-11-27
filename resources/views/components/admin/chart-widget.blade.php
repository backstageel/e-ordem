@props([
    'title',
    'chartId',
    'type' => 'line',
    'chartData' => [],
    'options' => [],
    'period' => null,
    'link' => null,
    'linkText' => 'Ver Mais',
    'height' => null,
])

@php
    $chartHeight = $height ?? match($type) {
        'doughnut', 'pie' => '250px',
        default => '300px',
    };

    $defaultOptions = [
        'responsive' => true,
        'maintainAspectRatio' => false,
        'plugins' => [
            'legend' => [
                'position' => $type === 'doughnut' || $type === 'pie' ? 'bottom' : 'top',
            ],
        ],
    ];

    if ($type === 'line' || $type === 'bar') {
        $defaultOptions['scales'] = [
            'y' => [
                'beginAtZero' => true,
            ],
        ];
    }

    $finalOptions = array_merge($defaultOptions, $options ?? []);
@endphp

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-bold mb-0">{{ $title }}</h5>
        <div class="d-flex align-items-center gap-2">
            @if($period)
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    {{ $period }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" data-period="week">Esta Semana</a></li>
                    <li><a class="dropdown-item" href="#" data-period="month">Este Mês</a></li>
                    <li><a class="dropdown-item" href="#" data-period="quarter">Este Trimestre</a></li>
                    <li><a class="dropdown-item" href="#" data-period="year">Este Ano</a></li>
                </ul>
            </div>
            @endif
            @if($link)
            <a href="{{ $link }}" class="btn btn-sm btn-primary">{{ $linkText }}</a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="chart-container" style="position: relative; height: {{ $chartHeight }};">
            <canvas id="{{ $chartId }}"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $chartId }}');
    if (ctx && typeof Chart !== 'undefined') {
        new Chart(ctx.getContext('2d'), {
            type: '{{ $type }}',
            data: @json($chartData),
            options: @json($finalOptions)
        });
    }
});
</script>
@endpush

