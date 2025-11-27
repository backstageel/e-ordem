@props([
    'title' => 'Legenda de Status',
    'statusEnum' => \App\Enums\RegistrationStatus::class,
    'collapsible' => true,
])

<div class="card shadow-sm">
    <div class="card-header {{ $collapsible ? 'cursor-pointer' : '' }}"
         @if($collapsible) data-bs-toggle="collapse" data-bs-target="#statusLegendCollapse" aria-expanded="false" @endif>
        <h6 class="card-title mb-0 fw-bold d-flex align-items-center justify-content-between">
            <span>
                <i class="ti ti-info-circle me-2"></i>{{ $title }}
            </span>
            @if($collapsible)
                <i class="ti ti-chevron-down text-muted"></i>
            @endif
        </h6>
    </div>
    <div class="{{ $collapsible ? 'collapse' : '' }}" id="statusLegendCollapse">
        <div class="card-body">
            <div class="row g-3">
                @foreach($statusEnum::cases() as $status)
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-start">
                            <x-status-badge :status="$status" :size="'sm'" />
                            <small class="text-muted ms-2 flex-grow-1">{{ $status->description() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

