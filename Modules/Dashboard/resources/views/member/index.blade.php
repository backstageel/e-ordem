<?php $page = 'member-dashboard'; ?>
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
                        <h4 class="fw-bold mb-0">Meu Dashboard</h4>
                        <p class="text-muted mb-0">Bem-vindo, {{ Auth::user()->name }}! Aqui está o resumo das suas actividades.</p>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="{{ route('member.registrations.create') }}" class="btn btn-primary d-inline-flex align-items-center"><i class="ti ti-plus me-1"></i>Nova Inscrição</a>
                        <a href="{{ route('member.registrations.index') }}" class="btn btn-outline-white bg-white d-inline-flex align-items-center"><i class="ti ti-calendar-time me-1"></i>Ver Minhas Inscrições</a>
                    </div>
                </div>
                <!-- End Page Header -->

                @if(!$member)
                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle me-2"></i>
                    <strong>Atenção!</strong> Não foi possível carregar os seus dados. Por favor, contacte o suporte.
                </div>
                @else

                <!-- start row -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="position-relative border card rounded-2 shadow-sm">
                            <img src="{{ asset('build/img/bg/bg-01.svg') }}" alt="img" class="position-absolute start-0 top-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2 justify-content-between">
                                    <span class="avatar bg-primary rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-user-plus fs-20"></i></span>
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
                                        <p class="mb-1">Minhas Inscrições</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($total_my_registrations ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-member-reg" class="chart-set"></div>
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
                                    <span class="avatar bg-warning rounded-circle d-flex align-items-center justify-content-center"><i class="ti ti-file-text fs-20"></i></span>
                                    <div class="text-end">
                                        @if(isset($documents_growth))
                                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-{{ $documents_growth >= 0 ? 'success' : 'danger' }}">
                                            {{ $documents_growth >= 0 ? '+' : '' }}{{ number_format($documents_growth, 1) }}%
                                        </span>
                                        @endif
                                        <p class="fs-13 mb-0">nos últimos 7 dias</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-1">Meus Documentos</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($total_my_documents ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-member-doc" class="chart-set"></div>
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
                                        <p class="mb-1">Meus Exames</p>
                                        <h3 class="fw-bold mb-0">{{ number_format($total_my_exams ?? 0) }}</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-member-exam" class="chart-set"></div>
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
                                        <p class="fs-13 mb-0">nos últimos 7 dias</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between overflow-hidden">
                                    <div>
                                        <p class="mb-1">Pagamentos</p>
                                        <h3 class="fw-bold mb-0 text-truncate">{{ number_format($payments_received_last_7_days ?? 0, 2, ',', '.') }} MT</h3>
                                    </div>
                                    <div>
                                        <div id="s-col-member-pay" class="chart-set"></div>
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
                                <h5 class="fw-bold mb-0">Estatísticas das Minhas Inscrições</h5>
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
                                    <div class="col-md-4 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body text-truncate"><i class="ti ti-point-filled me-1 text-primary"></i>Todas as Inscrições</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_my_registrations ?? 0) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-warning"></i>Pendentes</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_pending_registrations ?? 0) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="bg-light border p-2 text-center rounded-2">
                                            <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-success"></i>Aprovadas</p>
                                            <h5 class="fw-bold mb-0">{{ number_format($total_approved_registrations ?? 0) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="chart-set" id="memberRegistrationStatusChart"></div>
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
                                <a href="{{ route('member.registrations.index') }}" class="btn fw-normal btn-outline-white">Ver Todas</a>
                            </div>
                            <div class="card-body">
                                @forelse($recent_registrations ?? [] as $registration)
                                <div class="mb-3 bg-light p-3 rounded-2 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="fs-14 fw-semibold mb-1">{{ $registration->registrationType->name ?? 'N/A' }}</h6>
                                        <p class="mb-0 text-body text-truncate"><i class="ti ti-calendar-time me-1 text-body"></i> {{ $registration->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-status-badge :status="$registration->status" :size="'sm'" />
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-3">
                                    <p class="text-muted mb-0">Nenhuma inscrição encontrada.</p>
                                </div>
                                @endforelse
                                <a href="{{ route('member.registrations.index') }}" class="btn btn-light w-100">Ver Todas as Inscrições</a>
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
                                <h5 class="fw-bold mb-0">Status de Pagamentos</h5>
                                <a href="{{ route('member.payments.index') }}" class="btn fw-normal btn-outline-white">Ver Todos</a>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-4">
                                    <div class="col d-flex border-end">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Recebidos</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_payments_completed ?? 0) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col d-flex border-end">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Pendentes</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_payments_pending_count ?? 0) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col d-flex">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Vencidos</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_payments_overdue_count ?? 0) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="overflow-auto">
                                    @forelse($recent_payments ?? [] as $payment)
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center flex-shrink-0">
                                            <div class="avatar me-2 flex-shrink-0 bg-light rounded-circle p-2 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-credit-card text-{{ $payment->status->isCompleted() ? 'success' : 'warning' }} fs-20"></i>
                                            </div>
                                            <div class="ms-2 flex-shrink-0">
                                                <div>
                                                    <h6 class="fw-semibold fs-14 text-truncate mb-1"><a href="{{ route('member.payments.show', $payment->id) }}">{{ $payment->paymentType->name ?? 'Pagamento' }}</a></h6>
                                                    <p class="fs-13 mb-0 text-truncate">{{ $payment->created_at->format('d M Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <span class="badge bg-{{ $payment->status->isCompleted() ? 'success' : 'warning' }} py-1">{{ number_format($payment->amount ?? 0, 2, ',', '.') }} MT</span>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-3">
                                        <p class="text-muted mb-0">Nenhum pagamento recente encontrado.</p>
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
                                <h5 class="fw-bold mb-0">Meus Documentos</h5>
                                <a href="{{ route('member.documents.index') }}" class="btn fw-normal btn-outline-white">Ver Todos</a>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-4">
                                    <div class="col d-flex border-end">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Total</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_my_documents ?? 0) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col d-flex">
                                        <div class="text-center flex-fill">
                                            <p class="mb-1">Pendentes</p>
                                            <h3 class="fw-bold mb-0">{{ number_format($total_pending_documents ?? 0) }}</h3>
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
                                                    <h6 class="fw-semibold fs-14 text-truncate mb-1"><a href="{{ route('member.documents.show', $document->id) }}">{{ $document->documentType->name ?? 'Documento' }}</a></h6>
                                                    <p class="fs-13 mb-0 text-truncate">{{ $document->created_at->format('d M Y') }}</p>
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
                </div>
                <!-- end row -->

                @endif

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
                // Chart for Registrations card (s-col-member-reg) - Bar chart
                if (document.querySelector("#s-col-member-reg")) {
                    var registrationsChart = new ApexCharts(document.querySelector("#s-col-member-reg"), {
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
                                { x: 'A', y: {{ $registrations_chart_data['last_7_days'] ?? 40 }}, fillColor: '#2d5016' },
                                { x: 'B', y: {{ $registrations_chart_data['current_day'] ?? 15 }}, fillColor: '#2d5016' },
                                { x: 'C', y: {{ ($registrations_chart_data['last_7_days'] ?? 40) + 20 }}, fillColor: '#2d5016' },
                                { x: 'D', y: {{ ($registrations_chart_data['current_day'] ?? 15) + 10 }}, fillColor: '#2d5016' },
                                { x: 'E', y: {{ ($registrations_chart_data['last_7_days'] ?? 40) + 50 }}, fillColor: '#4a7c2a' },
                                { x: 'F', y: {{ ($registrations_chart_data['current_day'] ?? 15) + 5 }}, fillColor: '#2d5016' },
                                { x: 'G', y: {{ ($registrations_chart_data['last_7_days'] ?? 40) + 30 }}, fillColor: '#2d5016' }
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

                // Chart for Documents card (s-col-member-doc) - Area chart
                if (document.querySelector("#s-col-member-doc")) {
                    var documentsChart = new ApexCharts(document.querySelector("#s-col-member-doc"), {
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
                                {{ $documents_chart_data['last_7_days'] ?? 22 }},
                                {{ $documents_chart_data['current_day'] ?? 35 }},
                                {{ ($documents_chart_data['last_7_days'] ?? 22) + 8 }},
                                {{ ($documents_chart_data['current_day'] ?? 35) + 5 }},
                                {{ ($documents_chart_data['last_7_days'] ?? 22) - 2 }},
                                {{ ($documents_chart_data['current_day'] ?? 35) + 10 }},
                                {{ ($documents_chart_data['last_7_days'] ?? 22) + 5 }}
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
                    documentsChart.render();
                }

                // Chart for Exams card (s-col-member-exam) - Bar chart
                if (document.querySelector("#s-col-member-exam")) {
                    var examsChart = new ApexCharts(document.querySelector("#s-col-member-exam"), {
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

                // Chart for Payments card (s-col-member-pay) - Area chart
                if (document.querySelector("#s-col-member-pay")) {
                    var paymentsChart = new ApexCharts(document.querySelector("#s-col-member-pay"), {
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
                    paymentsChart.render();
                }

                // Member Registration Status Chart (Line Chart) - Statistics by status
                var memberRegistrationStatusChartElement = document.querySelector("#memberRegistrationStatusChart");
                if (memberRegistrationStatusChartElement) {
                    @php
                        $approvedData = $approved_data ?? [];
                        $pendingData = $pending_data ?? [];
                        $rejectedData = $rejected_data ?? [];
                        $chartMonths = $months ?? ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
                    @endphp

                    var memberRegistrationStatusChartOptions = {
                        series: [{
                            name: 'Aprovadas',
                            data: @json($approvedData)
                        }, {
                            name: 'Pendentes',
                            data: @json($pendingData)
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
                        colors: ['#198754', '#ffc107', '#dc3545'],
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center'
                        },
                        markers: {
                            size: 5,
                            colors: ['#fff'],
                            strokeColors: ['#198754', '#ffc107', '#dc3545'],
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
                    var memberRegistrationStatusChart = new ApexCharts(memberRegistrationStatusChartElement, memberRegistrationStatusChartOptions);
                    memberRegistrationStatusChart.render();
                }
            }

            // Initialize all charts
            initCharts();
        });
    </script>
    @endpush
</x-layouts.app>
