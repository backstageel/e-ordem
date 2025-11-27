<x-layouts.app>
    <x-slot name="header">
        <h2 class="heading-2 mb-0">Pré-Inscrição para Certificação</h2>
        <p class="text-sm text-muted mb-0 mt-2">Criar nova pré-inscrição para certificação (modo interno)</p>
    </x-slot>

    @livewire(\Modules\Registration\Livewire\Wizard\AdminCertificationWizard::class, [
        'registrationId' => $registrationId ?? null,
    ])
</x-layouts.app>

