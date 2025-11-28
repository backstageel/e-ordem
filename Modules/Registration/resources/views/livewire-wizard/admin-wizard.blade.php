@php
    $stepNames = $currentStepState['allStepNames'] ?? [];
    // Get currentStepName from the view data (passed by CustomWizardRender trait)
    $currentStepName = $currentStepName ?? ($stepNames[0] ?? '');
    $currentStepIndex = array_search($currentStepName, $stepNames);
    $currentStepNumber = $currentStepIndex !== false ? $currentStepIndex + 1 : 1;
    $totalSteps = count($stepNames);

    // Step titles mapping for admin wizards
    $stepTitles = [
        'registration::livewire.wizard.steps.admin.certification.choose-category' => ['title' => 'Categoria', 'icon' => 'ti ti-category'],
        'registration::livewire.wizard.steps.admin.certification.contact-info' => ['title' => 'Contacto', 'icon' => 'ti ti-phone'],
        'registration::livewire.wizard.steps.admin.certification.personal-info' => ['title' => 'Pessoal', 'icon' => 'ti ti-user'],
        'registration::livewire.wizard.steps.admin.certification.identity-address' => ['title' => 'Identidade', 'icon' => 'ti ti-id'],
        'registration::livewire.wizard.steps.admin.certification.academic-professional' => ['title' => 'Académico', 'icon' => 'ti ti-school'],
        'registration::livewire.wizard.steps.admin.certification.upload-documents' => ['title' => 'Documentos', 'icon' => 'ti ti-file-upload'],
        'registration::livewire.wizard.steps.admin.certification.review-submit' => ['title' => 'Revisão', 'icon' => 'ti ti-check'],

        'registration::livewire.wizard.steps.admin.provisional.choose-subtype' => ['title' => 'Subtipo', 'icon' => 'ti ti-category'],
        'registration::livewire.wizard.steps.admin.provisional.contact-info' => ['title' => 'Contacto', 'icon' => 'ti ti-phone'],
        'registration::livewire.wizard.steps.admin.provisional.personal-info' => ['title' => 'Pessoal', 'icon' => 'ti ti-user'],
        'registration::livewire.wizard.steps.admin.provisional.identity-address' => ['title' => 'Identidade', 'icon' => 'ti ti-id'],
        'registration::livewire.wizard.steps.admin.provisional.academic-professional' => ['title' => 'Académico', 'icon' => 'ti ti-school'],
        'registration::livewire.wizard.steps.admin.provisional.upload-documents' => ['title' => 'Documentos', 'icon' => 'ti ti-file-upload'],
        'registration::livewire.wizard.steps.admin.provisional.review-submit' => ['title' => 'Revisão', 'icon' => 'ti ti-check'],

        'registration::livewire.wizard.steps.admin.effective.verify-eligibility' => ['title' => 'Elegibilidade', 'icon' => 'ti ti-shield-check'],
        'registration::livewire.wizard.steps.admin.effective.select-grade' => ['title' => 'Grau', 'icon' => 'ti ti-star'],
        'registration::livewire.wizard.steps.admin.effective.upload-documents' => ['title' => 'Documentos', 'icon' => 'ti ti-file-upload'],
        'registration::livewire.wizard.steps.admin.effective.review-submit' => ['title' => 'Revisão', 'icon' => 'ti ti-check'],
    ];
@endphp

<div>
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-center">
            <h4 class="header-title mb-0">Processo de Inscrição (Modo Admin)</h4>
        </div>

        <div class="card-body">
            <form>
                <div id="registration-wizard">
                    <!-- Wizard Steps Navigation -->
                    <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                        @foreach($stepNames as $index => $stepName)
                            @php
                                $stepNumber = $index + 1;
                                $isActive = $stepName === $currentStepName;
                                $isCompleted = $stepNumber < $currentStepNumber;
                                $stepInfo = $stepTitles[$stepName] ?? ['title' => 'Passo ' . $stepNumber, 'icon' => 'ti ti-circle'];
                            @endphp
                            <li class="nav-item">
                                <a href="javascript:void(0);"
                                   class="nav-link rounded-0 py-2 {{ $isActive ? 'active' : '' }} {{ $isCompleted ? 'bg-success bg-opacity-10 text-success' : '' }}"
                                   wire:click="goToStep('{{ $stepName }}')"
                                   style="cursor: pointer;">
                                    <i class="{{ $stepInfo['icon'] }} fs-18 align-middle me-1 {{ $isCompleted ? 'text-success' : '' }}"></i>
                                    <span class="d-none d-sm-inline">{{ $stepInfo['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Step Content -->
                    <div class="tab-content b-0 mb-0">
                        <div class="tab-pane active">
                            @livewire($currentStepName, $currentStepState, key($currentStepName))
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex wizard justify-content-between flex-wrap gap-2 mt-3">
                        @if($currentStepNumber > 1)
                            <div class="previous">
                                <button type="button"
                                        class="btn btn-primary"
                                        wire:click="goToPreviousStep">
                                    <i class="ti ti-arrow-left me-2"></i>Anterior
                                </button>
                            </div>
                        @else
                            <div></div>
                        @endif

                        <div class="d-flex flex-wrap gap-2">
                            @if($currentStepNumber < $totalSteps)
                                <div class="next">
                                    <button type="button"
                                            class="btn btn-primary"
                                            wire:click="goToNextStep"
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="goToNextStep">
                                            Próximo<i class="ti ti-arrow-right ms-2"></i>
                                        </span>
                                        <span wire:loading wire:target="goToNextStep">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            A processar...
                                        </span>
                                    </button>
                                </div>
                            @else
                                <div class="last">
                                    <button type="button"
                                            class="btn btn-success"
                                            wire:click="goToNextStep"
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="goToNextStep">
                                            <i class="ti ti-check me-2"></i>Finalizar
                                        </span>
                                        <span wire:loading wire:target="goToNextStep">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            A processar...
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

