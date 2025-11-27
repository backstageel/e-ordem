<x-layouts.app>
    <x-slot name="header">
        Minhas Inscrições
    </x-slot>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-1">Minhas Inscrições</h3>
                    <p class="text-muted mb-0">Gerencie suas inscrições na Ordem dos Médicos</p>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle d-inline-flex align-items-center" type="button" id="newRegistrationDropdown" data-bs-toggle="dropdown">
                            <i class="ti ti-plus me-1"></i>Nova Inscrição
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="newRegistrationDropdown">
                            <li><a class="dropdown-item" href="{{ route('guest.registrations.type-selection') }}">
                                <i class="ti ti-user-plus me-2"></i>Nova Inscrição
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(isset($stats['active_registration']) && $stats['active_registration']?->isRenewable())
                            <li><a class="dropdown-item" href="{{ route('member.registrations.renew', $stats['active_registration']) }}">
                                <i class="ti ti-refresh me-2"></i>Renovar Inscrição
                            </a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Registration Status -->
    <div class="row row-cols-1 row-cols-xl-3 row-cols-md-1 mb-4">
        <div class="col">
            <div class="card shadow-sm {{ isset($stats['active_registration']) ? 'border-success' : 'border-warning' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="mb-1 text-muted">Status Atual</p>
                            <h3 class="fw-bold mb-0 {{ isset($stats['active_registration']) ? 'text-success' : 'text-warning' }}">
                                {{ isset($stats['active_registration']) ? 'Ativo' : 'Sem Inscrição Ativa' }}
                            </h3>
                            @if(isset($stats['active_registration']))
                            <div class="mt-2">
                                <span class="badge bg-success-light text-success">
                                    {{ $stats['active_registration']->registrationType->name }}
                                </span>
                                <span class="text-muted ms-2">#{{ $stats['active_registration']->registration_number }}</span>
                            </div>
                            @endif
                        </div>
                        <span class="avatar border {{ isset($stats['active_registration']) ? 'border-success text-success' : 'border-warning text-warning' }} rounded-2 flex-shrink-0">
                            <i class="ti ti-{{ isset($stats['active_registration']) ? 'check-circle' : 'alert-circle' }} fs-20"></i>
                        </span>
                    </div>
                    @if(!isset($stats['active_registration']))
                    <div class="mt-3">
                        <a href="{{ route('guest.registrations.type-selection') }}" class="btn btn-sm btn-warning w-100">
                            <i class="ti ti-plus me-1"></i>Criar Nova Inscrição
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm {{ isset($stats['active_registration']) ? 'border-warning' : 'border-secondary' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="mb-1 text-muted">Validade</p>
                            <h3 class="fw-bold mb-0">
                                {{ isset($stats['active_registration']) && $stats['active_registration']->expiry_date
                                    ? $stats['active_registration']->expiry_date->format('d/m/Y')
                                    : 'N/A' }}
                            </h3>
                            @if(isset($stats['active_registration']) && $stats['active_registration']->expiry_date)
                            <div class="mt-2">
                                <span class="badge bg-warning-light text-warning">
                                    {{ $stats['days_remaining'] ?? 0 }} dias restantes
                                </span>
                            </div>
                            @endif
                        </div>
                        <span class="avatar border {{ isset($stats['active_registration']) ? 'border-warning text-warning' : 'border-secondary text-secondary' }} rounded-2 flex-shrink-0">
                            <i class="ti ti-calendar fs-20"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="mb-1 text-muted">Total de Inscrições</p>
                            <h3 class="fw-bold mb-0">{{ $stats['total_registrations'] ?? 0 }}</h3>
                            @if($registrations->isNotEmpty())
                            <div class="mt-2">
                                <span class="badge bg-primary-light text-primary">
                                    Desde {{ $registrations->last()->submission_date->format('Y') }}
                                </span>
                            </div>
                            @endif
                        </div>
                        <span class="avatar border border-primary text-primary rounded-2 flex-shrink-0">
                            <i class="ti ti-history fs-20"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registrations List -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0">Histórico de Inscrições</h5>
                    <div class="d-flex gap-2">
                        @if($registrations->isNotEmpty())
                        <a href="{{ route('member.registrations.show', $registrations->first()) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-eye me-1"></i>Acompanhar Status
                        </a>
                        @endif
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-filter me-1"></i>Filtrar
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Todas</a></li>
                                <li><a class="dropdown-item" href="#">Ativas</a></li>
                                <li><a class="dropdown-item" href="#">Expiradas</a></li>
                                <li><a class="dropdown-item" href="#">Pendentes</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Tipo</th>
                                    <th>Data de Inscrição</th>
                                    <th>Validade</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $registration)
                                <tr>
                                    <td>
                                        <strong>#{{ $registration->registration_number }}</strong>
                                        @if(isset($stats['active_registration']) && $stats['active_registration']->id === $registration->id)
                                        <br><small class="text-muted">Atual</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $registration->registrationType->name === 'Provisional' ? 'secondary' : 'primary' }}">
                                            {{ $registration->registrationType->name }}
                                        </span>
                                    </td>
                                    <td>{{ $registration->submission_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($registration->expiry_date)
                                            {{ $registration->expiry_date->format('d/m/Y') }}
                                            @if($registration->isActive())
                                                <br><small class="text-warning">{{ now()->diffInDays($registration->expiry_date, false) }} dias restantes</small>
                                            @else
                                                <br><small class="text-muted">Expirada</small>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <x-status-badge :status="$registration->status" :size="'sm'" />
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('member.registrations.show', $registration) }}" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @if($registration->isRenewable())
                                            <a href="{{ route('member.registrations.renew', $registration) }}" class="btn btn-sm btn-outline-warning" title="Renovar">
                                                <i class="ti ti-refresh"></i>
                                            </a>
                                            @endif
                                            @if($registration->status === 'approved')
                                            <button type="button" class="btn btn-sm btn-outline-info" title="Baixar Certificado">
                                                <i class="ti ti-download"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="avatar bg-light rounded-2 p-3 mb-3">
                                                <i class="ti ti-file-text fs-32 text-muted"></i>
                                            </div>
                                            <p class="text-muted mb-2">Nenhuma inscrição encontrada</p>
                                            <a href="{{ route('guest.registrations.type-selection') }}" class="btn btn-sm btn-primary">
                                                <i class="ti ti-plus me-1"></i>Criar Nova Inscrição
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('guest.registrations.type-selection') }}" class="btn btn-outline-primary w-100 p-3 text-center d-flex flex-column align-items-center">
                                <i class="ti ti-user-plus fs-24 mb-2"></i>
                                <strong>Nova Inscrição</strong>
                                <small class="text-muted">Escolher Tipo</small>
                            </a>
                        </div>
                        <div class="col-md-4">
                            @if(isset($stats['active_registration']) && $stats['active_registration']?->isRenewable())
                            <a href="{{ route('member.registrations.renew', $stats['active_registration']) }}" class="btn btn-outline-warning w-100 p-3 text-center d-flex flex-column align-items-center">
                                <i class="ti ti-refresh fs-24 mb-2"></i>
                                <strong>Renovar</strong>
                                <small class="text-muted">Inscrição Atual</small>
                            </a>
                            @else
                            <button disabled class="btn btn-outline-secondary w-100 p-3 text-center d-flex flex-column align-items-center">
                                <i class="ti ti-refresh fs-24 mb-2"></i>
                                <strong>Renovar</strong>
                                <small class="text-muted">Não Disponível</small>
                            </button>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if($registrations->isNotEmpty())
                            <a href="{{ route('member.registrations.show', $registrations->first()) }}" class="btn btn-outline-info w-100 p-3 text-center d-flex flex-column align-items-center">
                                <i class="ti ti-search fs-24 mb-2"></i>
                                <strong>Acompanhar</strong>
                                <small class="text-muted">Status</small>
                            </a>
                            @else
                            <button disabled class="btn btn-outline-secondary w-100 p-3 text-center d-flex flex-column align-items-center">
                                <i class="ti ti-search fs-24 mb-2"></i>
                                <strong>Acompanhar</strong>
                                <small class="text-muted">Não Disponível</small>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Notices -->
    @if(isset($stats['active_registration']) && $stats['active_registration']?->isRenewable())
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Avisos Importantes</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="ti ti-alert-triangle me-3 fs-20"></i>
                        <div>
                            <strong>Renovação Próxima:</strong> Sua inscrição vence em {{ $stats['days_remaining'] ?? 0 }} dias ({{ $stats['active_registration']->expiry_date->format('d/m/Y') }}).
                            <a href="{{ route('member.registrations.renew', $stats['active_registration']) }}" class="alert-link">Renovar agora</a> para evitar interrupções.
                        </div>
                    </div>
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="ti ti-info-circle me-3 fs-20"></i>
                        <div>
                            <strong>Documentos:</strong> Mantenha seus documentos sempre atualizados para facilitar futuras renovações.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-layouts.app>
