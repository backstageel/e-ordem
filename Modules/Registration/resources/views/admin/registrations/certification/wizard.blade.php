<?php $page = 'admin-registrations'; ?>
<x-layouts.app>
    <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3">
        <div class="flex-grow-1">
            <h4 class="fw-bold mb-0">Pré-Inscrição para Certificação</h4>
            <p class="text-muted mb-0">Criar nova pré-inscrição para certificação (modo interno)</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @livewire(\Modules\Registration\Livewire\Wizard\AdminCertificationWizard::class, [
                'registrationId' => $registrationId ?? null,
            ])
        </div>
    </div>
</x-layouts.app>

