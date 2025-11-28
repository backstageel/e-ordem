<?php $page = 'guest-registration'; ?>
<x-layouts.guest>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <!-- Page Header -->
            <div class="d-flex align-items-sm-center justify-content-center flex-sm-row flex-column gap-2 mb-4">
                <div class="text-center">
                    <h4 class="fw-bold mb-0">Pré-Inscrição para Certificação</h4>
                    <p class="text-muted mb-0">Complete o processo de inscrição passo a passo</p>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- Wizard Content -->
            @livewire(\Modules\Registration\Livewire\Wizard\CertificationWizard::class)
        </div>
    </div>
</x-layouts.guest>

