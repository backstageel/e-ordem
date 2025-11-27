<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Selecionar Grau</h2>
        <p class="text-sm text-muted mb-0 mt-2">Escolha o seu grau profissional</p>
    </div>
    <div class="card-body card-spacing">
        @if($grade)
            <div class="alert alert-success d-flex justify-content-between align-items-center mb-spacing-4">
                <div>
                    <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                    <span>Grau selecionado: <strong>{{ $this->grades[$grade]['name'] ?? '' }}</strong></span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="$set('grade', '')" aria-label="Alterar grau">
                    <i class="fas fa-edit me-1" aria-hidden="true"></i> Alterar
                </button>
            </div>
        @endif

        <div class="row g-4">
            @foreach($this->grades as $gradeValue => $gradeData)
                <div class="col-md-4">
                    <div class="card h-100 border {{ $grade === $gradeValue ? 'border-primary shadow-sm' : '' }}" wire:click="selectGrade('{{ $gradeValue }}')" style="cursor: pointer;">
                        <div class="card-body">
                            <h5 class="card-title-lg mb-2">{{ $gradeData['name'] }}</h5>
                            <p class="text-sm text-muted mb-3">{{ $gradeData['description'] }}</p>
                            @if($grade === $gradeValue)
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary w-100" disabled>
                                        <i class="fas fa-check me-2" aria-hidden="true"></i>Selecionado
                                    </button>
                                </div>
                            @else
                                <div class="text-center">
                                    <button type="button" class="btn btn-outline-primary w-100">
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

