<x-layouts.guest-registration>
    <x-slot name="header">
        Status da Inscrição
    </x-slot>

    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status-approved {
            background-color: #28a745;
            color: white;
        }

        .status-rejected {
            background-color: #dc3545;
            color: white;
        }

        .status-expired {
            background-color: #6c757d;
            color: white;
        }

        .info-section {
            margin-bottom: 25px;
            padding-bottom: 5px;
        }

        .info-section h5 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            color: #2d5016;
            font-weight: 600;
        }

        .info-item {
            margin-bottom: 12px;
        }

        .info-label {
            font-weight: 600;
            color: #4a7c2a;
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 500;
            color: #333;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(45, 80, 22, 0.1);
            overflow: hidden;
        }

        .card-header {
            background-color: #f9f9f9;
            border-bottom: 1px solid rgba(74, 124, 42, 0.2);
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Status da Inscrição</h5>
                        <x-status-badge :status="$registration->status" :size="'default'" />
                    </div>
                    <div class="card-body">
                        <div class="info-section">
                            <h5>Informações da Inscrição</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Número de Inscrição</div>
                                        <div class="info-value">{{ $registration->registration_number }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Tipo de Inscrição</div>
                                        <div class="info-value">{{ $registration->registrationType->name }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Data de Submissão</div>
                                        <div class="info-value">{{ $registration->submission_date->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Validade</div>
                                        <div class="info-value">
                                            @if($registration->expiry_date)
                                                {{ $registration->expiry_date->format('d/m/Y') }}
                                            @else
                                                Não aplicável
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-section">
                            <h5>Informações Pessoais</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Nome Completo</div>
                                        <div class="info-value">{{ $registration->person->full_name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Documento de Identidade</div>
                                        <div class="info-value">{{ $registration->person->identity_document_number }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($registration->status === \App\Enums\RegistrationStatus::SUBMITTED || $registration->status === \App\Enums\RegistrationStatus::UNDER_REVIEW)
                            <div class="alert alert-warning">
                                <i class="fas fa-clock me-2"></i> Sua inscrição está sendo analisada. Por favor, aguarde a aprovação.
                            </div>
                        @elseif($registration->status === \App\Enums\RegistrationStatus::APPROVED)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> Sua inscrição foi aprovada. Você pode agora proceder com os próximos passos.
                            </div>
                        @elseif($registration->status === \App\Enums\RegistrationStatus::REJECTED)
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle me-2"></i> Sua inscrição foi rejeitada.
                                @if($registration->rejection_reason)
                                    <p class="mt-2 mb-0"><strong>Motivo:</strong> {{ $registration->rejection_reason }}</p>
                                @endif
                            </div>
                        @elseif($registration->status === \App\Enums\RegistrationStatus::EXPIRED)
                            <div class="alert alert-secondary">
                                <i class="fas fa-exclamation-circle me-2"></i> Sua inscrição expirou. Por favor, entre em contato com a Ordem dos Médicos para mais informações.
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('guest.registrations.check-status') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Voltar
                            </a>
                            @if($registration->status === \App\Enums\RegistrationStatus::REJECTED)
                                <a href="{{ route('guest.registrations.type') }}" class="btn btn-primary">
                                    <i class="fas fa-redo me-2"></i> Nova Inscrição
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest-registration>
