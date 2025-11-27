<x-layouts.app>
    <x-slot name="header">
        <h2 class="heading-2 mb-0">Inscrição Provisória</h2>
        <p class="text-sm text-muted mb-0 mt-2">Criar nova inscrição provisória (modo interno)</p>
    </x-slot>

    @livewire(\Modules\Registration\Livewire\Wizard\AdminProvisionalWizard::class, [
        'registrationId' => $registrationId ?? null,
    ])
</x-layouts.app>

