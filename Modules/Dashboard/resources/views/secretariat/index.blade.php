<?php $page = 'secretariat-dashboard'; ?>
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
                        <h4 class="fw-bold mb-0">Dashboard Secretariado</h4>
                        <p class="text-muted mb-0">Bem-vindo, {{ Auth::user()->name }}! Aqui está o resumo das inscrições e documentos pendentes.</p>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="{{ route('admin.registrations.wizard') }}" class="btn btn-primary d-inline-flex align-items-center"><i class="ti ti-plus me-1"></i>Nova Inscrição</a>
                        <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-white bg-white d-inline-flex align-items-center"><i class="ti ti-calendar-time me-1"></i>Ver Inscrições</a>
                    </div>
                </div>
                <!-- End Page Header -->

                <!-- start row -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-01.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2 justify-content-between">
                                    <span class="avatar bg-warning rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-clock-hour-4 fs-20"></i></span>
                                    <div class="text-end">
                                        @if(isset($pending_registrations_growth))
                                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-{{ $pending_registrations_growth >= 0 ? 'success' : 'danger' }}">
                                            {{ $pending_registrations_growth >= 0 ? '+' : '' }}{{ number_format($pending_registrations_growth, 1) }}%
                                        </span>
                                        @endif
                                        <p class="fs-13 mb-0">nos últimos 7 dias</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-1">Inscrições Pendentes</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($total_pending_registrations ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-secretariat-reg" class="chart-set"></div>
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
                                    <span class="avatar bg-danger rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-file-text fs-20"></i></span>
                                    <div class="text-end">
                                        @if(isset($pending_documents_growth))
                                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-{{ $pending_documents_growth >= 0 ? 'success' : 'danger' }}">
                                            {{ $pending_documents_growth >= 0 ? '+' : '' }}{{ number_format($pending_documents_growth, 1) }}%
                                        </span>
                                        @endif
                                        <p class="fs-13 mb-0">nos últimos 7 dias</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-1">Documentos Pendentes</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($total_pending_documents ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-secretariat-doc" class="chart-set"></div>
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
                                    <span class="avatar bg-info rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-search fs-20"></i></span>
                                    <div class="text-end">
                                        @if(isset($under_review_growth))
                                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-{{ $under_review_growth >= 0 ? 'success' : 'danger' }}">
                                            {{ $under_review_growth >= 0 ? '+' : '' }}{{ number_format($under_review_growth, 1) }}%
                                        </span>
                                        @endif
                                        <p class="fs-13 mb-0">nos últimos 7 dias</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-1">Em Análise</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($total_under_review_registrations ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-secretariat-review" class="chart-set"></div>
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
                                    <span class="avatar bg-success rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-check-circle fs-20"></i></span>
                                    <div class="text-end">
                                        <p class="fs-13 mb-0">últimos 7 dias</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between overflow-hidden">
                                    <div>
                                        <p class="mb-1">Aprovadas</p>
                                        <h3 class="fw-bold mb-0 text-truncate">{{ number_format($total_approved_last_7_days ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-secretariat-approved" class="chart-set"></div>
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
                                            <p class="mb-1 text-body text-truncate"><i class="ti ti-point-filled me-1 text-warning"></i>Pendentes</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_pending_registrations ?? 0) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-info"></i>Em Análise</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_under_review_registrations ?? 0) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-success"></i>Aprovadas</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_approved_last_7_days ?? 0) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-danger"></i>Rejeitadas</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_rejected_documents ?? 0) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="chart-set" id="secretariatRegistrationStatusChart"></div>
                            </div>
                        </div>
                        <!-- card end -->

                    </div>
                        <!-- col end -->

                        <!-- col start -->
                    <div class="col-xl-4">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0 text-truncate">Inscrições Pendentes</h5>
                                <a href="{{ route('admin.registrations.index') }}" class="btn fw-normal btn-outline-white">Ver Todas</a>
                            </div>
                            <div class="card-body">
                                @forelse($recent_registrations ?? [] as $registration)
                                <div class="mb-3 bg-light p-3 rounded-2 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="fs-14 fw-semibold mb-1">{{ $registration->registrationType->name ?? 'N/A' }}</h6>
                                        <p class="mb-0 text-body text-truncate"><i class="ti ti-calendar-time me-1 text-body"></i> {{ $registration->created_at->format('d M Y, H:i') }}</p>
                                        <p class="mb-0 fs-12 text-muted mt-1">
                                            {{ ($registration->person->civility ?? 'Dr.') . ' ' . ($registration->person->full_name ?? 'N/A') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-status-badge :status="$registration->status" :size="'sm'" />
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-3">
                                    <p class="text-muted mb-0">Nenhuma inscrição pendente encontrada.</p>
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
                    <div class="col-xl-6 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Status de Documentos</h5>
                                <a href="{{ route('admin.registrations.index') }}" class="btn fw-normal btn-outline-white">Ver Todos</a>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-4">
                                    <div class="col d-flex border-end">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Pendentes</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_pending_documents ?? 0) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col d-flex border-end">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Em Análise</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_under_review_documents ?? 0) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col d-flex">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Validados</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_validated_documents ?? 0) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="overflow-auto">
                                    @forelse($recent_documents ?? [] as $document)
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center flex-shrink-0">
                                            <div class="avatar me-2 flex-shrink-0 bg-light rounded-circle p-2 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-file-text text-primary fs-20"></i>
                                            </div>
                                            <div class="ms-2 flex-shrink-0">
                                                <div>
                                                    <h6 class="fw-semibold fs-14 text-truncate mb-1"><a href="{{ route('admin.registrations.index') }}">{{ $document->documentType->name ?? 'Documento' }}</a></h6>
                                                    <p class="fs-13 mb-0 text-truncate">{{ $document->member->full_name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            @if($document->status instanceof \App\Enums\DocumentStatus)
                                                <x-status-badge :status="$document->status" :size="'sm'" />
                                            @else
                                                <span class="badge bg-secondary">{{ $document->status }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-3">
                                        <p class="text-muted mb-0">Nenhum documento recente encontrado.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->

                    <!-- col start -->
                    <div class="col-xl-6 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">Documentos Recentes</h5>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                        Todos <i class="ti ti-chevron-down ms-1"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Pendentes</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Em Análise</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Validados</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-4">
                                    <div class="col d-flex border-end">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Total</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_documents ?? 0) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col d-flex border-end">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Rejeitados</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_rejected_documents ?? 0) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col d-flex">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Correção</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_requires_correction ?? 0) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="overflow-auto">
                                    @forelse(($recent_documents ?? [])->take(5) as $document)
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2 flex-shrink-0 bg-light rounded-circle p-2 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-file-text text-{{ $document->status->value === 'validated' ? 'success' : ($document->status->value === 'pending' ? 'warning' : 'danger') }} fs-20"></i>
                                            </div>
                                            <div>
                                                <h6 class="fs-14 mb-1 text-truncate fw-semibold">{{ $document->documentType->name ?? 'Documento' }}</h6>
                                                <p class="mb-0 fs-13 text-truncate">
                                                    <a href="javascript:void(0);" class="link-primary">#{{ $document->id }}</a> - {{ $document->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="badge fw-medium bg-{{ $document->status->value === 'validated' ? 'success' : ($document->status->value === 'pending' ? 'warning' : 'danger') }} flex-shrink-0">
                                            {{ ucfirst($document->status->value ?? 'N/A') }}
                                        </span>
                                    </div>
                                    @empty
                                    <div class="text-center py-4">
                                        <p class="text-muted mb-0">Nenhum documento encontrado</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                </div>
                <!-- end row -->

            </div>
            <!-- End Content -->

            <x-footer />

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
                // Chart for Pending Registrations card (s-col-secretariat-reg) - Bar chart
                if (document.querySelector("#s-col-secretariat-reg")) {
                    var pendingRegChart = new ApexCharts(document.querySelector("#s-col-secretariat-reg"), {
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
                                { x: 'A', y: {{ $pending_registrations_chart_data['last_7_days'] ?? 40 }}, fillColor: '#ffc107' },
                                { x: 'B', y: {{ $pending_registrations_chart_data['current_day'] ?? 15 }}, fillColor: '#ffc107' },
                                { x: 'C', y: {{ ($pending_registrations_chart_data['last_7_days'] ?? 40) + 20 }}, fillColor: '#ffc107' },
                                { x: 'D', y: {{ ($pending_registrations_chart_data['current_day'] ?? 15) + 10 }}, fillColor: '#ffc107' },
                                { x: 'E', y: {{ ($pending_registrations_chart_data['last_7_days'] ?? 40) + 50 }}, fillColor: '#ffc107' },
                                { x: 'F', y: {{ ($pending_registrations_chart_data['current_day'] ?? 15) + 5 }}, fillColor: '#ffc107' },
                                { x: 'G', y: {{ ($pending_registrations_chart_data['last_7_days'] ?? 40) + 30 }}, fillColor: '#ffc107' }
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
                    pendingRegChart.render();
                }

                // Chart for Pending Documents card (s-col-secretariat-doc) - Area chart
                if (document.querySelector("#s-col-secretariat-doc")) {
                    var pendingDocChart = new ApexCharts(document.querySelector("#s-col-secretariat-doc"), {
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
                            colors: ['#dc3545']
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.4,
                                opacityTo: 0,
                                stops: [0, 90, 100],
                                colorStops: [
                                    { offset: 0, color: "#dc3545", opacity: 0.4 },
                                    { offset: 100, color: "#ffffff", opacity: 0.1 }
                                ]
                            }
                        },
                        dataLabels: { enabled: false },
                        series: [{
                            name: 'Data',
                            data: [
                                {{ $pending_documents_chart_data['last_7_days'] ?? 22 }},
                                {{ $pending_documents_chart_data['current_day'] ?? 35 }},
                                {{ ($pending_documents_chart_data['last_7_days'] ?? 22) + 8 }},
                                {{ ($pending_documents_chart_data['current_day'] ?? 35) + 5 }},
                                {{ ($pending_documents_chart_data['last_7_days'] ?? 22) - 2 }},
                                {{ ($pending_documents_chart_data['current_day'] ?? 35) + 10 }},
                                {{ ($pending_documents_chart_data['last_7_days'] ?? 22) + 5 }}
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
                    pendingDocChart.render();
                }

                // Chart for Under Review card (s-col-secretariat-review) - Bar chart
                if (document.querySelector("#s-col-secretariat-review")) {
                    var underReviewChart = new ApexCharts(document.querySelector("#s-col-secretariat-review"), {
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
                                { x: 'A', y: {{ $under_review_chart_data['last_7_days'] ?? 80 }}, fillColor: '#0dcaf0' },
                                { x: 'B', y: {{ $under_review_chart_data['current_day'] ?? 35 }}, fillColor: '#0dcaf0' },
                                { x: 'C', y: {{ ($under_review_chart_data['last_7_days'] ?? 80) - 30 }}, fillColor: '#0dcaf0' },
                                { x: 'D', y: {{ ($under_review_chart_data['current_day'] ?? 35) + 10 }}, fillColor: '#0dcaf0' },
                                { x: 'E', y: {{ ($under_review_chart_data['last_7_days'] ?? 80) - 45 }}, fillColor: '#0dcaf0' },
                                { x: 'F', y: {{ ($under_review_chart_data['current_day'] ?? 35) + 25 }}, fillColor: '#0dcaf0' },
                                { x: 'G', y: {{ ($under_review_chart_data['last_7_days'] ?? 80) - 20 }}, fillColor: '#0dcaf0' }
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
                    underReviewChart.render();
                }

                // Chart for Approved card (s-col-secretariat-approved) - Area chart
                if (document.querySelector("#s-col-secretariat-approved")) {
                    var approvedChart = new ApexCharts(document.querySelector("#s-col-secretariat-approved"), {
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
                                {{ ($total_approved_last_7_days ?? 20) / 10 }},
                                {{ ($total_approved_last_7_days ?? 20) / 8 }},
                                {{ ($total_approved_last_7_days ?? 20) / 12 }},
                                {{ ($total_approved_last_7_days ?? 20) / 6 }},
                                {{ ($total_approved_last_7_days ?? 20) / 10 }},
                                {{ ($total_approved_last_7_days ?? 20) / 8 }},
                                {{ ($total_approved_last_7_days ?? 20) / 10 }}
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
                    approvedChart.render();
                }

                // Secretariat Registration Status Chart (Line Chart) - Statistics by status
                var secretariatRegistrationStatusChartElement = document.querySelector("#secretariatRegistrationStatusChart");
                if (secretariatRegistrationStatusChartElement) {
                    @php
                        $pendingData = $pending_data ?? [];
                        $underReviewData = $under_review_data ?? [];
                        $approvedData = $approved_data ?? [];
                        $rejectedData = $rejected_data ?? [];
                        $chartMonths = $months ?? ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
                    @endphp

                    var secretariatRegistrationStatusChartOptions = {
                        series: [{
                            name: 'Pendentes',
                            data: @json($pendingData)
                        }, {
                            name: 'Em Análise',
                            data: @json($underReviewData)
                        }, {
                            name: 'Aprovadas',
                            data: @json($approvedData)
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
                        colors: ['#ffc107', '#0dcaf0', '#198754', '#dc3545'],
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center'
                        },
                        markers: {
                            size: 5,
                            colors: ['#fff'],
                            strokeColors: ['#ffc107', '#0dcaf0', '#198754', '#dc3545'],
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
                    var secretariatRegistrationStatusChart = new ApexCharts(secretariatRegistrationStatusChartElement, secretariatRegistrationStatusChartOptions);
                    secretariatRegistrationStatusChart.render();
                }
            }

            // Initialize all charts
            initCharts();
        });
    </script>
    @endpush
</x-layouts.app>
