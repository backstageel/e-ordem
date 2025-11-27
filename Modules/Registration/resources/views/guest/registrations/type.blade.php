<x-layouts.guest-registration>
    <x-slot name="header">
        Escolha o Tipo de Inscrição
    </x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header Section -->
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bold text-primary mb-3">Processo de Inscrição na OMM</h2>
                    <p class="lead text-muted">Selecione o tipo de inscrição adequado ao seu perfil profissional</p>
                </div>

                <!-- Registration Types Cards -->
                <div class="row g-4">
                    <!-- Efective Registration Card -->
                    <div class="col-lg-6">
                        <div class="card h-100 border-0 shadow-lg registration-card">
                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <div class="registration-icon bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-check fa-2x"></i>
                                    </div>
                                    <h4 class="card-title text-primary fw-bold">Inscrição Efectiva</h4>
                                    <p class="text-muted">Para médicos nacionais</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Destinado a:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Médicos moçambicanos</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Formados em escolas superiores moçambicanas</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Formados em escolas superiores estrangeiras</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Médicos de Clínica Geral</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Médicos Dentistas</li>
                                    </ul>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Benefícios:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Membro Efectivo da OMM</li>
                                        <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Exercício permanente</li>
                                        <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Cartão profissional</li>
                                        <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Direitos plenos</li>
                                    </ul>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('guest.registrations.type-selection') }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-arrow-right me-2"></i>Iniciar Inscrição Efectiva
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Provisional Registration Card -->
                    <div class="col-lg-6">
                        <div class="card h-100 border-0 shadow-lg registration-card">
                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <div class="registration-icon bg-info text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-clock fa-2x"></i>
                                    </div>
                                    <h4 class="card-title text-info fw-bold">Inscrição Provisória</h4>
                                    <p class="text-muted">Para médicos estrangeiros</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Destinado a:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Médicos estrangeiros</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Formação médica especializada</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Investigação científica</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Missões humanitárias</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Cooperação intergovernamental</li>
                                    </ul>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Modalidades:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-clock text-info me-2"></i>3-24 meses (conforme modalidade)</li>
                                        <li class="mb-2"><i class="fas fa-clock text-info me-2"></i>Renovável (conforme regulamento)</li>
                                        <li class="mb-2"><i class="fas fa-clock text-info me-2"></i>Membro Associado</li>
                                        <li class="mb-2"><i class="fas fa-clock text-info me-2"></i>Actividade limitada</li>
                                    </ul>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('guest.registrations.type-selection') }}" class="btn btn-info btn-lg">
                                        <i class="fas fa-arrow-right me-2"></i>Iniciar Inscrição Provisória
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">Precisa de ajuda?</h5>
                                        <p class="text-muted mb-0">Consulte o regulamento de inscrição ou entre em contacto connosco para esclarecimentos sobre o processo.</p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <a href="#" class="btn btn-outline-primary me-2">
                                            <i class="fas fa-book me-2"></i>Regulamento
                                        </a>
                                        <a href="#" class="btn btn-outline-secondary">
                                            <i class="fas fa-phone me-2"></i>Contacto
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to Login -->
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-arrow-left me-2"></i>Voltar ao Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .registration-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .registration-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.175) !important;
        }

        .registration-icon {
            transition: transform 0.3s ease;
        }

        .registration-card:hover .registration-icon {
            transform: scale(1.1);
        }
    </style>
</x-layouts.guest-registration>
