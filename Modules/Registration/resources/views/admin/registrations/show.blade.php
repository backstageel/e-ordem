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
                    <h4 class="fw-bold mb-0">Inscrição #{{ $registration->registration_number }}</h4>
                    <p class="text-muted mb-0">Informações completas sobre a inscrição médica.</p>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary d-inline-flex align-items-center"><i class="ti ti-arrow-left me-1"></i>Voltar</a>
                    <a href="{{ route('admin.registrations.export-pdf', $registration) }}" class="btn btn-success d-inline-flex align-items-center"><i class="ti ti-download me-1"></i>Exportar PDF</a>
                    @if(!$registration->isApproved() && !$registration->isRejected())
                        <a href="{{ route('admin.registrations.edit-wizard', $registration) }}" class="btn btn-primary d-inline-flex align-items-center"><i class="ti ti-edit me-1"></i>Editar</a>
                        @if(!$registration->isValidated())
                            <form class="d-inline" method="POST" action="{{ route('admin.registrations.validate', $registration) }}">
                                @csrf
                                <button type="submit" class="btn btn-info d-inline-flex align-items-center"><i class="ti ti-clipboard-check me-1"></i>Validar Inscrição</button>
                            </form>
                        @endif
                        @if($registration->isValidated())
                            <form class="d-inline" method="POST" action="{{ route('admin.registrations.approve', $registration) }}">
                                @csrf
                                <button type="submit" class="btn btn-success d-inline-flex align-items-center"><i class="ti ti-check me-1"></i>Aprovar Inscrição</button>
                            </form>
                        @endif
                        <button class="btn btn-danger d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#rejectRegistrationModal"><i class="ti ti-x me-1"></i>Rejeitar Inscrição</button>
                    @endif
                </div>
            </div>
            <!-- End Page Header -->

            <!-- Status Cards -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="border card rounded-2 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <x-status-badge :status="$registration->status" :size="'lg'" />
                                    <div>
                                        <h6 class="mb-0 fw-semibold">Status da Inscrição</h6>
                                        @if($registration->approval_date)
                                            <small class="text-muted">Aprovada em {{ $registration->approval_date->format('d M Y') }}</small>
                                        @elseif($registration->isRejected())
                                            <small class="text-muted">Rejeitada</small>
                                        @else
                                            <small class="text-muted">Pendente desde {{ $registration->submission_date->format('d M Y') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border card rounded-2 shadow-sm">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <i class="ti ti-calendar text-primary fs-20"></i>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Data de Submissão</h6>
                                    <small class="text-muted">{{ $registration->submission_date->format('d M Y, H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Details -->
            <div class="row">
                <!-- Left Column - Personal, Academic, Professional Info -->
                <div class="col-lg-8">
                    <!-- Personal Information -->
                    <div class="border card rounded-2 shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0">Informações Pessoais</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-4">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($registration->person->full_name) }}&background=0D8ABC&color=fff&size=120" class="rounded-circle mb-3" alt="Profile">
                                    <h5 class="fw-bold mb-1">{{ $registration->person->full_name }}</h5>
                                    <p class="text-muted mb-0">{{ $registration->specialty ?? 'Sem especialidade' }}</p>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted mb-1">Nome Completo</label>
                                            <p class="fw-bold mb-0">{{ $registration->person->full_name }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted mb-1">Data de Nascimento</label>
                                            <p class="fw-bold mb-0">{{ $registration->person->birth_date ? $registration->person->birth_date->format('d F Y') : 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted mb-1">Número de Identificação</label>
                                            <p class="fw-bold mb-0">{{ $registration->person->identity_document_number }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted mb-1">Nacionalidade</label>
                                            <p class="fw-bold mb-0">{{ $registration->person->nationality_id ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted mb-1">Email</label>
                                            <p class="fw-bold mb-0">{{ $registration->person->email }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted mb-1">Telefone</label>
                                            <p class="fw-bold mb-0">{{ $registration->person->phone }}</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label text-muted mb-1">Endereço</label>
                                            <p class="fw-bold mb-0">{{ $registration->person->living_address ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="border card rounded-2 shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0">Informações Académicas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted mb-1">Instituição de Formação</label>
                                    <p class="fw-bold mb-0">{{ optional($registration->person->currentAcademicQualification)->institution_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted mb-1">Ano de Graduação</label>
                                    <p class="fw-bold mb-0">{{ optional(optional($registration->person->currentAcademicQualification)->completion_date)->format('d/m/Y') ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted mb-1">Especialidade</label>
                                    <p class="fw-bold mb-0">{{ $registration->specialty ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted mb-1">Ano de Especialização</label>
                                    <p class="fw-bold mb-0">N/A</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted mb-1">Outras Qualificações</label>
                                    <p class="fw-bold mb-0">N/A</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="border card rounded-2 shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0">Informações Profissionais</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted mb-1">Local de Trabalho Atual</label>
                                    <p class="fw-bold mb-0">{{ optional($registration->person->currentWorkExperience)->institution_name ?? ($registration->workplace ?? 'N/A') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted mb-1">Cargo</label>
                                    <p class="fw-bold mb-0">{{ $registration->professional_category ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted mb-1">Anos de Experiência</label>
                                    <p class="fw-bold mb-0">N/A</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted mb-1">Número de Licença Anterior</label>
                                    <p class="fw-bold mb-0">N/A</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Registration Details, Payment, Documents, Actions -->
                <div class="col-lg-4">
                    <!-- Registration Details -->
                    <div class="border card rounded-2 shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0">Detalhes da Inscrição</h5>
                            <i class="ti ti-settings text-muted"></i>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">ID da Inscrição</label>
                                <p class="fw-bold mb-0">{{ $registration->registration_number }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Tipo de Inscrição</label>
                                <div>
                                    <span class="badge bg-info">{{ $registration->registrationType->name }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Data de Submissão</label>
                                <p class="fw-bold mb-0">{{ $registration->submission_date->format('d M Y, H:i') }}</p>
                            </div>
                            @if($registration->approval_date)
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Data de Aprovação</label>
                                <p class="fw-bold mb-0">{{ $registration->approval_date->format('d M Y, H:i') }}</p>
                            </div>
                            @endif
                            @if($registration->approved_by)
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Aprovado por</label>
                                <p class="fw-bold mb-0">{{ $registration->approvedBy->name }}</p>
                            </div>
                            @endif
                            @if($registration->expiry_date)
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Validade</label>
                                <p class="fw-bold mb-0">{{ $registration->expiry_date->format('d M Y') }}</p>
                            </div>
                            @endif
                            @if($registration->motive)
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Motivo da Inscrição</label>
                                <p class="fw-bold mb-0">{{ $registration->motive }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="border card rounded-2 shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0">Informações de Pagamento</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Taxa de Inscrição</label>
                                <p class="fw-bold mb-0">{{ number_format($registration->registrationType->fee, 2) }} MT</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Referência</label>
                                <p class="fw-bold mb-0">{{ optional($registration->payments->first())->reference_number ?? ($registration->payment_reference ?? '—') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Status do Pagamento</label>
                                <div>
                                    <span class="badge bg-{{ $registration->is_paid ? 'success' : 'warning' }}">
                                        {{ $registration->is_paid ? 'Pago' : 'Pendente' }}
                                    </span>
                                </div>
                            </div>
                            @if($registration->is_paid)
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Data do Pagamento</label>
                                <p class="fw-bold mb-0">{{ $registration->payment_date ? $registration->payment_date->format('d M Y') : 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Método de Pagamento</label>
                                <p class="fw-bold mb-0">{{ optional($registration->payments->first())->payment_method ?? ($registration->payment_method ?? 'N/A') }}</p>
                            </div>
                            @endif
                            @unless($registration->is_paid)
                                <div class="d-grid">
                                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#validatePaymentModal">
                                        <i class="ti ti-currency-dollar me-1"></i> Validar Pagamento
                                    </button>
                                </div>
                            @endunless
                        </div>
                    </div>

                    <!-- Documents Status -->
                    <div class="border card rounded-2 shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0">Status dos Documentos</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @if($registration->person->documents->count() > 0)
                                    @foreach($registration->person->documents->where('registration_id', $registration->id) as $document)
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                                        <span class="text-muted">{{ $document->documentType->name }}</span>
                                        <span class="badge bg-{{ $document->status->color() }}">
                                            <i class="ti ti-{{ $document->status->isPending() ? 'clock' : ($document->status->isValidated() ? 'check' : 'x') }}"></i>
                                        </span>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-3">
                                        Nenhum documento encontrado
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border card rounded-2 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($registration->isApproved() && $registration->person->member)
                                <a href="{{ route('admin.members.show', $registration->person->member) }}" class="btn btn-success">
                                    <i class="ti ti-user me-2"></i>Ver Perfil do Médico
                                </a>
                                @endif
                                @if($registration->isApproved() && $registration->person->member)
                                <a href="{{ route('admin.members.card', $registration->person->member) }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-id me-2"></i>Emitir Cartão
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="row">
                <div class="col-12">
                    <div class="border card rounded-2 shadow-sm mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0">Documentos Anexados</h5>
                            <div class="d-flex gap-2">
                                @if($registration->person->documents->where('registration_id', $registration->id)->count() > 0)
                                <button class="btn btn-sm btn-primary" onclick="downloadAllDocuments()"><i class="ti ti-download me-1"></i>Baixar Todos</button>
                                @endif
                                @unless($registration->documents_validated)
                                    <form method="POST" action="{{ route('admin.registrations.documents.approve-all', $registration) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"><i class="ti ti-check me-1"></i>Aprovar Todos</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.registrations.documents.reject-all', $registration) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="ti ti-x me-1"></i>Rejeitar Todos</button>
                                    </form>
                                @endunless
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($registration->person->documents->count() > 0)
                                    @foreach($registration->person->documents->where('registration_id', $registration->id) as $document)
                                    <div class="col-md-4 mb-3">
                                        <div class="border card rounded-2">
                                            <div class="card-body text-center">
                                                @if(in_array(strtolower(pathinfo($document->original_filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <i class="ti ti-file-type-jpg text-primary" style="font-size: 3rem;"></i>
                                                @elseif(in_array(strtolower(pathinfo($document->original_filename, PATHINFO_EXTENSION)), ['pdf']))
                                                    <i class="ti ti-file-type-pdf text-danger" style="font-size: 3rem;"></i>
                                                @elseif(in_array(strtolower(pathinfo($document->original_filename, PATHINFO_EXTENSION)), ['doc', 'docx']))
                                                    <i class="ti ti-file-type-doc text-primary" style="font-size: 3rem;"></i>
                                                @else
                                                    <i class="ti ti-file text-secondary" style="font-size: 3rem;"></i>
                                                @endif
                                                <h6 class="fw-semibold mt-2 mb-1">{{ $document->documentType->name }}</h6>
                                                <small class="text-muted d-block mb-2">{{ $document->original_filename }}</small>
                                                <div class="mt-2 d-flex justify-content-center gap-1 flex-wrap">
                                                    <a href="{{ route('admin.documents.view', $document) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Ver"><i class="ti ti-eye"></i></a>
                                                    <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-sm btn-outline-secondary" title="Baixar"><i class="ti ti-download"></i></a>
                                                    @if(!$registration->documents_validated && $document->status->isPending())
                                                        <form method="POST" action="{{ route('admin.registrations.documents.approve', [$registration, $document]) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Aprovar"><i class="ti ti-check"></i></button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.registrations.documents.reject', [$registration, $document]) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Rejeitar"><i class="ti ti-x"></i></button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-info mb-0">
                                            Nenhum documento encontrado para esta inscrição.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="row">
                <div class="col-12">
                    <div class="border card rounded-2 shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0">Histórico de Atividades</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @if($registration->status === \App\Enums\RegistrationStatus::APPROVED)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Inscrição Aprovada</h6>
                                        <p class="timeline-description">A inscrição foi aprovada pelo administrador.</p>
                                        <small class="timeline-meta">{{ $registration->approval_date ? $registration->approval_date->format('d M Y, H:i') : 'N/A' }} - {{ $registration->approvedBy ? $registration->approvedBy->name : 'Sistema' }}</small>
                                    </div>
                                </div>
                                @elseif($registration->status === \App\Enums\RegistrationStatus::REJECTED)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Inscrição Rejeitada</h6>
                                        <p class="timeline-description">{{ $registration->rejection_reason }}</p>
                                        <small class="timeline-meta">{{ $registration->updated_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                                @endif

                                @if($registration->documents_validated)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Documentos Verificados</h6>
                                        <p class="timeline-description">Todos os documentos foram verificados e aprovados.</p>
                                        <small class="timeline-meta">{{ $registration->updated_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                                @endif

                                @if($registration->is_paid)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Pagamento Confirmado</h6>
                                        <p class="timeline-description">Pagamento da taxa de inscrição confirmado{{ $registration->payment_method ? ' via '.$registration->payment_method : '' }}.</p>
                                        <small class="timeline-meta">{{ $registration->payment_date ? $registration->payment_date->format('d M Y, H:i') : $registration->updated_at->format('d M Y, H:i') }} - Sistema</small>
                                    </div>
                                </div>
                                @endif

                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Inscrição Submetida</h6>
                                        <p class="timeline-description">Inscrição submetida{{ $registration->person->documents->where('registration_id', $registration->id)->count() > 0 ? ' com '.($registration->person->documents->where('registration_id', $registration->id)->count()).' documento(s)' : '' }}.</p>
                                        <small class="timeline-meta">{{ $registration->submission_date->format('d M Y, H:i') }} - {{ $registration->person->full_name }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End Content -->

    </div>
    <!-- End Page Wrapper -->

    <!-- Validate Payment Modal -->
    <div class="modal fade" id="validatePaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('admin.registrations.validate-payment', $registration) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Validar Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Data do Pagamento</label>
                        <input type="date" name="payment_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Forma de Pagamento</label>
                        <input type="text" name="payment_method" class="form-control" placeholder="Ex.: Transferência, M-Pesa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Referência</label>
                        <input type="text" name="reference_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Valor (opcional)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="{{ number_format($registration->registrationType->fee, 2) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comprovativo (opcional)</label>
                        <input type="file" name="proof" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Validar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Registration Modal -->
    <div class="modal fade" id="rejectRegistrationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('admin.registrations.reject', $registration) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Rejeitar Inscrição</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Motivo da Rejeição</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rejeitar</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function downloadAllDocuments() {
            const documents = @json($registration->person->documents->where('registration_id', $registration->id)->pluck('id'));
            documents.forEach((documentId, index) => {
                setTimeout(() => {
                    window.open('{{ route('admin.documents.download', ':id') }}'.replace(':id', documentId), '_blank');
                }, index * 500); // Delay between downloads to avoid browser blocking
            });
        }
    </script>
    @endpush

</x-layouts.app>
