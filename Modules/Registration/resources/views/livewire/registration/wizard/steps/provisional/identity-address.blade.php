<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Identificação e Morada</h2>
        <p class="text-sm text-muted mb-0 mt-2">Informações do documento de identidade e morada de residência</p>
    </div>
    <div class="card-body card-spacing">
        <div class="row g-3">
            {{-- Primeira linha: Tipo de Documento, Número, Data de Emissão, Data de Validade --}}
            <div class="col-md-3">
                <label for="form.identity_document_id" class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                <select
                    id="form.identity_document_id"
                    name="form.identity_document_id"
                    class="form-select @error('form.identity_document_id') is-invalid @enderror"
                    wire:model.defer="form.identity_document_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->identityDocuments as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                    @endforeach
                </select>
                @error('form.identity_document_id')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="form.identity_document_number" class="form-label">Número do Documento <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="form.identity_document_number"
                    name="form.identity_document_number"
                    class="form-control @error('form.identity_document_number') is-invalid @enderror"
                    wire:model.defer="form.identity_document_number"
                    required
                />
                @error('form.identity_document_number')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="form.identity_document_issue_date" class="form-label">Data de Emissão <span class="text-danger">*</span></label>
                <input
                    type="date"
                    id="form.identity_document_issue_date"
                    name="form.identity_document_issue_date"
                    class="form-control @error('form.identity_document_issue_date') is-invalid @enderror"
                    wire:model.defer="form.identity_document_issue_date"
                    required
                />
                @error('form.identity_document_issue_date')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="form.identity_document_expiry_date" class="form-label">Data de Validade <span class="text-danger">*</span></label>
                <input
                    type="date"
                    id="form.identity_document_expiry_date"
                    name="form.identity_document_expiry_date"
                    class="form-control @error('form.identity_document_expiry_date') is-invalid @enderror"
                    wire:model.defer="form.identity_document_expiry_date"
                    required
                />
                <small class="form-text text-muted">O documento deve ser válido por pelo menos 6 meses</small>
                @error('form.identity_document_expiry_date')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Segunda linha: País de Residência, Província de Residência, Distrito de Residência --}}
            <div class="col-md-4">
                <label for="form.living_country_id" class="form-label">País de Residência <span class="text-danger">*</span></label>
                <select
                    id="form.living_country_id"
                    name="form.living_country_id"
                    class="form-select @error('form.living_country_id') is-invalid @enderror"
                    wire:model.live="form.living_country_id"
                    required
                >
                    <option value="">Selecione</option>
                    @foreach($this->countries as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('form.living_country_id')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="form.living_province_id" class="form-label">Província de Residência</label>
                <select
                    id="form.living_province_id"
                    name="form.living_province_id"
                    class="form-select"
                    wire:model.live="form.living_province_id"
                    @if(!$this->livingCountryHasProvinces) disabled @endif
                >
                    <option value="">Selecione</option>
                    @if($this->livingCountryHasProvinces)
                        @foreach($this->livingProvinces as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    @else
                        <option value="0">Estrangeiro</option>
                    @endif
                </select>
            </div>
            <div class="col-md-4">
                <label for="form.living_district_id" class="form-label">Distrito de Residência</label>
                <select
                    id="form.living_district_id"
                    name="form.living_district_id"
                    class="form-select"
                    wire:model.defer="form.living_district_id"
                    @if(!$this->livingProvinceHasDistricts) disabled @endif
                >
                    <option value="">Selecione</option>
                    @if($this->livingProvinceHasDistricts)
                        @foreach($this->livingDistricts as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    @else
                        <option value="0">Estrangeiro</option>
                    @endif
                </select>
            </div>

            {{-- Terceira linha: Bairro de Residência, Endereço --}}
            <div class="col-md-4">
                <label for="form.neighborhood" class="form-label">Bairro de Residência</label>
                <input
                    type="text"
                    id="form.neighborhood"
                    name="form.neighborhood"
                    class="form-control"
                    wire:model.defer="form.neighborhood"
                    placeholder="Bairro de Residência"
                />
            </div>
            <div class="col-md-8">
                <label for="form.living_address" class="form-label">Endereço <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="form.living_address"
                    name="form.living_address"
                    class="form-control @error('form.living_address') is-invalid @enderror"
                    wire:model.defer="form.living_address"
                    required
                    placeholder="Av, Rua, Número, Flat, etc."
                />
                <small class="form-text text-muted">Exemplo: Av. Julius Nyerere, Nº 1234, Flat 5</small>
                @error('form.living_address')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Quarta linha: Telefone Alternativo, Telefone com WhatsApp --}}
            <div class="col-md-6">
                <label for="form.phone_2" class="form-label">Telefone Alternativo</label>
                <input
                    type="tel"
                    id="form.phone_2"
                    name="form.phone_2"
                    class="form-control @error('form.phone_2') is-invalid @enderror"
                    wire:model.defer="form.phone_2"
                    placeholder="+258821234567"
                />
                <small class="form-text text-muted">Formato: +258821234567 ou +2588212345678</small>
                @error('form.phone_2')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="form.phone_whatsapp" class="form-label">Telefone com WhatsApp</label>
                <input
                    type="tel"
                    id="form.phone_whatsapp"
                    name="form.phone_whatsapp"
                    class="form-control @error('form.phone_whatsapp') is-invalid @enderror"
                    wire:model.defer="form.phone_whatsapp"
                    placeholder="+258821234567"
                />
                <small class="form-text text-muted">Formato: +258821234567 ou +2588212345678</small>
                @error('form.phone_whatsapp')
                    <div class="invalid-feedback d-block">
                        <i class="ti ti-exclamation-circle me-1" aria-hidden="true"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
</div>
