<?php $page = 'admin-registrations'; ?>
<x-layouts.app>
    <div class="page-wrapper">
        <div class="content pb-0">
            <div class="d-flex align-items-sm-center justify-content-center flex-sm-row flex-column gap-2 pb-3">
                <div class="text-center">
                    <h4 class="fw-bold mb-0">Inscrição Provisória</h4>
                    <p class="text-muted mb-0">Criar nova inscrição provisória (modo interno)</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @livewire(\Modules\Registration\Livewire\Wizard\AdminProvisionalWizard::class, [
                        'registrationId' => $registrationId ?? null,
                    ])
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

