<?php $page = 'admin-dashboard'; ?>
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
                        <h4 class="fw-bold mb-0">Dashboard Administrativo</h4>
                        <p class="text-muted mb-0">Bem-vindo, {{ Auth::user()->name }}! Aqui está o resumo das actividades da plataforma.</p>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="{{ route('admin.registrations.wizard') }}" class="btn btn-primary d-inline-flex align-items-center"><i class="ti ti-plus me-1"></i>Nova Inscrição</a>
                        <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-white bg-white d-inline-flex align-items-center"><i class="ti ti-calendar-time me-1"></i>Ver Inscrições</a>
                    </div>
                </div>
                <!-- End Page Header -->

                <!-- System Alerts -->
                @if(!empty($system_alerts))
                <div class="row mb-4">
                    <div class="col-12">
                        @foreach($system_alerts as $alert)
                        <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                            <i class="ti ti-{{ str_replace('fas fa-', '', $alert['icon']) }} me-2"></i>
                            <strong>{{ $alert['title'] }}</strong> {{ $alert['message'] }}
                            @if(isset($alert['link']))
                            <a href="{{ $alert['link'] }}" class="alert-link">Ver mais</a>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- start row -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-01.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2 justify-content-between">
                                    <span class="avatar bg-primary rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-users fs-20"></i></span>
                                    <div class="text-end">
                                        @if(isset($members_growth))
                                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-{{ $members_growth >= 0 ? 'success' : 'danger' }}">
                                            {{ $members_growth >= 0 ? '+' : '' }}{{ number_format($members_growth, 1) }}%
                                        </span>
                                        @endif
                                        <p class="fs-13 mb-0">nos últimos 7 dias</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-1">Membros</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($total_members ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col" class="chart-set"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-xl-3 col-md-6">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-02.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2 justify-content-between">
                                    <span class="avatar bg-warning rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-calendar-check fs-20"></i></span>
                                    <div class="text-end">
                                        @if(isset($registrations_growth))
                                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-{{ $registrations_growth >= 0 ? 'success' : 'danger' }}">
                                            {{ $registrations_growth >= 0 ? '+' : '' }}{{ number_format($registrations_growth, 1) }}%
                                        </span>
                                        @endif
                                        <p class="fs-13 mb-0">nos últimos 7 dias</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-1">Inscrições Pendentes</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($total_registrations ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-2" class="chart-set"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-xl-3 col-md-6">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-03.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2 justify-content-between">
                                    <span class="avatar bg-info rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-clipboard-list fs-20"></i></span>
                                    <div class="text-end">
                                        @if(isset($exams_growth))
                                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-{{ $exams_growth >= 0 ? 'success' : 'danger' }}">
                                            {{ $exams_growth >= 0 ? '+' : '' }}{{ number_format($exams_growth, 1) }}%
                                        </span>
                                        @endif
                                        <p class="fs-13 mb-0">nos últimos 7 dias</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-1">Exames Abertos</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($total_exams ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-3" class="chart-set"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-xl-3 col-md-6">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-04.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2 justify-content-between">
                                    <span class="avatar bg-success rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-currency-dollar fs-20"></i></span>
                                    <div class="text-end">
                                        @if(isset($payments_growth))
                                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-{{ $payments_growth >= 0 ? 'success' : 'danger' }}">
                                            {{ $payments_growth >= 0 ? '+' : '' }}{{ number_format($payments_growth, 1) }}%
                                        </span>
                                        @endif
                                        <p class="fs-13 mb-0">este mês</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between overflow-hidden">
                                    <div>
                                        <p class="mb-1">Receitas</p>
                                        <h3 class="fw-bold mb-0 text-truncate">{{ number_format($payments_received ?? 0, 0, ',', '.') }} MT</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-4" class="chart-set"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <!-- row start -->
                <div class="row">
                    <!-- col start -->
                    <div class="col-xl-8">

                        <!-- card start -->
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Estatísticas de Inscrições</h5>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                        Mensal <i class="ti ti-chevron-down ms-1"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Mensal</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Semanal</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Anual</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <div class="row row-gap-3 mb-2">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body text-truncate"><i class="ti ti-point-filled me-1 text-primary"></i>Todas as Inscrições</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_all_registrations ?? 0) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-danger"></i>Rejeitadas</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_rejected_registrations ?? 0) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-warning"></i>Em Análise</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_under_review_registrations ?? 0) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-success"></i>Aprovadas</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_approved_registrations ?? 0) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="chart-set" id="registrationStatusChart"></div>
                            </div>
                        </div>
                        <!-- card end -->

                    </div>
                        <!-- col end -->

                        <!-- col start -->
                    <div class="col-xl-4">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0 text-truncate">Inscrições Recentes</h5>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                        Todas <i class="ti ti-chevron-down ms-1"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Pendentes</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Aprovadas</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                @forelse(($recent_registrations ?? [])->take(3) as $registration)
                                <div class="mb-3 bg-light p-3 rounded-2 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="fs-14 fw-semibold mb-1">{{ $registration->registrationType->name ?? 'N/A' }}</h6>
                                        <p class="mb-0 text-body text-truncate">
                                            <i class="ti ti-calendar-time me-1 text-body"></i>
                                            {{ $registration->created_at->format('d M Y, H:i') }}
                                        </p>
                                        <p class="mb-0 fs-12 text-muted mt-1">
                                            {{ ($registration->person->civility ?? 'Dr.') . ' ' . ($registration->person->full_name ?? 'N/A') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-status-badge :status="$registration->status" :size="'sm'" />
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <p class="text-muted mb-0">Nenhuma inscrição recente</p>
                                </div>
                                @endforelse
                                <a href="{{ route('admin.registrations.index') }}" class="btn btn-light w-100">Ver Todas as Inscrições</a>
                            </div>
                        </div>
                    </div>
                        <!-- col end -->
                </div>
                <!-- end row -->

                <!-- start row -->
                <div class="row">
                    <!-- col start -->
                    <div class="col-xl-4 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Top 3 Especialidades</h5>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"  data-bs-toggle="dropdown">
                                        Semanal <i class="ti ti-chevron-down ms-1"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Mensal</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Semanal</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Anual</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="members-by-speciality-chart" class="chart-set"></div>
                                <div class="d-flex align-items-center flex-wrap justify-content-center gap-2 mt-3">
                                    @forelse(($popular_specialties ?? [])->take(3) as $index => $specialty)
                                    @php
                                        $colors = ['text-info', 'text-purple', 'text-primary'];
                                        $color = $colors[$index] ?? 'text-primary';
                                    @endphp
                                    <p class="d-flex align-items-center mb-0 fs-13">
                                        <i class="ti ti-circle-filled {{ $color }} fs-10 me-1"></i>
                                        <span class="text-dark fw-semibold me-1">{{ number_format($specialty->members_count ?? 0) }}</span>
                                        {{ Str::limit($specialty->name, 15) }}
                                    </p>
                                    @empty
                                    <p class="text-muted mb-0">Nenhuma especialidade encontrada</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->

                    <!-- col start -->
                    <div class="col-xl-4 col-lg-6 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Status de Pagamentos</h5>
                                <a href="{{ route('admin.payments.index') }}" class="btn fw-normal btn-outline-white">Ver Todos</a>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-4">
                                    <div class="col d-flex border-end">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Recebidos</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($payments_completed_count ?? 0) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col d-flex border-end">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Pendentes</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($payments_pending_count ?? 0) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col d-flex">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Vencidos</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($payments_overdue_count ?? 0) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <p class="fw-semibold mb-1 text-dark">Receitas Este Ano</p>
                                        <p class="mb-0">{{ number_format($payments_received ?? 0, 2, ',', '.') }} MT</p>
                                    </div>
                                    <h6 class="fw-bold mb-0 text-success">+{{ number_format($payments_growth ?? 0, 1) }}%</h6>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <p class="fw-semibold mb-1 text-dark">Pendentes</p>
                                        <p class="mb-0">{{ number_format($payments_pending ?? 0, 2, ',', '.') }} MT</p>
                                    </div>
                                    <h6 class="fw-bold mb-0 text-warning">Aguardando</h6>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <div>
                                        <p class="fw-semibold mb-1 text-dark">Vencidos</p>
                                        <p class="mb-0">{{ number_format($payments_overdue ?? 0, 2, ',', '.') }} MT</p>
                                    </div>
                                    <h6 class="fw-bold mb-0 text-danger">Urgente</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->

                    <!-- col start -->
                    <div class="col-xl-4 col-lg-6 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Receitas por Tipo</h5>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"  data-bs-toggle="dropdown">
                                        Semanal <i class="ti ti-chevron-down ms-1"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Mensal</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Semanal</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Anual</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <p class="fw-semibold mb-1 text-dark">Inscrições</p>
                                        <p class="mb-0">{{ number_format($payments_by_type['registration']['amount'] ?? 0, 2, ',', '.') }} MT</p>
                                    </div>
                                    <h6 class="fw-bold mb-0">{{ number_format($payments_by_type['registration']['count'] ?? 0) }} pagamentos</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <p class="fw-semibold mb-1 text-dark">Quotas</p>
                                        <p class="mb-0">{{ number_format($payments_by_type['quota']['amount'] ?? 0, 2, ',', '.') }} MT</p>
                                    </div>
                                    <h6 class="fw-bold mb-0">{{ number_format($payments_by_type['quota']['count'] ?? 0) }} pagamentos</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <p class="fw-semibold mb-1 text-dark">Exames</p>
                                        <p class="mb-0">{{ number_format($payments_by_type['exam']['amount'] ?? 0, 2, ',', '.') }} MT</p>
                                    </div>
                                    <h6 class="fw-bold mb-0">{{ number_format($payments_by_type['exam']['count'] ?? 0) }} pagamentos</h6>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0">
                                    <div>
                                        <p class="fw-semibold mb-1 text-dark">Cartões</p>
                                        <p class="mb-0">{{ number_format($payments_by_type['card']['amount'] ?? 0, 2, ',', '.') }} MT</p>
                                    </div>
                                    <h6 class="fw-bold mb-0">{{ number_format($payments_by_type['card']['count'] ?? 0) }} pagamentos</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                </div>
                <!-- end row -->

                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Todas as Inscrições Recentes</h5>
                                <a href="{{ route('admin.registrations.index') }}" class="btn fw-normal btn-outline-white">Ver Todas</a>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Código</th>
                                                <th>Médico</th>
                                                <th>Data</th>
                                                <th>Tipo</th>
                                                <th>Status</th>
                                                <th class="text-end">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recent_registrations ?? [] as $registration)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="fs-14 mb-1 fw-semibold">#{{ $registration->registration_number ?? $registration->id }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="fs-14 mb-1 fw-semibold">{{ ($registration->person->civility ?? 'Dr.') . ' ' . ($registration->person->full_name ?? 'N/A') }}</h6>
                                                            <p class="mb-0 fs-13 text-muted">{{ $registration->person->email ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $registration->created_at->format('d M Y - H:i') }}</td>
                                                <td>{{ $registration->registrationType->name ?? 'N/A' }}</td>
                                                <td>
                                                    <x-status-badge :status="$registration->status" :size="'sm'" />
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                                        <a href="{{ route('admin.registrations.show', $registration->id) }}" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <div class="dropdown">
                                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                @if($registration->isRenewable())
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('admin.registrations.renew', $registration->id) }}">
                                                                        <i class="ti ti-refresh me-2"></i>Renovar
                                                                    </a>
                                                                </li>
                                                                @endif
                                                                @if($registration->isExpired())
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('admin.registrations.reinstate', $registration->id) }}">
                                                                        <i class="ti ti-rotate-clockwise me-2"></i>Reinscrever
                                                                    </a>
                                                                </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <p class="text-muted mb-0">Nenhuma inscrição encontrada</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- row end -->

                <!-- row start -->
                <div class="row">
                    <!-- col start -->
                    <div class="col-xl-4 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Top 5 Especialidades</h5>
                                <a href="{{ route('admin.medical-specialities.index') }}" class="btn fw-normal btn-outline-white">Ver Todas</a>
                            </div>
                            <div class="card-body">
                                @forelse(($popular_specialties ?? [])->take(5) as $specialty)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 flex-shrink-0 bg-light rounded-circle p-2">
                                            <i class="ti ti-stethoscope text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="fs-14 mb-1 text-truncate fw-medium">{{ $specialty->name }}</h6>
                                            <p class="mb-0 fs-13 text-muted text-truncate">Especialidade Médica</p>
                                        </div>
                                    </div>
                                    <span class="badge fw-medium badge-soft-primary border border-primary flex-shrink-0">{{ number_format($specialty->members_count ?? 0) }} Membros</span>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <p class="text-muted mb-0">Nenhuma especialidade encontrada</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <!-- col end -->

                    <!-- col start -->
                    <div class="col-xl-4 col-lg-6 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Transacções Recentes</h5>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"  data-bs-toggle="dropdown">
                                        Hoje <i class="ti ti-chevron-down ms-1"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Hoje</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Esta Semana</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Este Mês</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                @forelse(($recent_payments ?? []) as $payment)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 flex-shrink-0 bg-light rounded-circle p-2">
                                            <i class="ti ti-credit-card text-{{ $payment->status->isCompleted() ? 'success' : 'warning' }}"></i>
                                        </div>
                                        <div>
                                            <h6 class="fs-14 mb-1 text-truncate fw-semibold">{{ $payment->paymentType->name ?? 'Pagamento' }}</h6>
                                            <p class="mb-0 fs-13 text-truncate">
                                                <a href="javascript:void(0);" class="link-primary">#{{ $payment->id }}</a>
                                            </p>
                                        </div>
                                    </div>
                                    <span class="badge fw-medium bg-{{ $payment->status->isCompleted() ? 'success' : 'danger' }} flex-shrink-0">
                                        {{ $payment->status->isCompleted() ? '+' : '' }}{{ number_format($payment->amount ?? 0, 2, ',', '.') }} MT
                                    </span>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <p class="text-muted mb-0">Nenhuma transacção encontrada</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <!-- col end -->

                    <!-- col start -->
                    <div class="col-xl-4 col-lg-6 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Alertas do Sistema</h5>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center"  data-bs-toggle="dropdown">
                                        Hoje <i class="ti ti-chevron-down ms-1"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Hoje</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Esta Semana</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Este Mês</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(!empty($system_alerts))
                                    @foreach($system_alerts as $alert)
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar flex-shrink-0 bg-soft-{{ $alert['type'] }} rounded-circle p-2">
                                                <i class="ti ti-{{ str_replace('fas fa-', '', $alert['icon']) }} text-{{ $alert['type'] }}"></i>
                                            </div>
                                            <div class="ms-2">
                                                <div>
                                                    <h6 class="fw-semibold text-truncate mb-1 fs-14">{{ $alert['title'] }}</h6>
                                                    <p class="fs-13 mb-0 text-truncate">{{ Str::limit($alert['message'], 40) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0);" class="d-inline-flex bg-soft-{{ $alert['type'] }} text-{{ $alert['type'] }} p-2 rounded-circle">
                                                <i class="ti ti-arrow-right fw-bold"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                <div class="text-center py-4">
                                    <p class="text-muted mb-0">Nenhum alerta no momento</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- col end -->

                </div>
                <!-- row end -->

            </div>
            <!-- End Content -->

            @component('components.footer')
            @endcomponent

        </div>

        <!-- ========================
            End Page Content
        ========================= -->

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for ApexCharts to be fully loaded
            function initCharts() {
                if (typeof ApexCharts === 'undefined') {
                    setTimeout(initCharts, 100);
                    return;
                }

                // Initialize mini charts for stat cards (sparklines)
                // Chart for Members card (s-col) - Bar chart
                if (document.querySelector("#s-col")) {
                    var membersChart = new ApexCharts(document.querySelector("#s-col"), {
                        chart: {
                            width: 80,
                            height: 54,
                            type: 'bar',
                            toolbar: { show: false },
                            sparkline: { enabled: true }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '70%',
                                borderRadius: 3,
                                endingShape: 'rounded'
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: { show: false },
                        series: [{
                            name: 'Data',
                            data: [
                                { x: 'A', y: {{ $members_chart_data['last_7_days'] ?? 40 }}, fillColor: '#2d5016' },
                                { x: 'B', y: {{ $members_chart_data['current_day'] ?? 15 }}, fillColor: '#2d5016' },
                                { x: 'C', y: {{ ($members_chart_data['last_7_days'] ?? 40) + 20 }}, fillColor: '#2d5016' },
                                { x: 'D', y: {{ ($members_chart_data['current_day'] ?? 15) + 10 }}, fillColor: '#2d5016' },
                                { x: 'E', y: {{ ($members_chart_data['last_7_days'] ?? 40) + 50 }}, fillColor: '#4a7c2a' },
                                { x: 'F', y: {{ ($members_chart_data['current_day'] ?? 15) + 5 }}, fillColor: '#2d5016' },
                                { x: 'G', y: {{ ($members_chart_data['last_7_days'] ?? 40) + 30 }}, fillColor: '#2d5016' }
                            ]
                        }],
                        xaxis: {
                            labels: { show: false },
                            axisTicks: { show: false },
                            axisBorder: { show: false }
                        },
                        yaxis: { show: false },
                        grid: { show: false },
                        tooltip: { enabled: false }
                    });
                    membersChart.render();
                }

                // Chart for Registrations card (s-col-2) - Area chart
                if (document.querySelector("#s-col-2")) {
                    var registrationsChart = new ApexCharts(document.querySelector("#s-col-2"), {
                        chart: {
                            width: 100,
                            height: 54,
                            type: 'area',
                            toolbar: { show: false },
                            sparkline: { enabled: true }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 1,
                            colors: ['#ffc107']
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.4,
                                opacityTo: 0,
                                stops: [0, 90, 100],
                                colorStops: [
                                    { offset: 0, color: "#ffc107", opacity: 0.4 },
                                    { offset: 100, color: "#ffffff", opacity: 0.1 }
                                ]
                            }
                        },
                        dataLabels: { enabled: false },
                        series: [{
                            name: 'Data',
                            data: [
                                {{ $registrations_chart_data['last_7_days'] ?? 22 }},
                                {{ $registrations_chart_data['current_day'] ?? 35 }},
                                {{ ($registrations_chart_data['last_7_days'] ?? 22) + 8 }},
                                {{ ($registrations_chart_data['current_day'] ?? 35) + 5 }},
                                {{ ($registrations_chart_data['last_7_days'] ?? 22) - 2 }},
                                {{ ($registrations_chart_data['current_day'] ?? 35) + 10 }},
                                {{ ($registrations_chart_data['last_7_days'] ?? 22) + 5 }}
                            ]
                        }],
                        xaxis: {
                            labels: { show: false },
                            axisTicks: { show: false },
                            axisBorder: { show: false }
                        },
                        yaxis: { show: false },
                        grid: { show: false },
                        tooltip: { enabled: false }
                    });
                    registrationsChart.render();
                }

                // Chart for Exams card (s-col-3) - Bar chart
                if (document.querySelector("#s-col-3")) {
                    var examsChart = new ApexCharts(document.querySelector("#s-col-3"), {
                        chart: {
                            width: 80,
                            height: 54,
                            type: 'bar',
                            toolbar: { show: false },
                            sparkline: { enabled: true }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '70%',
                                borderRadius: 0,
                                endingShape: 'rounded'
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: { show: false },
                        series: [{
                            name: 'Data',
                            data: [
                                { x: 'A', y: {{ $exams_chart_data['last_7_days'] ?? 80 }}, fillColor: '#0dcaf0' },
                                { x: 'B', y: {{ $exams_chart_data['current_day'] ?? 35 }}, fillColor: '#0dcaf0' },
                                { x: 'C', y: {{ ($exams_chart_data['last_7_days'] ?? 80) - 30 }}, fillColor: '#0dcaf0' },
                                { x: 'D', y: {{ ($exams_chart_data['current_day'] ?? 35) + 10 }}, fillColor: '#0dcaf0' },
                                { x: 'E', y: {{ ($exams_chart_data['last_7_days'] ?? 80) - 45 }}, fillColor: '#0dcaf0' },
                                { x: 'F', y: {{ ($exams_chart_data['current_day'] ?? 35) + 25 }}, fillColor: '#0dcaf0' },
                                { x: 'G', y: {{ ($exams_chart_data['last_7_days'] ?? 80) - 20 }}, fillColor: '#0dcaf0' }
                            ]
                        }],
                        xaxis: {
                            labels: { show: false },
                            axisTicks: { show: false },
                            axisBorder: { show: false }
                        },
                        yaxis: { show: false },
                        grid: { show: false },
                        tooltip: { enabled: false }
                    });
                    examsChart.render();
                }

                // Chart for Revenue card (s-col-4) - Area chart
                if (document.querySelector("#s-col-4")) {
                    var revenueChart = new ApexCharts(document.querySelector("#s-col-4"), {
                        chart: {
                            width: 100,
                            height: 54,
                            type: 'area',
                            toolbar: { show: false },
                            sparkline: { enabled: true }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2,
                            colors: ['#198754']
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.5,
                                opacityTo: 0,
                                stops: [0, 90, 100],
                                colorStops: [
                                    { offset: 0, color: "#198754", opacity: 0.4 },
                                    { offset: 100, color: "#ffffff", opacity: 0.1 }
                                ]
                            }
                        },
                        dataLabels: { enabled: false },
                        series: [{
                            name: 'Data',
                            data: [
                                {{ ($payments_chart_data['last_7_days'] ?? 20) / 1000 }},
                                {{ ($payments_chart_data['current_day'] ?? 12) / 1000 }},
                                {{ (($payments_chart_data['last_7_days'] ?? 20) - 8) / 1000 }},
                                {{ (($payments_chart_data['current_day'] ?? 12) + 2) / 1000 }},
                                {{ (($payments_chart_data['last_7_days'] ?? 20) - 11) / 1000 }},
                                {{ (($payments_chart_data['current_day'] ?? 12) + 13) / 1000 }},
                                {{ (($payments_chart_data['last_7_days'] ?? 20) + 10) / 1000 }},
                                {{ (($payments_chart_data['current_day'] ?? 12) + 8) / 1000 }},
                                {{ (($payments_chart_data['last_7_days'] ?? 20) + 15) / 1000 }},
                                {{ (($payments_chart_data['current_day'] ?? 12) + 20) / 1000 }}
                            ]
                        }],
                        xaxis: {
                            labels: { show: false },
                            axisTicks: { show: false },
                            axisBorder: { show: false }
                        },
                        yaxis: { show: false },
                        grid: { show: false },
                        tooltip: { enabled: false }
                    });
                    revenueChart.render();
                }

                // Registration Status Chart (Line Chart) - Statistics by status
                var registrationStatusChartElement = document.querySelector("#registrationStatusChart");
                if (registrationStatusChartElement) {
                    @php
                        $approvedData = $approved_data ?? [];
                        $pendingData = $pending_data ?? [];
                        $rejectedData = $rejected_data ?? [];
                        $underReviewData = $under_review_data ?? [];
                        $chartMonths = $months ?? ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
                    @endphp

                    var registrationStatusChartOptions = {
                        series: [{
                            name: 'Aprovadas',
                            data: @json($approvedData)
                        }, {
                            name: 'Pendentes',
                            data: @json($pendingData)
                        }, {
                            name: 'Em Análise',
                            data: @json($underReviewData)
                        }, {
                            name: 'Rejeitadas',
                            data: @json($rejectedData)
                        }],
                        chart: {
                            type: 'line',
                            height: 350,
                            toolbar: { show: false },
                            zoom: { enabled: false }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        xaxis: {
                            categories: @json($chartMonths),
                            labels: {
                                style: {
                                    fontSize: '14px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontSize: '14px'
                                }
                            }
                        },
                        colors: ['#198754', '#ffc107', '#0dcaf0', '#dc3545'],
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center'
                        },
                        markers: {
                            size: 5,
                            colors: ['#fff'],
                            strokeColors: ['#198754', '#ffc107', '#0dcaf0', '#dc3545'],
                            strokeWidth: 2,
                            hover: {
                                size: 7
                            }
                        },
                        tooltip: {
                            x: {
                                format: 'MMM yyyy'
                            },
                            y: {
                                formatter: function(val) {
                                    return val + ' inscrições'
                                }
                            }
                        },
                        grid: {
                            borderColor: '#e7e7e7',
                            strokeDashArray: 3,
                            padding: {
                                left: 0,
                                right: 0
                            }
                        }
                    };
                    var registrationStatusChart = new ApexCharts(registrationStatusChartElement, registrationStatusChartOptions);
                    registrationStatusChart.render();
                }

                // Members by Speciality Chart (Donut Chart)
                var membersBySpecialityChartElement = document.querySelector("#members-by-speciality-chart");
                if (membersBySpecialityChartElement) {
                    @php
                        $membersBySpecialityData = $members_by_speciality ?? [];
                    @endphp

                    @if(!empty($membersBySpecialityData))
                    var membersBySpecialityData = @json($membersBySpecialityData);
                    var membersBySpecialityOptions = {
                        series: Object.values(membersBySpecialityData),
                        chart: {
                            type: 'donut',
                            height: 350,
                            toolbar: { show: false }
                        },
                        labels: Object.keys(membersBySpecialityData),
                        colors: ['#2d5016', '#4a7c2a', '#6ba83a', '#198754', '#0dcaf0', '#ffc107', '#dc3545', '#8a2be2', '#ff69b4', '#ffd700'],
                        legend: {
                            position: 'bottom'
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };
                    var membersBySpecialityChart = new ApexCharts(membersBySpecialityChartElement, membersBySpecialityOptions);
                    membersBySpecialityChart.render();
                    @else
                    // Show empty state if no data
                    membersBySpecialityChartElement.innerHTML = '<div class="text-center py-5"><p class="text-muted">Nenhuma especialidade encontrada</p></div>';
                    @endif
                }
            }

            // Initialize all charts
            initCharts();
        });
    </script>
    @endpush

</x-layouts.app>
