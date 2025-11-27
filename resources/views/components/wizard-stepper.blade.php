@props([
    'steps' => [],
    'currentStep' => 1,
    'totalSteps' => 0,
])

@php
    $currentStepIndex = $currentStep - 1;
    $progressPercentage = $totalSteps > 0 ? (($currentStep - 1) / ($totalSteps - 1)) * 100 : 0;
@endphp

<div class="wizard-stepper mb-4">
    <!-- Progress Indicator -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-sm text-muted">
            <span class="fw-semibold">Passo {{ $currentStep }}</span>
            <span class="text-muted"> de </span>
            <span class="fw-semibold">{{ $totalSteps }}</span>
        </div>
        <div class="text-sm text-muted">
            {{ round($progressPercentage) }}% Completo
        </div>
    </div>

    <!-- Stepper Visual -->
    <div class="stepper-container position-relative">
        <!-- Progress Line -->
        <div class="stepper-progress-line" style="width: {{ $progressPercentage }}%;"></div>

        <!-- Steps -->
        <div class="d-flex justify-content-between">
            @foreach($steps as $index => $step)
                @php
                    $stepNumber = $index + 1;
                    $isCompleted = $stepNumber < $currentStep;
                    $isCurrent = $stepNumber === $currentStep;
                    $isPending = $stepNumber > $currentStep;
                @endphp
                <div class="stepper-step {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }} {{ $isPending ? 'pending' : '' }}" style="flex: 1;">
                    <div class="stepper-circle" aria-label="Passo {{ $stepNumber }}: {{ $step['title'] ?? '' }}">
                        @if($isCompleted)
                            <i class="ti ti-check" aria-hidden="true"></i>
                        @else
                            <span>{{ $stepNumber }}</span>
                        @endif
                    </div>
                    @if(isset($step['title']))
                        <div class="stepper-label mt-2">
                            <div class="stepper-title {{ $isCurrent ? 'fw-semibold' : 'text-muted' }}">{{ $step['title'] }}</div>
                            @if(isset($step['description']) && $isCurrent)
                                <div class="stepper-description text-sm text-muted">{{ $step['description'] }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

