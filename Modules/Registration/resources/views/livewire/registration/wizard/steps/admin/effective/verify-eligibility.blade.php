<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Verificar Elegibilidade</h2>
        <p class="text-sm text-muted mb-0 mt-2">Introduza o seu número de inscrição e ID do resultado do exame para verificar elegibilidade</p>
    </div>
    <div class="card-body card-spacing">
        @if($eligible)
            <div class="alert alert-success mb-spacing-4">
                <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                Está elegível para inscrição efetiva.
            </div>
        @endif

        <form wire:submit="verify">
            <div class="mb-3">
                <label for="registration_number" class="form-label">Número de Inscrição <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="registration_number"
                    name="registration_number"
                    class="form-control @error('registration_number') is-invalid @enderror"
                    wire:model="registration_number"
                    required
                    placeholder="Número de inscrição do exame"
                />
                <small class="form-text text-muted">O seu número de inscrição do exame</small>
                @error('registration_number')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="exam_result_id" class="form-label">ID do Resultado do Exame <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="exam_result_id"
                    name="exam_result_id"
                    class="form-control @error('exam_result_id') is-invalid @enderror"
                    wire:model="exam_result_id"
                    required
                    placeholder="ID do resultado do exame"
                />
                <small class="form-text text-muted">O ID do seu resultado do exame</small>
                @error('exam_result_id')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-2" aria-hidden="true"></i>Verificar
                </button>
            </div>
        </form>
    </div>
</div>
