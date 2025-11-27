<div class="card card-spacing">
    <div class="card-header bg-transparent border-bottom">
        <h2 class="heading-2 mb-0">Selecionar Categoria de Certificação</h2>
        <p class="text-sm text-muted mb-0 mt-2">Escolha a categoria que corresponde ao seu perfil</p>
    </div>
    <div class="card-body card-spacing">
        @if($category > 0)
            <div class="alert alert-success d-flex justify-content-between align-items-center mb-spacing-4">
                <div>
                    <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                    <span>Categoria selecionada: <strong>{{ $this->categories[$category]['name'] ?? '' }}</strong></span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="$set('category', 0)" aria-label="Alterar Categoria">
                    <i class="fas fa-edit me-1" aria-hidden="true"></i>Alterar
                </button>
            </div>
        @else
            <div class="row g-4">
                @foreach($this->categories as $catNum => $catData)
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body card-spacing">
                                <div class="text-center mb-3">
                                    <div class="bg-warning text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                        <i class="fas fa-graduation-cap fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <h5 class="heading-5 text-warning mb-0">Categoria {{ $catNum }}</h5>
                                    <p class="text-sm text-muted">{{ $catData['name'] }}</p>
                                </div>

                                <div class="mb-spacing-3">
                                    <p class="text-sm text-muted">{{ $catData['description'] }}</p>
                                    <ul class="list-unstyled text-sm mb-0">
                                        <li class="mb-1"><i class="fas fa-file-alt text-warning me-2" aria-hidden="true"></i>Documentos necessários: {{ $catData['documents_count'] }}</li>
                                        <li class="mb-1"><i class="fas fa-money-bill-wave text-warning me-2" aria-hidden="true"></i>Taxa total: {{ number_format($catData['fee'], 2, ',', '.') }} MT</li>
                                    </ul>
                                </div>

                                <div class="d-grid">
                                    <button type="button" class="btn btn-warning" wire:click="selectCategory({{ $catNum }})" aria-label="Selecionar Categoria {{ $catNum }}">
                                        <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Selecionar Categoria {{ $catNum }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

