<?php $page = 'admin-registrations'; ?>
<x-layouts.app>
    <!-- ========================
            Start Page Content
        ========================= -->

        <div class="page-wrapper">

            <!-- Start Content -->
            <div class="content pb-0">

                <!-- Page Header -->
                <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">Gestão de Inscrições</h4>
                        <p class="text-muted mb-0">Gerir todas as inscrições médicas do sistema.</p>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="{{ route('admin.registrations.export', request()->query()) }}" class="btn btn-outline-white bg-white d-inline-flex align-items-center">
                            <i class="ti ti-download me-1"></i>Exportar
                        </a>
                        <a href="{{ route('admin.registrations.type-selection') }}" class="btn btn-primary d-inline-flex align-items-center">
                            <i class="ti ti-plus me-1"></i>Nova Inscrição
                        </a>
                    </div>
                </div>
                <!-- End Page Header -->

                <!-- Stats Cards -->
                <div class="row row-cols-1 row-cols-xl-4 row-cols-md-2 row-cols-sm-1 mb-4">
                    <div class="col">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-01.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <p class="mb-1 text-muted">Total de Inscrições</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($totalCount ?? 0) }}</h3>
                                    </div>
                                    <span class="avatar border border-primary text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                        <i class="ti ti-list fs-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-02.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <p class="mb-1 text-muted">Aprovadas</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($approvedCount ?? 0) }}</h3>
                                    </div>
                                    <span class="avatar border border-success text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                        <i class="ti ti-check-circle fs-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-03.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <p class="mb-1 text-muted">Pendentes</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($pendingCount ?? 0) }}</h3>
                                    </div>
                                    <span class="avatar border border-warning text-warning rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                        <i class="ti ti-clock fs-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-04.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <p class="mb-1 text-muted">Rejeitadas</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($rejectedCount ?? 0) }}</h3>
                                    </div>
                                    <span class="avatar border border-danger text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                        <i class="ti ti-x-circle fs-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="fw-bold mb-0">Filtros de Pesquisa</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.registrations.index') }}" method="GET">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label for="search" class="form-label">Pesquisar</label>
                                            <div class="input-icon-start">
                                                <span class="input-icon-addon">
                                                    <i class="ti ti-search"></i>
                                                </span>
                                                <input type="text" name="filter[search]" id="filter_search" class="form-control" value="{{ request('filter.search') }}" placeholder="Nome, Nº ou Email...">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="filter[status]" id="filter_status" class="form-select">
                                                <option value="">Todos</option>
                                                @foreach($statusOptions ?? [] as $opt)
                                                    <option value="{{ $opt['value'] }}" {{ request('filter.status') == $opt['value'] ? 'selected' : '' }}>
                                                        {{ $opt['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="type" class="form-label">Tipo</label>
                                            <select name="filter[type]" id="filter_type" class="form-select">
                                                <option value="">Todos</option>
                                                @foreach($typeOptions ?? [] as $opt)
                                                    <option value="{{ $opt['value'] }}" {{ request('filter.type') == $opt['value'] ? 'selected' : '' }}>
                                                        {{ $opt['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="date_from" class="form-label">Data Início</label>
                                            <input type="date" name="filter[date_from]" id="filter_date_from" class="form-control" value="{{ request('filter.date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="date_to" class="form-label">Data Fim</label>
                                            <input type="date" name="filter[date_to]" id="filter_date_to" class="form-control" value="{{ request('filter.date_to') }}">
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="ti ti-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inscriptions Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Lista de Inscrições</h5>
                                <div>
                                    <a href="{{ route('admin.registrations.export', request()->query()) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-download me-1"></i>Exportar
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Código</th>
                                                <th>Nome do Candidato</th>
                                                <th>Telefone</th>
                                                <th>Data de Submissão</th>
                                                <th>Tipo</th>
                                                <th>Status</th>
                                                <th class="text-end">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($registrations as $registration)
                                                <tr>
                                                    <td>
                                                        <span class="fw-medium text-dark">#{{ $registration->registration_number }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                @if($registration->person->user && $registration->person->user->profile_photo_url)
                                                                    <img src="{{ $registration->person->user->profile_photo_url }}" class="rounded-circle border" alt="Foto" width="40" height="40">
                                                                @else
                                                                    <span class="avatar-initials rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                        {{ substr($registration->person->full_name, 0, 1) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="ms-3">
                                                                <div class="fw-bold text-dark">{{ $registration->person->full_name }}</div>
                                                                <small class="text-muted">{{ $registration->person->email ?? '—' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $registration->person->phone ?? ($registration->person->mobile ?? '—') }}</td>
                                                    <td>{{ $registration->submission_date ? $registration->submission_date->format('d/m/Y') : '—' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{
                                                            str_contains(strtolower($registration->registrationType->name), 'provisional') ? 'info' :
                                                            (str_contains(strtolower($registration->registrationType->name), 'effective') ? 'primary' :
                                                            (str_contains(strtolower($registration->registrationType->name), 'renewal') ? 'secondary' :
                                                            (str_contains(strtolower($registration->registrationType->name), 'reinstatement') ? 'success' : 'secondary')))
                                                        }}">
                                                            {{ $registration->registrationType->name }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <x-status-badge :status="$registration->status" :size="'sm'" />
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                                            <a href="{{ route('admin.registrations.show', $registration) }}" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                                                <i class="ti ti-eye"></i>
                                                            </a>
                                                            <div class="dropdown">
                                                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('admin.registrations.edit-wizard', $registration) }}">
                                                                            <i class="ti ti-edit me-2 text-secondary"></i> Editar
                                                                        </a>
                                                                    </li>
                                                                    @if($registration->status === \App\Enums\RegistrationStatus::SUBMITTED || $registration->status === \App\Enums\RegistrationStatus::UNDER_REVIEW)
                                                                        <li>
                                                                            <button class="dropdown-item text-success" type="button" data-bs-toggle="modal" data-bs-target="#approveModal{{ $registration->id }}">
                                                                                <i class="ti ti-check me-2"></i> Aprovar
                                                                            </button>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <button class="dropdown-item text-danger" type="button" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $registration->id }}">
                                                                            <i class="ti ti-x me-2"></i> Rejeitar
                                                                        </button>
                                                                    </li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li>
                                                                        <form action="{{ route('admin.registrations.destroy', $registration) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja apagar esta inscrição?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item text-danger">
                                                                                <i class="ti ti-trash me-2"></i> Apagar
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>

                                                        <!-- Approve Modal -->
                                                        <div class="modal fade" id="approveModal{{ $registration->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $registration->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form action="{{ route('admin.registrations.approve', $registration) }}" method="POST">
                                                                        @csrf
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title fw-bold" id="approveModalLabel{{ $registration->id }}">Aprovar Inscrição</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Tem certeza que deseja aprovar esta inscrição?</p>
                                                                            <div class="mb-3">
                                                                                <label for="notes{{ $registration->id }}" class="form-label">Observações (opcional)</label>
                                                                                <textarea name="notes" id="notes{{ $registration->id }}" class="form-control" rows="3"></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                            <button type="submit" class="btn btn-success">Aprovar</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Reject Modal -->
                                                        <div class="modal fade" id="rejectModal{{ $registration->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $registration->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form action="{{ route('admin.registrations.reject', $registration) }}" method="POST">
                                                                        @csrf
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title fw-bold" id="rejectModalLabel{{ $registration->id }}">Rejeitar Inscrição</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Tem certeza que deseja rejeitar esta inscrição?</p>
                                                                            <div class="mb-3">
                                                                                <label for="rejection_reason{{ $registration->id }}" class="form-label">Motivo da Rejeição <span class="text-danger">*</span></label>
                                                                                <textarea name="rejection_reason" id="rejection_reason{{ $registration->id }}" class="form-control" rows="3" required></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                            <button type="submit" class="btn btn-danger">Rejeitar</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-5">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <div class="avatar bg-light rounded-2 p-3 mb-3">
                                                                <i class="ti ti-file-text fs-32 text-muted"></i>
                                                            </div>
                                                            <h5 class="text-muted fw-semibold">Nenhuma inscrição encontrada</h5>
                                                            <p class="text-muted mb-0">Tente ajustar os filtros de pesquisa.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if($registrations->hasPages() || $registrations->total() > 0)
                                <div class="card-footer bg-transparent border-top-0 pt-3 pb-4">
                                    <x-pagination-enhanced :paginator="$registrations" />
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Content -->

            <x-footer />

        </div>

        <!-- ========================
            End Page Content
        ========================= -->
</x-layouts.app>
