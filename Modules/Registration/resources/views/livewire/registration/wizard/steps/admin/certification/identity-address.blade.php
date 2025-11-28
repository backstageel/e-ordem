<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Identificação e Morada</h2>
        <p class="text-sm text-muted mb-0 mt-2">Informações do documento de identidade e morada de residência</p>
    </div>
    <div class="card-body card-spacing">
        <div class="row g-3">
            {{-- Primeira linha: Tipo de Documento, Número, Data de Emissão, Data de Validade --}}
            <div class="col-md-3">
                <x-bootstrap::form.select
                    name="form.identity_document_id"
                    label="Tipo de Documento"
                    wire:model.defer="form.identity_document_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->identityDocuments as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                    @endforeach
                </x-bootstrap::form.select>
                @error('form.identity_document_id')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <x-bootstrap::form.input
                    type="text"
                    name="form.identity_document_number"
                    label="Número do Documento"
                    wire:model.defer="form.identity_document_number"
                    required
                />
                @error('form.identity_document_number')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <x-bootstrap::form.input
                    type="date"
                    name="form.identity_document_issue_date"
                    label="Data de Emissão"
                    wire:model.defer="form.identity_document_issue_date"
                    required
                />
                @error('form.identity_document_issue_date')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <x-bootstrap::form.input
                    type="date"
                    name="form.identity_document_expiry_date"
                    label="Data de Validade"
                    wire:model.defer="form.identity_document_expiry_date"
                    required
                    help="O documento deve ser válido por pelo menos 6 meses"
                />
                @error('form.identity_document_expiry_date')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Segunda linha: País de Residência, Província de Residência, Distrito de Residência --}}
            <div class="col-md-4">
                <x-bootstrap::form.select
                    name="form.living_country_id"
                    label="País de Residência"
                    wire:model.live="form.living_country_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </x-bootstrap::form.select>
                @error('form.living_country_id')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.select
                    name="form.living_province_id"
                    label="Província de Residência"
                    wire:model.live="form.living_province_id"
                    :disabled="!$this->livingCountryHasProvinces"
                >
                    <option value="">Selecione</option>
                    @if($this->livingCountryHasProvinces)
                        @foreach($this->livingProvinces as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    @else
                        <option value="0">Estrangeiro</option>
                    @endif
                </x-bootstrap::form.select>
            </div>
            <div class="col-md-4">
                <x-bootstrap::form.select
                    name="form.living_district_id"
                    label="Distrito de Residência"
                    wire:model.defer="form.living_district_id"
                    :disabled="!$this->livingProvinceHasDistricts"
                >
                    <option value="">Selecione</option>
                    @if($this->livingProvinceHasDistricts)
                        @foreach($this->livingDistricts as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    @else
                        <option value="0">Estrangeiro</option>
                    @endif
                </x-bootstrap::form.select>
            </div>

            {{-- Terceira linha: Bairro de Residência, Endereço --}}
            <div class="col-md-4">
                <x-bootstrap::form.input
                    type="text"
                    name="form.neighborhood"
                    label="Bairro de Residência"
                    wire:model.defer="form.neighborhood"
                    placeholder="Bairro de Residência"
                />
            </div>
            <div class="col-md-8">
                <x-bootstrap::form.input
                    type="text"
                    name="form.living_address"
                    label="Endereço"
                    wire:model.defer="form.living_address"
                    required
                    placeholder="Av, Rua, Número, Flat, etc."
                    help="Exemplo: Av. Julius Nyerere, Nº 1234, Flat 5"
                />
                @error('form.living_address')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Quarta linha: Telefone Alternativo, Telefone com WhatsApp --}}
            <div class="col-md-6">
                <x-bootstrap::form.input
                    type="tel"
                    name="form.phone_2"
                    label="Telefone Alternativo"
                    wire:model.defer="form.phone_2"
                    help="Formato: +258821234567 ou +2588212345678"
                />
                @error('form.phone_2')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6">
                <x-bootstrap::form.input
                    type="tel"
                    name="form.phone_whatsapp"
                    label="Telefone com WhatsApp"
                    wire:model.defer="form.phone_whatsapp"
                    help="Formato: +258821234567 ou +2588212345678"
                />
                @error('form.phone_whatsapp')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
</div>
