<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Dados Académicos e Profissionais</h2>
        <p class="text-sm text-muted mb-0 mt-2">Introduza as suas qualificações académicas e informações profissionais</p>
    </div>
    <div class="card-body card-spacing">
        <div class="row g-3">
            {{-- Primeira linha: Licenciatura em, Instituição, Ano início, Ano término --}}
            <div class="col-md-4">
                <label for="form.degree_type" class="form-label">Licenciatura em <span class="text-danger">*</span></label>
                <select
                    id="form.degree_type"
                    name="form.degree_type"
                    class="form-select @error('form.degree_type') is-invalid @enderror"
                    wire:model.defer="form.degree_type"
                    required
                >
                    <option value="">Selecione</option>
                    <option value="Medicina Geral">Medicina Geral</option>
                    <option value="Medicina Dentária">Medicina Dentária</option>
                </select>
                @error('form.degree_type')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="form.university" class="form-label">Instituição onde concluiu o Ensino Superior <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="form.university"
                    name="form.university"
                    class="form-control @error('form.university') is-invalid @enderror"
                    wire:model.defer="form.university"
                    required
                />
                @error('form.university')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-2">
                <label for="form.university_start_year" class="form-label">Ano de Início <span class="text-danger">*</span></label>
                <input
                    type="number"
                    id="form.university_start_year"
                    name="form.university_start_year"
                    class="form-control @error('form.university_start_year') is-invalid @enderror"
                    wire:model.defer="form.university_start_year"
                    required
                    min="{{ date('Y') - 50 }}"
                    max="{{ date('Y') + 1 }}"
                />
                @error('form.university_start_year')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-2">
                <label for="form.university_end_year" class="form-label">Ano de Término <span class="text-danger">*</span></label>
                <input
                    type="number"
                    id="form.university_end_year"
                    name="form.university_end_year"
                    class="form-control @error('form.university_end_year') is-invalid @enderror"
                    wire:model.defer="form.university_end_year"
                    required
                    min="{{ date('Y') - 50 }}"
                    max="{{ date('Y') }}"
                />
                @error('form.university_end_year')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Segunda linha: País, Cidade/Distrito, Nota final --}}
            <div class="col-md-4">
                <label for="form.university_country_id" class="form-label">País onde concluiu a Licenciatura <span class="text-danger">*</span></label>
                <select
                    id="form.university_country_id"
                    name="form.university_country_id"
                    class="form-select @error('form.university_country_id') is-invalid @enderror"
                    wire:model.defer="form.university_country_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                @error('form.university_country_id')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="form.university_city_district" class="form-label">Cidade/Distrito onde concluiu a Licenciatura <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="form.university_city_district"
                    name="form.university_city_district"
                    class="form-control @error('form.university_city_district') is-invalid @enderror"
                    wire:model.defer="form.university_city_district"
                    required
                />
                @error('form.university_city_district')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="form.university_final_grade" class="form-label">Nota Final da Licenciatura <span class="text-danger">*</span></label>
                <input
                    type="number"
                    id="form.university_final_grade"
                    name="form.university_final_grade"
                    class="form-control @error('form.university_final_grade') is-invalid @enderror"
                    wire:model.defer="form.university_final_grade"
                    required
                    step="0.01"
                    min="0"
                    max="20"
                />
                <small class="form-text text-muted">Escala de 0 a 20</small>
                @error('form.university_final_grade')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Terceira linha: Instituição Ensino Médio, País, Cidade/Distrito --}}
            <div class="col-md-4">
                <label for="form.high_school_institution" class="form-label">Instituição onde Concluiu o Ensino Médio <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="form.high_school_institution"
                    name="form.high_school_institution"
                    class="form-control @error('form.high_school_institution') is-invalid @enderror"
                    wire:model.defer="form.high_school_institution"
                    required
                />
                @error('form.high_school_institution')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="form.high_school_country_id" class="form-label">País onde Concluiu o Ensino Médio <span class="text-danger">*</span></label>
                <select
                    id="form.high_school_country_id"
                    name="form.high_school_country_id"
                    class="form-select @error('form.high_school_country_id') is-invalid @enderror"
                    wire:model.defer="form.high_school_country_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                @error('form.high_school_country_id')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="form.high_school_city_district" class="form-label">Cidade/Distrito onde concluiu o Ensino Médio <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="form.high_school_city_district"
                    name="form.high_school_city_district"
                    class="form-control @error('form.high_school_city_district') is-invalid @enderror"
                    wire:model.defer="form.high_school_city_district"
                    required
                />
                @error('form.high_school_city_district')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Quarta linha: Ano conclusão, Nota conclusão --}}
            <div class="col-md-6">
                <label for="form.high_school_completion_year" class="form-label">Ano de Conclusão do Ensino Médio <span class="text-danger">*</span></label>
                <input
                    type="number"
                    id="form.high_school_completion_year"
                    name="form.high_school_completion_year"
                    class="form-control @error('form.high_school_completion_year') is-invalid @enderror"
                    wire:model.defer="form.high_school_completion_year"
                    required
                    min="{{ date('Y') - 50 }}"
                    max="{{ date('Y') }}"
                />
                @error('form.high_school_completion_year')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="form.high_school_final_grade" class="form-label">Nota de Conclusão do Ensino Médio <span class="text-danger">*</span></label>
                <input
                    type="number"
                    id="form.high_school_final_grade"
                    name="form.high_school_final_grade"
                    class="form-control @error('form.high_school_final_grade') is-invalid @enderror"
                    wire:model.defer="form.high_school_final_grade"
                    required
                    step="0.01"
                    min="0"
                    max="20"
                />
                <small class="form-text text-muted">Escala de 0 a 20</small>
                @error('form.high_school_final_grade')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
</div>
