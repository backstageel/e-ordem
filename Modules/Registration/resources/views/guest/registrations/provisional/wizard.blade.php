<?php $page = 'guest-registration'; ?>
<x-layouts.guest>
    <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3">
        <div class="flex-grow-1">
            <h4 class="fw-bold mb-0">Inscrição Provisória</h4>
            <p class="text-muted mb-0">Complete o processo de inscrição passo a passo</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @livewire(\Modules\Registration\Livewire\Wizard\ProvisionalWizard::class)
        </div>
    </div>
</x-layouts.guest>

