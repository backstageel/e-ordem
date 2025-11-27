<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Selecionar Subtipo de Inscrição Provisória</h2>
        <p class="text-sm text-muted mb-0 mt-2">Escolha o subtipo que corresponde à sua situação</p>
    </div>
    <div class="card-body card-spacing">
        @error('subtype')
            <div class="alert alert-danger mb-spacing-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror

        @if($subtype > 0)
            <div class="alert alert-success d-flex justify-content-between align-items-center mb-spacing-4">
                <div>
                    <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                    <span>Subtipo selecionado: <strong>{{ $this->subtypes[$subtype]['label'] ?? '' }}</strong></span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="$set('subtype', 0)" aria-label="Alterar subtipo">
                        <i class="fas fa-edit me-1" aria-hidden="true"></i> Alterar
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" wire:click.prevent="continue" wire:loading.attr="disabled" aria-label="Continuar">
                        <span wire:loading.remove wire:target="continue">
                            <i class="fas fa-arrow-right me-1" aria-hidden="true"></i> Continuar
                        </span>
                        <span wire:loading wire:target="continue">
                            <i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i> A processar...
                        </span>
                    </button>
                </div>
            </div>
        @endif

        <div class="row g-4">
            @foreach($this->subtypes as $subtypeValue => $subtypeData)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border {{ $subtype === $subtypeValue ? 'border-primary shadow-sm' : '' }}" style="cursor: pointer;" wire:key="subtype-card-{{ $subtypeValue }}">
                        <div class="card-body" wire:click.prevent="selectSubtype({{ $subtypeValue }})">
                            <h5 class="card-title-lg mb-2">{{ $subtypeData['label'] }}</h5>
                            <div class="text-sm text-muted mb-3">
                                <div class="mb-1">
                                    <i class="fas fa-clock me-1" aria-hidden="true"></i>
                                    <strong>Duração:</strong> {{ $subtypeData['duration_days'] ? number_format($subtypeData['duration_days'] / 30, 0) . ' meses' : 'N/A' }}
                                </div>
                                @if($subtypeData['is_renewable'])
                                    <div class="mb-1">
                                        <i class="fas fa-sync me-1" aria-hidden="true"></i>
                                        <strong>Renovável:</strong> Sim (máx. {{ $subtypeData['max_renewals'] }})
                                    </div>
                                @endif
                            </div>
                            @if($subtype === $subtypeValue)
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary w-100" disabled wire:click.stop>
                                        <i class="fas fa-check me-2" aria-hidden="true"></i>Selecionado
                                    </button>
                                </div>
                            @else
                                <div class="text-center">
                                    <button type="button" class="btn btn-outline-primary w-100" wire:click.stop="selectSubtype({{ $subtypeValue }})">
                                        <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Selecionar
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

