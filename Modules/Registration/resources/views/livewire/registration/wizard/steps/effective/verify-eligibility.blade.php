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

        <x-bootstrap::form.form wire:submit="verify">
            <x-bootstrap::form.input
                name="registration_number"
                label="Número de Inscrição"
                wire:model="registration_number"
                required
                help="O seu número de inscrição do exame"
            />

            <x-bootstrap::form.input
                name="exam_result_id"
                label="ID do Resultado do Exame"
                wire:model="exam_result_id"
                required
                help="O ID do seu resultado do exame"
            />

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-2" aria-hidden="true"></i>Verificar
                </button>
            </div>
        </x-bootstrap::form.form>
    </div>
</div>

