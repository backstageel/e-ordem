@props(['title', 'value', 'icon', 'color' => 'primary', 'growth' => null, 'growthLabel' => null, 'valueFormat' => 'number'])

<div class="card stat-card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="stat-icon bg-{{ $color }}-light rounded-circle p-3 me-3">
                <i class="{{ str_replace('fas fa-', 'ti ti-', $icon) }} text-{{ $color }} fs-20"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-0 text-muted">{{ $title }}</h6>
                <h3 class="mb-0">
                    @if($valueFormat === 'currency')
                        {{ number_format($value, 0, ',', '.') }} MT
                    @elseif($valueFormat === 'percentage')
                        {{ $value }}%
                    @else
                        {{ number_format($value) }}
                    @endif
                </h3>
            </div>
        </div>
        @if($growth !== null)
        <div class="mt-3 stat-growth">
            <span class="badge {{ $growth >= 0 ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                <i class="ti ti-{{ $growth >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>{{ abs($growth) }}%
            </span>
            <span class="text-muted ms-2">{{ $growthLabel ?? 'Desde o mês passado' }}</span>
        </div>
        @endif
    </div>
</div>

