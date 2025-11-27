<x-layouts.app>
    <x-slot name="header">
        Dashboard Administrativo
    </x-slot>

    <!-- System Alerts -->
    @if(!empty($system_alerts))
    <div class="row mb-4">
        <div class="col-12">
            @foreach($system_alerts as $alert)
            <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                <i class="ti ti-{{ $alert['icon'] }} me-2"></i>
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

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-1">Bem-vindo, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted mb-0">Aqui está o resumo das actividades da plataforma.</p>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <a href="{{ route('admin.registrations.wizard') }}" class="btn btn-primary d-inline-flex align-items-center">
                        <i class="ti ti-plus me-1"></i>Nova Inscrição
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row row-cols-1 row-cols-xl-4 row-cols-md-2 row-cols-sm-1 mb-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="mb-1 text-muted">Total de Membros</p>
                            <div class="d-flex align-items-center gap-1">
                                <h3 class="fw-bold mb-0">{{ $total_members ?? $total_doctors ?? 0 }}</h3>
                                @if(isset($members_growth) || isset($doctors_growth))
                                <span class="badge fw-medium bg-{{ ($members_growth ?? $doctors_growth ?? 0) >= 0 ? 'success' : 'danger' }} flex-shrink-0">
                                    {{ ($members_growth ?? $doctors_growth ?? 0) >= 0 ? '+' : '' }}{{ $members_growth ?? $doctors_growth ?? 0 }}%
                                </span>
                                @endif
                            </div>
                        </div>
                        <span class="avatar border border-primary text-primary rounded-2 flex-shrink-0">
                            <i class="ti ti-user-md fs-20"></i>
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
                            <p class="mb-1 text-muted">Inscrições Pendentes</p>
                            <div class="d-flex align-items-center gap-1">
                                <h3 class="fw-bold mb-0">{{ $total_registrations ?? 0 }}</h3>
                                @if(isset($registrations_growth))
                                <span class="badge fw-medium bg-{{ $registrations_growth >= 0 ? 'success' : 'danger' }} flex-shrink-0">
                                    {{ $registrations_growth >= 0 ? '+' : '' }}{{ $registrations_growth }}%
                                </span>
                                @endif
                            </div>
                        </div>
                        <span class="avatar border border-success text-success rounded-2 flex-shrink-0">
                            <i class="ti ti-calendar-check fs-20"></i>
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
                            <p class="mb-1 text-muted">Exames Abertos</p>
                            <div class="d-flex align-items-center gap-1">
                                <h3 class="fw-bold mb-0">{{ $total_exams ?? 0 }}</h3>
                                @if(isset($exams_growth))
                                <span class="badge fw-medium bg-{{ $exams_growth >= 0 ? 'success' : 'danger' }} flex-shrink-0">
                                    {{ $exams_growth >= 0 ? '+' : '' }}{{ $exams_growth }}%
                                </span>
                                @endif
                            </div>
                        </div>
                        <span class="avatar border border-warning text-warning rounded-2 flex-shrink-0">
                            <i class="ti ti-file-text fs-20"></i>
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
                            <p class="mb-1 text-muted">Residentes Activos</p>
                            <div class="d-flex align-items-center gap-1">
                                <h3 class="fw-bold mb-0">{{ $total_residents ?? 0 }}</h3>
                                @if(isset($residents_growth))
                                <span class="badge fw-medium bg-{{ $residents_growth >= 0 ? 'success' : 'danger' }} flex-shrink-0">
                                    {{ $residents_growth >= 0 ? '+' : '' }}{{ $residents_growth }}%
                                </span>
                                @endif
                            </div>
                        </div>
                        <span class="avatar border border-danger text-danger rounded-2 flex-shrink-0">
                            <i class="ti ti-users fs-20"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status Cards -->
    <div class="row row-cols-1 row-cols-xl-3 row-cols-md-1 mb-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="mb-1 text-muted">Pagamentos Recebidos Este Ano</p>
                            <div class="d-flex align-items-center gap-1">
                                <h3 class="fw-bold mb-0">{{ number_format($payments_received ?? 0, 2, ',', '.') }} MT</h3>
                                @if(isset($payments_growth))
                                <span class="badge fw-medium bg-{{ $payments_growth >= 0 ? 'success' : 'danger' }} flex-shrink-0">
                                    {{ $payments_growth >= 0 ? '+' : '' }}{{ $payments_growth }}%
                                </span>
                                @endif
                            </div>
                            <p class="mb-0 fs-13 text-muted mt-1">Este mês</p>
                        </div>
                        <span class="avatar border border-success text-success rounded-2 flex-shrink-0">
                            <i class="ti ti-check-circle fs-20"></i>
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
                            <p class="mb-1 text-muted">Pagamentos Pendentes</p>
                            <h3 class="fw-bold mb-0">{{ number_format($payments_pending ?? 0, 2, ',', '.') }} MT</h3>
                        </div>
                        <span class="avatar border border-warning text-warning rounded-2 flex-shrink-0">
                            <i class="ti ti-clock fs-20"></i>
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
                            <p class="mb-1 text-muted">Pagamentos Vencidos</p>
                            <h3 class="fw-bold mb-0">{{ number_format($payments_overdue ?? 0, 2, ',', '.') }} MT</h3>
                        </div>
                        <span class="avatar border border-danger text-danger rounded-2 flex-shrink-0">
                            <i class="ti ti-alert-circle fs-20"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0">Visão Geral de Inscrições</h5>
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-sm btn-outline-primary">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body pb-0">
                    <div class="chart-set" id="registrationChart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Membros por Especialidade</h5>
                </div>
                <div class="card-body pb-0">
                    <div class="chart-set" id="membersBySpecialityChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Registrations & Top Exams -->
    <div class="row mb-4">
        <div class="col-xl-8 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0">Inscrições Recentes</h5>
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-sm btn-outline-primary">Ver Todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
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
                                    <td>#{{ $registration->registration_number }}</td>
                                    <td>{{ ($registration->person->civility ?? 'Dr.') . ' ' . $registration->person->full_name }}</td>
                                    <td>{{ $registration->created_at->format('d M Y') }}</td>
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
                                                    <li><a class="dropdown-item" href="{{ route('admin.registrations.renew', $registration->id) }}">
                                                        <i class="ti ti-refresh me-2"></i>Renovar
                                                    </a></li>
                                                    @endif
                                                    @if($registration->isExpired())
                                                    <li><a class="dropdown-item" href="{{ route('admin.registrations.reinstate', $registration->id) }}">
                                                        <i class="ti ti-rotate-clockwise me-2"></i>Reinscrever
                                                    </a></li>
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
                </div>
            </div>
        </div>
        <div class="col-xl-4 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0">Especialidades Populares</h5>
                    <a href="{{ route('admin.medical-specialities.index') }}" class="btn btn-sm btn-outline-primary">Ver Todas</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($popular_specialties ?? [] as $specialty)
                        <li class="list-group-item px-0 d-flex align-items-center border-0">
                            <div class="avatar bg-light rounded-2 p-2 me-3">
                                <i class="ti ti-stethoscope text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold">{{ $specialty->name }}</h6>
                                <small class="text-muted">Especialidade</small>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0 fw-bold">{{ $specialty->members_count ?? 0 }}</h6>
                                <small class="text-success">membros</small>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item px-0 text-center border-0">
                            Nenhuma especialidade encontrada
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Reports Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">
                        <i class="ti ti-chart-bar me-2"></i>Relatórios Rápidos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.reports.operational', ['type' => 'members']) }}" class="btn btn-outline-primary w-100 d-inline-flex align-items-center justify-content-center">
                                <i class="ti ti-users me-2"></i>Relatório de Membros
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.reports.financial', ['type' => 'payments']) }}" class="btn btn-outline-success w-100 d-inline-flex align-items-center justify-content-center">
                                <i class="ti ti-currency-dollar me-2"></i>Relatório Financeiro
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.reports.operational', ['type' => 'registrations']) }}" class="btn btn-outline-info w-100 d-inline-flex align-items-center justify-content-center">
                                <i class="ti ti-clipboard-list me-2"></i>Relatório de Inscrições
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center">
                                <i class="ti ti-chart-line me-2"></i>Ver Todos os Relatórios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize charts if data is available
        @if(isset($months) && isset($provisional_data) && isset($effective_data))
        // Registration Chart
        var registrationChartOptions = {
            series: [{
                name: 'Inscrições Provisórias',
                data: @json($provisional_data)
            }, {
                name: 'Inscrições Efetivas',
                data: @json($effective_data)
            }],
            chart: {
                type: 'line',
                height: 350
            },
            xaxis: {
                categories: @json($months)
            }
        };
        var registrationChart = new ApexCharts(document.querySelector("#registrationChart"), registrationChartOptions);
        registrationChart.render();
        @endif

        @if(isset($members_by_speciality))
        // Members by Speciality Chart
        var membersBySpecialityOptions = {
            series: @json(array_values($members_by_speciality)),
            chart: {
                type: 'donut',
                height: 350
            },
            labels: @json(array_keys($members_by_speciality))
        };
        var membersBySpecialityChart = new ApexCharts(document.querySelector("#membersBySpecialityChart"), membersBySpecialityOptions);
        membersBySpecialityChart.render();
        @endif
    </script>
    @endpush

</x-layouts.app>

