<div class="card card-spacing" x-data="{progress: 0}">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Documentos</h2>
        <p class="text-sm text-muted mb-0 mt-2">Carregue os documentos necessários para a sua inscrição</p>
    </div>
    <div class="card-body card-spacing">
        @php($seedFirst = (array) $this->state()->forStepClass(\Modules\Registration\Livewire\Wizard\Steps\Certification\ChooseCategoryStep::class))
        @php($internal = (bool) ($seedFirst['internal_enabled'] ?? false))

        @if($internal)
            <div class="alert alert-info d-flex align-items-center mb-spacing-4" role="alert">
                <i class="fas fa-shield-check me-2" aria-hidden="true"></i>
                <span>Documentos submetidos por utilizadores internos são considerados verificados.</span>
            </div>
        @else
            <div class="alert alert-warning d-flex align-items-center mb-spacing-4" role="alert">
                <i class="fas fa-info-circle me-2" aria-hidden="true"></i>
                <span>Os documentos são opcionais nesta fase. Pode submetê-los agora ou mais tarde. Documentos não fornecidos serão registados como pendentes.</span>
            </div>
        @endif

        <div class="mb-spacing-3">
            <div class="progress" style="height: 6px;" x-show="progress > 0">
                <div class="progress-bar" role="progressbar" :style="`width: ${progress}%`" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="list-group mb-spacing-3">
            @foreach ($this->requiredDocuments as $doc)
                <div class="list-group-item d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                    <div>
                        <strong class="text-base">{{ $this->getDocumentLabel($doc) }}</strong>
                        @if (isset($uploads[$doc]))
                            <span class="badge bg-success-light text-success ms-2">
                                <i class="fas fa-check fa-xs me-1" aria-hidden="true"></i>Anexado
                            </span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input
                            type="file"
                            id="file-{{ $doc }}"
                            wire:model="currentFile"
                            accept=".pdf,.jpg,.jpeg,.png"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            x-on:livewire-upload-finish="progress = 0"
                            x-on:livewire-upload-error="progress = 0"
                            wire:change="queueDocument('{{ $doc }}')"
                            class="form-control d-none"
                            style="width: 280px"
                        />
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('file-{{ $doc }}').click()" aria-label="Selecionar {{ str_replace('_',' ', $doc) }}">
                            <i class="fas fa-folder-open me-1" aria-hidden="true"></i>Selecionar
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" wire:click="uploadCurrent" @disabled($currentDocumentKey !== $doc || !$currentFile) aria-label="Carregar {{ str_replace('_',' ', $doc) }}">
                            <i class="fas fa-upload me-1" aria-hidden="true"></i>Carregar
                        </button>
                        @if(isset($uploads[$doc]))
                            <span class="text-success small">
                                <i class="fas fa-check-circle me-1" aria-hidden="true"></i>Carregado
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @if($internal)
            <div class="card mt-spacing-4">
                <div class="card-body card-spacing d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                    <div>
                        <strong class="text-base">Comprovativo de Pagamento (Opcional)</strong>
                        @if (isset($uploads['payment_proof']))
                            <span class="badge bg-success-light text-success ms-2">
                                <i class="fas fa-check fa-xs me-1" aria-hidden="true"></i>Anexado
                            </span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input
                            type="file"
                            id="file-payment_proof"
                            wire:model="currentFile"
                            accept=".pdf,.jpg,.jpeg,.png"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            x-on:livewire-upload-finish="progress = 0"
                            x-on:livewire-upload-error="progress = 0"
                            x-on:change="$wire.queueDocument('payment_proof')"
                            class="form-control d-none"
                            style="width: 280px"
                        />
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('file-payment_proof').click()" aria-label="Selecionar Comprovativo de Pagamento">
                            <i class="fas fa-folder-open me-1" aria-hidden="true"></i>Selecionar
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" wire:click="uploadCurrent" @disabled($currentDocumentKey !== 'payment_proof' || !$currentFile) aria-label="Carregar Comprovativo de Pagamento">
                            <i class="fas fa-upload me-1" aria-hidden="true"></i>Carregar
                        </button>
                        @if(isset($uploads['payment_proof']))
                            <span class="text-success small">
                                <i class="fas fa-check-circle me-1" aria-hidden="true"></i>Carregado
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        @error('uploads')
            <div class="alert alert-danger mt-spacing-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </div>
    <div class="card-footer bg-transparent border-top d-flex justify-content-between">
        <button type="button" class="btn btn-outline-secondary" wire:click="previousStep" aria-label="Passo Anterior">
            <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Voltar
        </button>
        <button type="button" class="btn btn-primary" wire:click="saveAndNext" wire:loading.attr="disabled" aria-label="Continuar para o Próximo Passo">
            Continuar<i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
        </button>
    </div>
</div>
