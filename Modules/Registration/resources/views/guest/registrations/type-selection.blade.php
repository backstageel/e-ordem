<?php $page = 'guest-registration'; ?>
<x-layouts.guest>
    <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3">
        <div class="flex-grow-1">
            <h4 class="fw-bold mb-0">Processo de Inscrição</h4>
            <p class="text-muted mb-0">Selecione o tipo de inscrição que corresponde ao seu perfil profissional</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <!-- Registration Types Cards -->
            <div class="row g-4">
                <!-- Certification Registration Card -->
                <div class="col-lg-4">
                    <div class="card h-100 border registration-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="registration-icon bg-warning text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="ti ti-school fa-2x" aria-hidden="true"></i>
                                </div>
                                <h3 class="fw-bold mb-2 text-warning">Pré-Inscrição para Certificação</h3>
                                <p class="text-sm text-muted">Para nacionais sem cadastro na ordem</p>
                            </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Público-Alvo:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Nacionais moçambicanos</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Formados em Moçambique</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Formados no estrangeiro</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Estrangeiros formados em Moçambique</li>
                                    </ul>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Processo:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-clipboard-list text-warning me-2" aria-hidden="true"></i>Workflow de certificação com 9 etapas</li>
                                        <li class="mb-2"><i class="fas fa-file-alt text-warning me-2" aria-hidden="true"></i>7-13 documentos necessários</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-warning me-2" aria-hidden="true"></i>Habilita inscrição efetiva após aprovação</li>
                                    </ul>
                                </div>

                            <div class="d-grid">
                                <a href="{{ route('guest.registrations.certification.wizard') }}" class="btn btn-warning">
                                    <i class="ti ti-arrow-right me-2"></i>Iniciar Pré-Inscrição para Certificação
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Provisional Registration Card -->
                <div class="col-lg-4">
                    <div class="card h-100 border registration-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="registration-icon bg-info text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="ti ti-clock-hour-4 fa-2x" aria-hidden="true"></i>
                                </div>
                                <h3 class="fw-bold mb-2 text-info">Inscrição Provisória</h3>
                                <p class="text-sm text-muted">Para médicos estrangeiros</p>
                            </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Público-Alvo:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Exclusivamente médicos estrangeiros</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Formação médica especializada</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Investigação científica</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Missões humanitárias</li>
                                    </ul>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Modalidades:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-clock text-info me-2" aria-hidden="true"></i>3-24 meses (conforme subtipo)</li>
                                        <li class="mb-2"><i class="fas fa-redo text-info me-2" aria-hidden="true"></i>Renovável (conforme regulamento)</li>
                                        <li class="mb-2"><i class="fas fa-id-card text-info me-2" aria-hidden="true"></i>12 subtipos disponíveis</li>
                                    </ul>
                                </div>

                            <div class="d-grid">
                                <a href="{{ route('guest.registrations.provisional.wizard') }}" class="btn btn-info">
                                    <i class="ti ti-arrow-right me-2"></i>Iniciar Inscrição Provisória
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Effective Registration Card -->
                <div class="col-lg-4">
                    <div class="card h-100 border registration-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="registration-icon bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="ti ti-user-check fa-2x" aria-hidden="true"></i>
                                </div>
                                <h3 class="fw-bold mb-2 text-success">Inscrição Efetiva</h3>
                                <p class="text-sm text-muted">Para nacionais com exame aprovado</p>
                            </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Público-Alvo:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Exclusivamente nacionais moçambicanos</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Exame de certificação aprovado</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Número de inscrição na base de dados</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2" aria-hidden="true"></i>Nota de exame disponível</li>
                                    </ul>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Benefícios:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-star text-success me-2" aria-hidden="true"></i>Membro Efetivo da OrMM</li>
                                        <li class="mb-2"><i class="fas fa-star text-success me-2" aria-hidden="true"></i>Exercício permanente</li>
                                        <li class="mb-2"><i class="fas fa-star text-success me-2" aria-hidden="true"></i>Cartão profissional</li>
                                        <li class="mb-2"><i class="fas fa-star text-success me-2" aria-hidden="true"></i>Direitos completos</li>
                                    </ul>
                                </div>

                            <div class="d-grid">
                                <a href="{{ route('guest.registrations.effective.wizard') }}" class="btn btn-success">
                                    <i class="ti ti-arrow-right me-2"></i>Iniciar Inscrição Efetiva
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Section -->
            <div class="card border mt-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-2">Precisa de Ajuda?</h5>
                            <p class="text-muted mb-0">Consulte o regulamento de inscrições ou contacte-nos para esclarecimentos sobre o processo.</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="#" class="btn btn-outline-primary me-2">
                                <i class="ti ti-book me-2"></i>Regulamento
                            </a>
                            <a href="#" class="btn btn-outline-secondary">
                                <i class="ti ti-phone me-2"></i>Contacto
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Login -->
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                    <i class="ti ti-arrow-left me-2"></i>Voltar ao Login
                </a>
            </div>
        </div>
    </div>

    <style>
        .registration-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .registration-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .registration-icon {
            transition: transform 0.3s ease;
        }

        .registration-card:hover .registration-icon {
            transform: scale(1.1);
        }
    </style>
</x-layouts.guest>

