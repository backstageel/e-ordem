<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Revisão e Submissão</h2>
        <p class="text-sm text-muted mb-0 mt-2">Revise todas as informações antes de submeter a sua inscrição</p>
    </div>
    <div class="card-body card-spacing">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="border rounded p-spacing-3 h-100">
                    <h6 class="heading-6 text-muted mb-3">Tipo de Inscrição</h6>
                    <div class="text-base mb-2"><strong>Grau:</strong> {{ $summary['grade'] ?? '-' }}</div>
                    <div class="text-base"><strong>Tipo:</strong> {{ $summary['grade_name'] ?? $summary['type'] ?? '-' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-spacing-3 h-100">
                    <h6 class="heading-6 text-muted mb-3">Resultado do Exame</h6>
                    @if($summary['exam_result'] ?? null)
                        <div class="text-base mb-2"><strong>Número de Inscrição:</strong> {{ $summary['registration_number'] ?? '-' }}</div>
                        <div class="text-base mb-2"><strong>Nota:</strong> {{ $summary['exam_result']->grade ?? '-' }}</div>
                        <div class="text-base"><strong>Status:</strong> {{ $summary['exam_result']->status ?? '-' }}</div>
                    @else
                        <p class="text-sm text-muted mb-0">Resultado do exame não encontrado</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-spacing-3 h-100">
                    <h6 class="heading-6 text-muted mb-3">Documentos</h6>
                    @php($uploads = (array) ($summary['uploads'] ?? []))
                    <div class="text-base mb-2"><strong>Total:</strong> {{ count($uploads) }}</div>
                    @if(count($uploads))
                        <ul class="mb-0 mt-2 list-unstyled">
                            @foreach($uploads as $key => $path)
                                <li class="mb-1">
                                    <i class="fas fa-file-pdf text-danger me-2" aria-hidden="true"></i>
                                    <span class="text-capitalize">{{ str_replace('_',' ', $key) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-muted mb-0">Nenhum documento carregado</p>
                    @endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="border rounded p-spacing-3 bg-light">
                    <h6 class="heading-6 text-muted mb-3">Taxas e Pagamentos</h6>
                    @php($feeDetails = (array) ($summary['fee_details'] ?? []))
                    @php($breakdown = (array) ($feeDetails['breakdown'] ?? []))
                    @if(!empty($breakdown))
                        <div class="mb-3">
                            @foreach($breakdown as $line)
                                @if(!empty($line))
                                    <div class="text-base mb-1">{{ $line }}</div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                        <strong class="text-lg">Total a Pagar:</strong>
                        <strong class="text-lg text-primary">{{ number_format($summary['fee'] ?? 0, 2, ',', '.') }} MT</strong>
                    </div>
                </div>
            </div>
        </div>

        @error('submit')
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
        <button type="button" class="btn btn-success" wire:click="submit" wire:loading.attr="disabled" aria-label="Submeter Inscrição">
            <i class="fas fa-check me-2" aria-hidden="true"></i>Submeter
        </button>
    </div>
</div>
