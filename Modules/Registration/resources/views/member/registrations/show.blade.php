<x-layouts.app>
    <x-slot name="title">Acompanhar Status</x-slot>

    <x-slot name="header">
        <h4 class="card-title mb-1">Acompanhar Status da Inscrição</h4>
        <p class="card-text mb-0">Monitore o progresso de sua solicitação de inscrição</p>
    </x-slot>

    <x-slot name="actions">
        <button class="btn btn-outline-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt me-2"></i>Atualizar
        </button>
    </x-slot>

    <!-- Registration Process -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Processo em Andamento</h5>
                </div>
                <div class="card-body">
                    <!-- Registration Process Card -->
                    <div class="card border-{{ $registration->status === 'pending' ? 'warning' : ($registration->status === 'approved' ? 'success' : 'danger') }} mb-4">
                        <div class="card-header bg-{{ $registration->status === 'pending' ? 'warning' : ($registration->status === 'approved' ? 'success' : 'danger') }}-light">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="mb-0">
                                        <i class="fas fa-{{ $registration->is_renewal ? 'sync-alt' : 'user-plus' }} text-{{ $registration->status === 'pending' ? 'warning' : ($registration->status === 'approved' ? 'success' : 'danger') }} me-2"></i>
                                        {{ $registration->is_renewal ? 'Renovação de Inscrição' : 'Nova Inscrição' }} #{{ $registration->registration_number }}
                                    </h6>
                                    <small class="text-muted">Submetido em {{ $registration->submission_date->format('d/m/Y') }}</small>
                                </div>
                                <div class="col-auto">
                                    <x-status-badge :status="$registration->status" :size="'default'" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Progress Timeline -->
                                    <div class="timeline">
                                        <div class="timeline-item completed">
                                            <div class="timeline-marker bg-success">
                                                <i class="fas fa-check text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Submissão Recebida</h6>
                                                <p class="text-muted mb-1">Documentos recebidos e protocolo gerado</p>
                                                <small class="text-success">{{ $registration->submission_date->format('d/m/Y - H:i') }}</small>
                                            </div>
                                        </div>

                                        <div class="timeline-item {{ $registration->documents_validated ? 'completed' : ($registration->status === 'pending' ? 'active' : '') }}">
                                            <div class="timeline-marker bg-{{ $registration->documents_validated ? 'success' : ($registration->status === 'pending' ? 'warning' : 'secondary') }}">
                                                <i class="fas fa-{{ $registration->documents_validated ? 'check' : ($registration->status === 'pending' ? 'clock' : 'circle') }} text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Análise Documental</h6>
                                                <p class="text-muted mb-1">{{ $registration->documents_validated ? 'Documentos verificados e aprovados' : 'Verificação de documentos em andamento' }}</p>
                                                @if($registration->documents_validated)
                                                    <small class="text-success">Concluído</small>
                                                @else
                                                    <small class="text-{{ $registration->status === 'pending' ? 'warning' : 'muted' }}">{{ $registration->status === 'pending' ? 'Em andamento' : 'Pendente' }}</small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="timeline-item {{ $registration->status === 'approved' || $registration->status === 'rejected' ? 'completed' : ($registration->documents_validated ? 'active' : '') }}">
                                            <div class="timeline-marker bg-{{ $registration->status === 'approved' || $registration->status === 'rejected' ? 'success' : ($registration->documents_validated ? 'warning' : 'secondary') }}">
                                                <i class="fas fa-{{ $registration->status === 'approved' || $registration->status === 'rejected' ? 'check' : ($registration->documents_validated ? 'clock' : 'circle') }} text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Avaliação Técnica</h6>
                                                <p class="text-muted mb-1">{{ $registration->status === 'approved' || $registration->status === 'rejected' ? 'Análise concluída pela comissão técnica' : 'Em análise pela comissão técnica' }}</p>
                                                @if($registration->status === 'approved' || $registration->status === 'rejected')
                                                    <small class="text-success">Concluído {{ $registration->approval_date ? 'em ' . $registration->approval_date->format('d/m/Y') : '' }}</small>
                                                @elseif($registration->documents_validated)
                                                    <small class="text-warning">Em andamento</small>
                                                @else
                                                    <small class="text-muted">Pendente</small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="timeline-item {{ $registration->is_paid ? 'completed' : ($registration->status === 'approved' ? 'active' : '') }}">
                                            <div class="timeline-marker bg-{{ $registration->is_paid ? 'success' : ($registration->status === 'approved' ? 'info' : 'secondary') }}">
                                                <i class="fas fa-{{ $registration->is_paid ? 'check' : ($registration->status === 'approved' ? 'credit-card' : 'circle') }} text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Pagamento</h6>
                                                @if($registration->is_paid)
                                                    <p class="text-muted mb-1">Pagamento confirmado</p>
                                                    <small class="text-success">Pago em {{ $registration->payment_date->format('d/m/Y') }}</small>
                                                @elseif($registration->status === 'approved')
                                                    <p class="text-muted mb-1">Aguardando pagamento da taxa de inscrição</p>
                                                    <small class="text-info">Referência: {{ number_format($registration->registrationType->fee, 2) }}MT - Ref: {{ $registration->payment_reference ?? 'Pendente' }}</small>
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-primary">
                                                            <i class="fas fa-credit-card me-1"></i>Pagar Agora
                                                        </button>
                                                    </div>
                                                @else
                                                    <p class="text-muted mb-1">Aguardando aprovação para gerar cobrança</p>
                                                    <small class="text-muted">Pendente</small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="timeline-item {{ $registration->is_paid && $registration->status === 'approved' ? 'completed' : '' }}">
                                            <div class="timeline-marker bg-{{ $registration->is_paid && $registration->status === 'approved' ? 'success' : 'secondary' }}">
                                                <i class="fas fa-{{ $registration->is_paid && $registration->status === 'approved' ? 'check' : 'circle' }} text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Emissão do Certificado</h6>
                                                @if($registration->is_paid && $registration->status === 'approved')
                                                    <p class="text-muted mb-1">Certificado emitido com sucesso</p>
                                                    <small class="text-success">Disponível para download</small>
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-success">
                                                            <i class="fas fa-download me-1"></i>Baixar Certificado
                                                        </button>
                                                    </div>
                                                @else
                                                    <p class="text-muted mb-1">Certificado será emitido após pagamento</p>
                                                    <small class="text-muted">Pendente</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Detalhes do Processo</h6>
                                            <div class="mb-2">
                                                <small class="text-muted">Tipo:</small>
                                                <br><strong>{{ $registration->registrationType->name }}</strong>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Prazo Estimado:</small>
                                                <br><strong>{{ $registration->is_renewal ? '3-7' : '5-10' }} dias úteis</strong>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Taxa:</small>
                                                <br><strong>{{ number_format($registration->registrationType->fee, 2) }} MT</strong>
                                            </div>
                                            <div class="mb-3">
                                                <small class="text-muted">Responsável:</small>
                                                <br><strong>Comissão de Inscrições</strong>
                                            </div>

                                            @php
                                                $progress = 0;
                                                if ($registration->submission_date) $progress += 20;
                                                if ($registration->documents_validated) $progress += 20;
                                                if ($registration->status === 'approved' || $registration->status === 'rejected') $progress += 20;
                                                if ($registration->is_paid) $progress += 20;
                                                if ($registration->is_paid && $registration->status === 'approved') $progress += 20;

                                                $progressClass = 'bg-warning';
                                                if ($progress >= 80) $progressClass = 'bg-success';
                                                elseif ($progress >= 60) $progressClass = 'bg-info';
                                                elseif ($progress <= 20) $progressClass = 'bg-danger';
                                            @endphp

                                            <div class="progress mb-2">
                                                <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">{{ $progress }}% Concluído</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Details -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detalhes da Inscrição</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Informações Gerais</h6>
                            <div class="mb-3">
                                <small class="text-muted d-block">Número de Inscrição:</small>
                                <strong>{{ $registration->registration_number }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Tipo de Inscrição:</small>
                                <strong>{{ $registration->registrationType->name }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Data de Submissão:</small>
                                <strong>{{ $registration->submission_date->format('d/m/Y') }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Status:</small>
                                <x-status-badge :status="$registration->status" :size="'sm'" />
                            </div>
                            @if($registration->status === 'approved' && $registration->expiry_date)
                            <div class="mb-3">
                                <small class="text-muted d-block">Validade:</small>
                                <strong>{{ $registration->expiry_date->format('d/m/Y') }}</strong>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Informações Profissionais</h6>
                            <div class="mb-3">
                                <small class="text-muted d-block">Categoria Profissional:</small>
                                <strong>{{ $registration->professional_category ?? 'Não informado' }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Especialidade:</small>
                                <strong>{{ $registration->specialty ?? 'Não informado' }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Local de Trabalho:</small>
                                <strong>{{ $registration->workplace ?? 'Não informado' }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Formação Acadêmica:</small>
                                <strong>{{ $registration->academic_degree ?? 'Não informado' }} - {{ $registration->university ?? 'Não informado' }}</strong>
                            </div>
                        </div>
                    </div>

                    @if($registration->status === 'rejected' && $registration->rejection_reason)
                    <div class="alert alert-danger mt-4">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Motivo da Rejeição</h6>
                        <p class="mb-0">{{ $registration->rejection_reason }}</p>
                    </div>
                    @endif

                    @if($registration->notes)
                    <div class="alert alert-info mt-4">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Observações</h6>
                        <p class="mb-0">{{ $registration->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Status -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Status dos Documentos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Data de Envio</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($registration->documents->isNotEmpty())
                                    @foreach($registration->documents as $document)
                                    <tr>
                                        <td>{{ $document->name }}</td>
                                        <td>{{ $document->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @if($document->status === 'approved')
                                                <span class="badge bg-success">Aprovado</span>
                                            @elseif($document->status === 'rejected')
                                                <span class="badge bg-danger">Rejeitado</span>
                                            @else
                                                <span class="badge bg-warning">Em Análise</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($document->status === 'rejected')
                                                <a href="#" class="btn btn-sm btn-outline-warning" title="Reenviar">
                                                    <i class="fas fa-upload"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Nenhum documento encontrado</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ações Disponíveis</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('member.registrations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar para Lista
                        </a>

                        @if($registration->status === 'approved' && !$registration->is_paid)
                        <button class="btn btn-primary">
                            <i class="fas fa-credit-card me-2"></i>Realizar Pagamento
                        </button>
                        @endif

                        @if($registration->status === 'rejected')
                        <a href="{{ route('member.registrations.create') }}" class="btn btn-warning">
                            <i class="fas fa-redo me-2"></i>Iniciar Nova Inscrição
                        </a>
                        @endif

                        @if($registration->status === 'approved' && $registration->is_paid)
                        <button class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Baixar Certificado
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info-light">
                    <h5 class="card-title text-info mb-0">
                        <i class="fas fa-question-circle me-2"></i>Precisa de Ajuda?
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">Se tiver dúvidas sobre o status do seu processo ou precisar de esclarecimentos, entre em contacto connosco:</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-phone text-info me-2"></i>
                                <div>
                                    <strong>Telefone</strong>
                                    <br><a href="tel:+258123456789">+258 12 345 6789</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-envelope text-info me-2"></i>
                                <div>
                                    <strong>Email</strong>
                                    <br><a href="mailto:inscricoes@ordemmedicos.mz">inscricoes@ordemmedicos.mz</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock text-info me-2"></i>
                                <div>
                                    <strong>Horário</strong>
                                    <br>Segunda a Sexta: 8h-17h
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
