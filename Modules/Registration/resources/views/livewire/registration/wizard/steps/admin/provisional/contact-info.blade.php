<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Informações de Contacto</h2>
        <p class="text-sm text-muted mb-0 mt-2">Introduza os seus dados de contacto para continuar</p>
    </div>
    <div class="card-body card-spacing">
        @if($resuming)
            <div class="alert alert-info mb-spacing-4">
                <i class="fas fa-info-circle me-2" aria-hidden="true"></i>
                Encontramos uma inscrição anterior. Pode continuar de onde parou.
            </div>
        @endif

        <x-bootstrap::form.form wire:submit="continue">
            <x-bootstrap::form.input
                name="email"
                label="Endereço de Email"
                type="email"
                wire:model="email"
                required
                help="Usaremos este email para enviar atualizações sobre a sua inscrição"
            />
            
            @error('email')
                <div class="text-danger small mt-1">
                    <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                    {{ $message }}
                </div>
            @enderror

            <x-bootstrap::form.input
                name="phone"
                label="Número de Telefone"
                type="tel"
                wire:model="phone"
                required
                help="Formato: +258821234567 ou +2588212345678"
            />
            
            @error('phone')
                <div class="text-danger small mt-1">
                    <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                    {{ $message }}
                </div>
            @enderror

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary" wire:click="previousStep">
                    <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Voltar
                </button>
                <button type="submit" class="btn btn-primary">
                    Continuar<i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                </button>
            </div>
        </x-bootstrap::form.form>
    </div>
</div>

