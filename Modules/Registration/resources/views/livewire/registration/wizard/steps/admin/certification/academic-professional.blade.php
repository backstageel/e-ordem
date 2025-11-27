<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Dados Académicos e Profissionais</h2>
        <p class="text-sm text-muted mb-0 mt-2">Introduza as suas qualificações académicas e informações profissionais</p>
    </div>
    <div class="card-body card-spacing">
        <div class="row g-3">
            {{-- Primeira linha: Licenciatura em, Instituição, Ano início, Ano término --}}
            <div class="col-md-4">
                <x-bootstrap::form.select
                    name="form.degree_type"
                    label="Licenciatura em"
                    wire:model.defer="form.degree_type"
                    required
                >
                    <option value="">Selecione</option>
                    <option value="Medicina Geral">Medicina Geral</option>
                    <option value="Medicina Dentária">Medicina Dentária</option>
                </x-bootstrap::form.select>
                @error('form.degree_type')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="text"
                    name="form.university"
                    label="Instituição onde concluiu o Ensino Superior"
                    wire:model.defer="form.university"
                    required
                />
                @error('form.university')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-2">
                <x-bootstrap::form.input
                    type="number"
                    name="form.university_start_year"
                    label="Ano de Início"
                    wire:model.defer="form.university_start_year"
                    required
                    min="{{ date('Y') - 50 }}"
                    max="{{ date('Y') + 1 }}"
                />
                @error('form.university_start_year')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-2">
                <x-bootstrap::form.input
                    type="number"
                    name="form.university_end_year"
                    label="Ano de Término"
                    wire:model.defer="form.university_end_year"
                    required
                    min="{{ date('Y') - 50 }}"
                    max="{{ date('Y') }}"
                />
                @error('form.university_end_year')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Segunda linha: País, Cidade/Distrito, Nota final --}}
            <div class="col-md-4">
                <x-bootstrap::form.select
                    name="form.university_country_id"
                    label="País onde concluiu a Licenciatura"
                    wire:model.defer="form.university_country_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </x-bootstrap::form.select>
                @error('form.university_country_id')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="text"
                    name="form.university_city_district"
                    label="Cidade/Distrito onde concluiu a Licenciatura"
                    wire:model.defer="form.university_city_district"
                    required
                />
                @error('form.university_city_district')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="number"
                    name="form.university_final_grade"
                    label="Nota Final da Licenciatura"
                    wire:model.defer="form.university_final_grade"
                    required
                    step="0.01"
                    min="0"
                    max="20"
                    help="Escala de 0 a 20"
                />
                @error('form.university_final_grade')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Terceira linha: Instituição Ensino Médio, País, Cidade/Distrito --}}
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="text"
                    name="form.high_school_institution"
                    label="Instituição onde Concluiu o Ensino Médio"
                    wire:model.defer="form.high_school_institution"
                    required
                />
                @error('form.high_school_institution')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.select
                    name="form.high_school_country_id"
                    label="País onde Concluiu o Ensino Médio"
                    wire:model.defer="form.high_school_country_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </x-bootstrap::form.select>
                @error('form.high_school_country_id')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="text"
                    name="form.high_school_city_district"
                    label="Cidade/Distrito onde concluiu o Ensino Médio"
                    wire:model.defer="form.high_school_city_district"
                    required
                />
                @error('form.high_school_city_district')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Quarta linha: Ano conclusão, Nota conclusão --}}
            <div class="col-md-6">
                <x-bootstrap::form.input
                    type="number"
                    name="form.high_school_completion_year"
                    label="Ano de Conclusão do Ensino Médio"
                    wire:model.defer="form.high_school_completion_year"
                    required
                    min="{{ date('Y') - 50 }}"
                    max="{{ date('Y') }}"
                />
                @error('form.high_school_completion_year')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6">
                <x-bootstrap::form.input
                    type="number"
                    name="form.high_school_final_grade"
                    label="Nota de Conclusão do Ensino Médio"
                    wire:model.defer="form.high_school_final_grade"
                    required
                    step="0.01"
                    min="0"
                    max="20"
                    help="Escala de 0 a 20"
                />
                @error('form.high_school_final_grade')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-transparent border-top d-flex justify-content-between">
        <button type="button" class="btn btn-outline-secondary" wire:click="previousStep" aria-label="Passo Anterior">
            <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Voltar
        </button>
        <button type="button" class="btn btn-primary" wire:click="saveAndNext" wire:loading.attr="disabled" aria-label="Continuar para o Próximo Passo">
            Continuar<i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
        </button>
    </div>
</div>
