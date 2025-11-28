<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Dados Pessoais</h2>
        <p class="text-sm text-muted mb-0 mt-2">Introduza as suas informações pessoais</p>
    </div>
    <div class="card-body card-spacing">
        <div class="row g-3">
            {{-- Primeira linha: Primeiro Nome, Nomes do Meio, Apelido (mesmo tamanho) --}}
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="text"
                    name="form.first_name"
                    label="Primeiro Nome"
                    wire:model.defer="form.first_name"
                    required
                />
                @error('form.first_name')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="text"
                    name="form.middle_name"
                    label="Nomes do Meio"
                    wire:model.defer="form.middle_name"
                />
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="text"
                    name="form.last_name"
                    label="Apelido"
                    wire:model.defer="form.last_name"
                    required
                />
                @error('form.last_name')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Segunda linha: Data de Nascimento, Género, Estado Civil --}}
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="date"
                    name="form.birth_date"
                    label="Data de Nascimento"
                    wire:model.defer="form.birth_date"
                    required
                />
                @error('form.birth_date')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.select
                    name="form.gender_id"
                    label="Género"
                    wire:model.defer="form.gender_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->genders as $g)
                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                    @endforeach
                </x-bootstrap::form.select>
                @error('form.gender_id')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.select
                    name="form.marital_status_id"
                    label="Estado Civil"
                    wire:model.defer="form.marital_status_id"
                >
                    <option value="">Selecione</option>
                    @foreach($this->civilStates as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </x-bootstrap::form.select>
            </div>

            {{-- Terceira linha: Nacionalidade, País de Nascimento, Província de Nascimento, Distrito de Nascimento --}}
            <div class="col-md-3">
                <x-bootstrap::form.select
                    name="form.nationality_id"
                    label="Nacionalidade"
                    wire:model.defer="form.nationality_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </x-bootstrap::form.select>
                @error('form.nationality_id')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <x-bootstrap::form.select
                    name="form.birth_country_id"
                    label="País de Nascimento"
                    wire:model.live="form.birth_country_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </x-bootstrap::form.select>
                @error('form.birth_country_id')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <x-bootstrap::form.select
                    name="form.birth_province_id"
                    label="Província de Nascimento"
                    wire:model.live="form.birth_province_id"
                    :disabled="!$this->birthCountryHasProvinces"
                >
                    <option value="">Selecione</option>
                    @if($this->birthCountryHasProvinces)
                        @foreach($this->birthProvinces as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    @else
                        <option value="0">Estrangeiro</option>
                    @endif
                </x-bootstrap::form.select>
            </div>
            <div class="col-md-3">
                <x-bootstrap::form.select
                    name="form.birth_district_id"
                    label="Distrito de Nascimento"
                    wire:model.defer="form.birth_district_id"
                    :disabled="!$this->birthProvinceHasDistricts"
                >
                    <option value="">Selecione</option>
                    @if($this->birthProvinceHasDistricts)
                        @foreach($this->birthDistricts as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    @else
                        <option value="0">Estrangeiro</option>
                    @endif
                </x-bootstrap::form.select>
            </div>

            {{-- Quarta linha: Nome do Pai, Nome da Mãe --}}
            <div class="col-md-6">
                <x-bootstrap::form.input
                    type="text"
                    name="form.father_name"
                    label="Nome do Pai"
                    wire:model.defer="form.father_name"
                />
            </div>
            <div class="col-md-6">
                <x-bootstrap::form.input
                    type="text"
                    name="form.mother_name"
                    label="Nome da Mãe"
                    wire:model.defer="form.mother_name"
                />
            </div>
        </div>
    </div>
</div>
