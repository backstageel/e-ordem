<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Dados Pessoais</h2>
        <p class="text-sm text-muted mb-0 mt-2">Introduza as suas informações pessoais</p>
    </div>
    <div class="card-body card-spacing">
        <div class="row g-3">
            {{-- Primeira linha: Primeiro Nome, Nomes do Meio, Apelido (mesmo tamanho) --}}
            <div class="col-md-4">
                <label for="form.first_name" class="form-label">Primeiro Nome <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="form.first_name"
                    name="form.first_name"
                    class="form-control @error('form.first_name') is-invalid @enderror"
                    wire:model.defer="form.first_name"
                    required
                />
                @error('form.first_name')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="form.middle_name" class="form-label">Nomes do Meio</label>
                <input
                    type="text"
                    id="form.middle_name"
                    name="form.middle_name"
                    class="form-control"
                    wire:model.defer="form.middle_name"
                />
            </div>
            <div class="col-md-4">
                <label for="form.last_name" class="form-label">Apelido <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="form.last_name"
                    name="form.last_name"
                    class="form-control @error('form.last_name') is-invalid @enderror"
                    wire:model.defer="form.last_name"
                    required
                />
                @error('form.last_name')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Segunda linha: Data de Nascimento, Género, Estado Civil --}}
            <div class="col-md-4">
                <label for="form.birth_date" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                <input
                    type="date"
                    id="form.birth_date"
                    name="form.birth_date"
                    class="form-control @error('form.birth_date') is-invalid @enderror"
                    wire:model.defer="form.birth_date"
                    required
                />
                @error('form.birth_date')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="form.gender_id" class="form-label">Género <span class="text-danger">*</span></label>
                <select
                    id="form.gender_id"
                    name="form.gender_id"
                    class="form-select @error('form.gender_id') is-invalid @enderror"
                    wire:model.defer="form.gender_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->genders as $g)
                        <option value="{{ $g['id'] }}">{{ $g['name'] }}</option>
                    @endforeach
                </select>
                @error('form.gender_id')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="form.marital_status_id" class="form-label">Estado Civil</label>
                <select
                    id="form.marital_status_id"
                    name="form.marital_status_id"
                    class="form-select"
                    wire:model.defer="form.marital_status_id"
                >
                    <option value="">Selecione</option>
                    @foreach($this->civilStates as $s)
                        <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Terceira linha: Nacionalidade, País de Nascimento, Província de Nascimento, Distrito de Nascimento --}}
            <div class="col-md-3">
                <label for="form.nationality_id" class="form-label">Nacionalidade <span class="text-danger">*</span></label>
                <select
                    id="form.nationality_id"
                    name="form.nationality_id"
                    class="form-select @error('form.nationality_id') is-invalid @enderror"
                    wire:model.defer="form.nationality_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $c)
                        <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                    @endforeach
                </select>
                @error('form.nationality_id')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="form.birth_country_id" class="form-label">País de Nascimento <span class="text-danger">*</span></label>
                <select
                    id="form.birth_country_id"
                    name="form.birth_country_id"
                    class="form-select @error('form.birth_country_id') is-invalid @enderror"
                    wire:model.live="form.birth_country_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $c)
                        <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                    @endforeach
                </select>
                @error('form.birth_country_id')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="form.birth_province_id" class="form-label">Província de Nascimento</label>
                <select
                    id="form.birth_province_id"
                    name="form.birth_province_id"
                    class="form-select"
                    wire:model.live="form.birth_province_id"
                    @if(!$this->birthCountryHasProvinces) disabled @endif
                >
                    <option value="">Selecione</option>
                    @if($this->birthCountryHasProvinces)
                        @foreach($this->birthProvinces as $p)
                            <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                        @endforeach
                    @else
                        <option value="0">Estrangeiro</option>
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label for="form.birth_district_id" class="form-label">Distrito de Nascimento</label>
                <select
                    id="form.birth_district_id"
                    name="form.birth_district_id"
                    class="form-select"
                    wire:model.defer="form.birth_district_id"
                    @if(!$this->birthProvinceHasDistricts) disabled @endif
                >
                    <option value="">Selecione</option>
                    @if($this->birthProvinceHasDistricts)
                        @foreach($this->birthDistricts as $d)
                            <option value="{{ $d['id'] }}">{{ $d['name'] }}</option>
                        @endforeach
                    @else
                        <option value="0">Estrangeiro</option>
                    @endif
                </select>
            </div>

            {{-- Quarta linha: Nome do Pai, Nome da Mãe --}}
            <div class="col-md-6">
                <label for="form.father_name" class="form-label">Nome do Pai</label>
                <input
                    type="text"
                    id="form.father_name"
                    name="form.father_name"
                    class="form-control"
                    wire:model.defer="form.father_name"
                />
            </div>
            <div class="col-md-6">
                <label for="form.mother_name" class="form-label">Nome da Mãe</label>
                <input
                    type="text"
                    id="form.mother_name"
                    name="form.mother_name"
                    class="form-control"
                    wire:model.defer="form.mother_name"
                />
            </div>
        </div>
    </div>
</div>
