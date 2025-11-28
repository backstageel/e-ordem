<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Informações de Contacto</h2>
        <p class="text-sm text-muted mb-0 mt-2">Introduza os seus dados de contacto para continuar</p>
    </div>
    <div class="card-body card-spacing">
        @if($resuming)
            <div class="alert alert-info mb-spacing-4">
                <i class="ti ti-info-circle me-2" aria-hidden="true"></i>
                Encontramos uma inscrição anterior. Pode continuar de onde parou.
            </div>
        @endif

        <form wire:submit="continue">
            <div class="mb-3">
                <label for="email" class="form-label">Endereço de Email <span class="text-danger">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    wire:model="email"
                    required
                    placeholder="exemplo@email.com"
                />
                <small class="form-text text-muted">Usaremos este email para enviar atualizações sobre a sua inscrição</small>
                @error('email')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Número de Telefone <span class="text-danger">*</span></label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    class="form-control @error('phone') is-invalid @enderror"
                    wire:model="phone"
                    required
                    placeholder="+258821234567"
                />
                <small class="form-text text-muted">Formato: +258821234567 ou +2588212345678</small>
                @error('phone')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </form>
    </div>
</div>
