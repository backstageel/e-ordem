<?php $page = 'admin-registrations'; ?>
<x-layouts.app>
    <div class="page-wrapper">
        <div class="content pb-0">
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Processo de Inscrição</h4>
                    <p class="text-muted mb-0">Selecione o tipo de inscrição que corresponde ao perfil profissional do candidato</p>
                </div>
            </div>

            <div class="container-fluid py-4">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Registration Types Cards -->
                <div class="row g-4">
                    <!-- Certification Registration Card -->
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-md registration-card">
                            <div class="card-body card-spacing">
                                <div class="text-center mb-4">
                                    <div class="registration-icon bg-warning text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-graduation-cap fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <h3 class="card-title-lg text-warning mb-2">Pré-Inscrição para Certificação</h3>
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
                                        <li class="mb-2"><i class="fas fa-shield-check text-warning me-2" aria-hidden="true"></i>Documentos verificados automaticamente</li>
                                    </ul>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('admin.registrations.certification.wizard') }}" class="btn btn-warning btn-lg">
                                        <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Iniciar Pré-Inscrição para Certificação
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Provisional Registration Card -->
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-md registration-card">
                            <div class="card-body card-spacing">
                                <div class="text-center mb-4">
                                    <div class="registration-icon bg-info text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-clock fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <h3 class="card-title-lg text-info mb-2">Inscrição Provisória</h3>
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
                                        <li class="mb-2"><i class="fas fa-shield-check text-info me-2" aria-hidden="true"></i>Documentos verificados automaticamente</li>
                                    </ul>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('admin.registrations.provisional.wizard') }}" class="btn btn-info btn-lg">
                                        <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Iniciar Inscrição Provisória
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Effective Registration Card -->
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-md registration-card">
                            <div class="card-body card-spacing">
                                <div class="text-center mb-4">
                                    <div class="registration-icon bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-check fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <h3 class="card-title-lg text-success mb-2">Inscrição Efetiva</h3>
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
                                        <li class="mb-2"><i class="fas fa-shield-check text-success me-2" aria-hidden="true"></i>Documentos verificados automaticamente</li>
                                    </ul>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('admin.registrations.effective.wizard') }}" class="btn btn-success btn-lg">
                                        <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Iniciar Inscrição Efetiva
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .registration-card {
            transition: transform var(--transition-normal), box-shadow var(--transition-normal);
        }

        .registration-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .registration-icon {
            transition: transform var(--transition-normal);
        }

        .registration-card:hover .registration-icon {
            transform: scale(1.1);
        }
    </style>
        </div>
    </div>
</x-layouts.app>

