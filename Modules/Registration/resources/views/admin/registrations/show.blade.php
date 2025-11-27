<x-layouts.app>
    <x-slot name="header">
        Detalhes da Inscrição
    </x-slot>

    <!-- Styles moved to public/assets/css/style.css -->

    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ dashboard_route() }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.registrations.index') }}">Inscrições</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detalhes da Inscrição</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card welcome-card mb-4">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h4 class="card-title mb-1 heading-4">Detalhes da Inscrição #{{ $registration->registration_number }}</h4>
                            <p class="card-text mb-0 text-base">Informações completas sobre a inscrição médica.</p>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary me-2"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
                            <a class="btn btn-success me-2" href="{{ route('admin.registrations.export-pdf', $registration) }}"><i class="fas fa-download me-2"></i>Exportar PDF</a>
                            @if(!$registration->isApproved() && !$registration->isRejected())
                                <a class="btn btn-primary me-2" href="{{ route('admin.registrations.edit-wizard', $registration) }}"><i class="fas fa-edit me-2"></i>Editar</a>
                                @if(!$registration->isValidated())
                                    <form class="d-inline" method="POST" action="{{ route('admin.registrations.validate', $registration) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-info me-2"><i class="fas fa-clipboard-check me-1"></i>Validar Inscrição</button>
                                    </form>
                                @endif
                                @if($registration->isValidated())
                                    <form class="d-inline" method="POST" action="{{ route('admin.registrations.approve', $registration) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success me-2"><i class="fas fa-check me-1"></i>Aprovar Inscrição</button>
                                    </form>
                                @endif
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectRegistrationModal"><i class="fas fa-times me-1"></i>Rejeitar Inscrição</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status and Actions -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <x-status-badge :status="$registration->status" :size="'lg'" />
                                <div>
                                    <h6 class="mb-0 text-sm fw-semibold">Status da Inscrição</h6>
                                    @if($registration->approval_date)
                                        <small class="text-muted">Aprovada em {{ $registration->approval_date->format('d M Y') }}</small>
                                    @elseif($registration->isRejected())
                                        <small class="text-muted">Rejeitada</small>
                                    @else
                                        <small class="text-muted">Pendente desde {{ $registration->submission_date->format('d M Y') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="btn-group" role="group">
                                @if(!$registration->isApproved() && !$registration->isRejected())
                                    <!-- Ações principais já no topo -->
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            <div>
                                <h6 class="mb-0 text-sm fw-semibold">Data de Submissão</h6>
                                <small class="text-muted">{{ $registration->submission_date->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Legend -->
        <div class="row mb-4">
            <div class="col-12">
                <x-status-legend title="Legenda de Status de Inscrições" :statusEnum="\App\Enums\RegistrationStatus::class" />
            </div>
        </div>

        <!-- Main Details -->
        <div class="row">
            <!-- Personal Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title card-title-lg">Informações Pessoais</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($registration->person->full_name) }}&background=0D8ABC&color=fff&size=120" class="rounded-circle mb-3" alt="Profile">
                                <h5 class="card-title-xl">{{ $registration->person->full_name }}</h5>
                                <p class="text-muted">{{ $registration->specialty ?? 'Sem especialidade' }}</p>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Nome Completo</label>
                                        <p class="fw-bold">{{ $registration->person->full_name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Data de Nascimento</label>
                                        <p class="fw-bold">{{ $registration->person->birth_date ? $registration->person->birth_date->format('d F Y') : 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Número de Identificação</label>
                                        <p class="fw-bold">{{ $registration->person->identity_document_number }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Nacionalidade</label>
                                        <p class="fw-bold">{{ $registration->person->nationality_id ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Email</label>
                                        <p class="fw-bold">{{ $registration->person->email }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Telefone</label>
                                        <p class="fw-bold">{{ $registration->person->phone }}</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted">Endereço</label>
                                        <p class="fw-bold">{{ $registration->person->living_address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title card-title-lg">Informações Académicas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Instituição de Formação</label>
                                <p class="fw-bold">{{ optional($registration->person->currentAcademicQualification)->institution_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Ano de Graduação</label>
                                <p class="fw-bold">{{ optional(optional($registration->person->currentAcademicQualification)->completion_date)->format('d/m/Y') ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Especialidade</label>
                                <p class="fw-bold">{{ $registration->specialty ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Ano de Especialização</label>
                                <p class="fw-bold">N/A</p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">Outras Qualificações</label>
                                <p class="fw-bold">N/A</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title card-title-lg">Informações Profissionais</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Local de Trabalho Atual</label>
                                <p class="fw-bold">{{ optional($registration->person->currentWorkExperience)->institution_name ?? ($registration->workplace ?? 'N/A') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Cargo</label>
                                <p class="fw-bold">{{ $registration->professional_category ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Anos de Experiência</label>
                                <p class="fw-bold">N/A</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Número de Licença Anterior</label>
                                <p class="fw-bold">N/A</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="col-lg-4">
                <!-- Inscription Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title card-title-lg">Detalhes da Inscrição</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">ID da Inscrição</label>
                            <p class="fw-bold">{{ $registration->registration_number }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Tipo de Inscrição</label>
                            <span class="badge bg-info">{{ $registration->registrationType->name }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Data de Submissão</label>
                            <p class="fw-bold">{{ $registration->submission_date->format('d M Y, H:i') }}</p>
                        </div>
                        @if($registration->approval_date)
                        <div class="mb-3">
                            <label class="form-label text-muted">Data de Aprovação</label>
                            <p class="fw-bold">{{ $registration->approval_date->format('d M Y, H:i') }}</p>
                        </div>
                        @endif
                        @if($registration->approved_by)
                        <div class="mb-3">
                            <label class="form-label text-muted">Aprovado por</label>
                            <p class="fw-bold">{{ $registration->approvedBy->name }}</p>
                        </div>
                        @endif
                        @if($registration->expiry_date)
                        <div class="mb-3">
                            <label class="form-label text-muted">Validade</label>
                            <p class="fw-bold">{{ $registration->expiry_date->format('d M Y') }}</p>
                        </div>
                        @endif
                        @if($registration->motive)
                        <div class="mb-3">
                            <label class="form-label text-muted">Motivo da Inscrição</label>
                            <p class="fw-bold">{{ $registration->motive }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title card-title-lg">Informações de Pagamento</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Taxa de Inscrição</label>
                            <p class="fw-bold">{{ number_format($registration->registrationType->fee, 2) }} MT</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Referência</label>
                            <p class="fw-bold">{{ optional($registration->payments->first())->reference_number ?? ($registration->payment_reference ?? '—') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Status do Pagamento</label>
                            <span class="badge bg-{{ $registration->is_paid ? 'success' : 'warning' }}">
                                {{ $registration->is_paid ? 'Pago' : 'Pendente' }}
                            </span>
                        </div>
                        @if($registration->is_paid)
                        <div class="mb-3">
                            <label class="form-label text-muted">Data do Pagamento</label>
                            <p class="fw-bold">{{ $registration->payment_date ? $registration->payment_date->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Método de Pagamento</label>
                            <p class="fw-bold">{{ optional($registration->payments->first())->payment_method ?? ($registration->payment_method ?? 'N/A') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Referência</label>
                            <p class="fw-bold">{{ optional($registration->payments->first())->reference_number ?? ($registration->payment_reference ?? 'N/A') }}</p>
                        </div>
                        @endif
                        @unless($registration->is_paid)
                            <div class="d-grid">
                                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#validatePaymentModal">
                                    <i class="fas fa-money-check-alt me-1"></i> Validar Pagamento
                                </button>
                            </div>
                        @endunless
                    </div>
                </div>

                <!-- Documents Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title card-title-lg">Status dos Documentos</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @if($registration->person->documents->count() > 0)
                                @foreach($registration->person->documents->where('registration_id', $registration->id) as $document)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>{{ $document->documentType->name }}</span>
                                    <span class="badge bg-{{ $document->status->color() }}">
                                        <i class="fas fa-{{ $document->status->isPending() ? 'clock' : ($document->status->isValidated() ? 'check' : 'times') }}"></i>
                                    </span>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted">
                                    Nenhum documento encontrado
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if($registration->isApproved())
                            <a href="#" class="btn btn-success">
                                <i class="fas fa-user-md me-2"></i>Ver Perfil do Médico
                            </a>
                            @endif
                            {{-- Renew and Reinstate removed temporarily --}}
                            {{-- @if($registration->isRenewable())
                            <a href="{{ route('admin.registrations.renew', $registration) }}" class="btn btn-primary">
                                <i class="fas fa-sync-alt me-2"></i>Renovar Inscrição
                            </a>
                            @endif
                            <a href="{{ route('admin.registrations.reinstate', $registration) }}" class="btn btn-info">
                                <i class="fas fa-redo me-2"></i>Reinscrever
                            </a> --}}
                            <button class="btn btn-outline-secondary">
                                <i class="fas fa-id-card me-2"></i>Emitir Cartão
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title card-title-lg">Documentos Anexados</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-primary"><i class="fas fa-download me-1"></i>Baixar Todos</button>
                            @unless($registration->documents_validated)
                                <form method="POST" action="{{ route('admin.registrations.documents.approve-all', $registration) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check me-1"></i>Aprovar Todos</button>
                                </form>
                                <form method="POST" action="{{ route('admin.registrations.documents.reject-all', $registration) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-times me-1"></i>Rejeitar Todos</button>
                                </form>
                            @endunless
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($registration->person->documents->count() > 0)
                                @foreach($registration->person->documents->where('registration_id', $registration->id) as $document)
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            @if(in_array(pathinfo($document->original_filename, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                <i class="fas fa-file-image text-primary fa-3x mb-2"></i>
                                            @elseif(in_array(pathinfo($document->original_filename, PATHINFO_EXTENSION), ['pdf']))
                                                <i class="fas fa-file-pdf text-danger fa-3x mb-2"></i>
                                            @elseif(in_array(pathinfo($document->original_filename, PATHINFO_EXTENSION), ['doc', 'docx']))
                                                <i class="fas fa-file-word text-primary fa-3x mb-2"></i>
                                            @else
                                                <i class="fas fa-file text-secondary fa-3x mb-2"></i>
                                            @endif
                                            <h6 class="heading-6">{{ $document->documentType->name }}</h6>
                                            <small class="text-muted">{{ $document->original_filename }}</small>
                                            <div class="mt-2 d-flex justify-content-center gap-1">
                                                <a href="{{ $document->file_url }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Ver"><i class="fas fa-eye"></i></a>
                                                <a href="{{ $document->file_url }}" class="btn btn-sm btn-outline-secondary" download title="Baixar"><i class="fas fa-download"></i></a>
                                                @if(!$registration->documents_validated && $document->status->isPending())
                                                    <form method="POST" action="{{ route('admin.registrations.documents.approve', [$registration, $document]) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Aprovar"><i class="fas fa-check"></i></button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.registrations.documents.reject', [$registration, $document]) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Rejeitar"><i class="fas fa-times"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        Nenhum documento encontrado para esta inscrição.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validate Payment Modal -->
        <div class="modal fade" id="validatePaymentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{ route('admin.registrations.validate-payment', $registration) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title modal-title-lg">Validar Pagamento</h5>
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
                        <h5 class="modal-title modal-title-lg">Rejeitar Inscrição</h5>
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

        <!-- Activity Log -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title card-title-lg">Histórico de Atividades</h5>
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

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.registrations.approve', $registration) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title modal-title-lg" id="approveModalLabel">Aprovar Inscrição</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja aprovar esta inscrição?</p>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Observações (opcional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
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
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.registrations.reject', $registration) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title modal-title-lg" id="rejectModalLabel">Rejeitar Inscrição</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja rejeitar esta inscrição?</p>
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Motivo da Rejeição <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required></textarea>
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
</x-layouts.app>
