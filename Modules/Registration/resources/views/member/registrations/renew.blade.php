<x-layouts.app>
    <x-slot name="title">Renovar Inscrição</x-slot>

    <x-slot name="header">
        <h4 class="card-title mb-1">Renovar Inscrição</h4>
        <p class="card-text mb-0">Renove sua inscrição na Ordem dos Médicos de Moçambique</p>
    </x-slot>

    <!-- Current Registration Status -->
    <div class="row">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning-light">
                    <h5 class="card-title text-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Inscrição Atual
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="stat-icon bg-success-light mx-auto mb-2 status-icon-container">
                                    <i class="fas fa-id-card text-success icon-lg"></i>
                                </div>
                                <h6>Número</h6>
                                <p class="mb-0"><strong>#{{ $registration->registration_number }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="stat-icon bg-primary-light mx-auto mb-2 status-icon-container">
                                    <i class="fas fa-certificate text-primary icon-lg"></i>
                                </div>
                                <h6>Tipo</h6>
                                <p class="mb-0"><strong>{{ $registration->registrationType->name }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="stat-icon bg-warning-light mx-auto mb-2 status-icon-container">
                                    <i class="fas fa-calendar-alt text-warning icon-lg"></i>
                                </div>
                                <h6>Validade</h6>
                                <p class="mb-0"><strong>{{ $registration->expiry_date->format('d/m/Y') }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="stat-icon bg-danger-light mx-auto mb-2 status-icon-container">
                                    <i class="fas fa-clock text-danger icon-lg"></i>
                                </div>
                                <h6>Dias Restantes</h6>
                                <p class="mb-0"><strong class="text-danger">{{ now()->diffInDays($registration->expiry_date, false) }} dias</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Renewal Form -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <form id="renewalForm" action="{{ route('member.registrations.store-renewal', $registration) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Personal Information Confirmation -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-user-check me-2"></i>Confirmação de Dados Pessoais
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Verifique se seus dados estão atualizados. Se necessário, atualize seu perfil antes de continuar.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Nome Completo</label>
                                    <p class="mb-0">{{ auth()->user()->person->full_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <p class="mb-0">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Telefone</label>
                                    <p class="mb-0">{{ auth()->user()->person->phone ?? 'Não informado' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Especialidade</label>
                                    <p class="mb-0">{{ $registration->specialty ?? 'Não informado' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('member.profile') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-2"></i>Atualizar Dados
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Professional Activity Update -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-briefcase me-2"></i>Atividade Profissional Atual
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="workplace" class="form-label">Local de Trabalho Atual *</label>
                                    <input type="text" class="form-control @error('workplace') is-invalid @enderror" id="workplace" name="workplace" value="{{ old('workplace', $registration->workplace) }}" required>
                                    @error('workplace')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="current_position" class="form-label">Cargo Atual *</label>
                                    <input type="text" class="form-control @error('current_position') is-invalid @enderror" id="current_position" name="current_position" value="{{ old('current_position', $registration->professional_category) }}" required>
                                    @error('current_position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="workplace_address" class="form-label">Endereço do Local de Trabalho *</label>
                                    <textarea class="form-control @error('workplace_address') is-invalid @enderror" id="workplace_address" name="workplace_address" rows="2" required>{{ old('workplace_address', $registration->workplace_address) }}</textarea>
                                    @error('workplace_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="workplace_phone" class="form-label">Telefone do Trabalho</label>
                                    <input type="tel" class="form-control @error('workplace_phone') is-invalid @enderror" id="workplace_phone" name="workplace_phone" value="{{ old('workplace_phone', $registration->workplace_phone) }}">
                                    @error('workplace_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="professional_activities" class="form-label">Atividades Profissionais no Período *</label>
                                    <textarea class="form-control @error('professional_activities') is-invalid @enderror" id="professional_activities" name="professional_activities" rows="4" required placeholder="Descreva suas principais atividades profissionais durante o período da inscrição atual...">{{ old('professional_activities') }}</textarea>
                                    @error('professional_activities')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Continuing Education -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-graduation-cap me-2"></i>Formação Continuada
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Informe sobre cursos, congressos, workshops ou outras atividades de formação realizadas no período.
                        </div>
                        <div id="educationContainer">
                            <div class="education-item border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nome do Curso/Evento</label>
                                            <input type="text" class="form-control" name="education_name[]">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Instituição</label>
                                            <input type="text" class="form-control" name="education_institution[]">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Data</label>
                                            <input type="date" class="form-control" name="education_date[]">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Carga Horária</label>
                                            <input type="number" class="form-control" name="education_hours[]" placeholder="Horas">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Certificado</label>
                                            <input type="file" class="form-control" name="education_certificate[]" accept=".pdf">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-education">
                                    <i class="fas fa-trash me-1"></i>Remover
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary" id="addEducation">
                            <i class="fas fa-plus me-2"></i>Adicionar Formação
                        </button>
                    </div>
                </div>

                <!-- Required Documents -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-file-upload me-2"></i>Documentos para Renovação
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Atenção:</strong> Alguns documentos podem ser obrigatórios dependendo do tempo desde a última renovação.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="work_certificate" class="form-label">Certificado de Trabalho Atual</label>
                                    <input type="file" class="form-control @error('work_certificate') is-invalid @enderror" id="work_certificate" name="work_certificate" accept=".pdf">
                                    <div class="form-text">Formato: PDF | Tamanho máximo: 5MB</div>
                                    @error('work_certificate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medical_certificate" class="form-label">Atestado Médico Atualizado</label>
                                    <input type="file" class="form-control @error('medical_certificate') is-invalid @enderror" id="medical_certificate" name="medical_certificate" accept=".pdf">
                                    <div class="form-text">Formato: PDF | Tamanho máximo: 5MB</div>
                                    @error('medical_certificate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="criminal_record" class="form-label">Registo Criminal Atualizado</label>
                                    <input type="file" class="form-control @error('criminal_record') is-invalid @enderror" id="criminal_record" name="criminal_record" accept=".pdf">
                                    <div class="form-text">Formato: PDF | Tamanho máximo: 5MB</div>
                                    @error('criminal_record')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Fotografia Atualizada</label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept=".jpg,.jpeg,.png">
                                    <div class="form-text">Formato: JPG, PNG | Tamanho máximo: 2MB</div>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Declaration -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-file-signature me-2"></i>Declaração
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input @error('activity_declaration') is-invalid @enderror" type="checkbox" id="activity_declaration" name="activity_declaration" required {{ old('activity_declaration') ? 'checked' : '' }}>
                            <label class="form-check-label" for="activity_declaration">
                                Declaro que exerci atividade médica durante o período da inscrição atual *
                            </label>
                            @error('activity_declaration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input @error('ethics_declaration') is-invalid @enderror" type="checkbox" id="ethics_declaration" name="ethics_declaration" required {{ old('ethics_declaration') ? 'checked' : '' }}>
                            <label class="form-check-label" for="ethics_declaration">
                                Declaro que não cometi infrações éticas durante o período da inscrição *
                            </label>
                            @error('ethics_declaration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input @error('data_accuracy') is-invalid @enderror" type="checkbox" id="data_accuracy" name="data_accuracy" required {{ old('data_accuracy') ? 'checked' : '' }}>
                            <label class="form-check-label" for="data_accuracy">
                                Declaro que todas as informações fornecidas são verdadeiras e atualizadas *
                            </label>
                            @error('data_accuracy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @error('terms_accept') is-invalid @enderror" type="checkbox" id="terms_accept" name="terms_accept" required {{ old('terms_accept') ? 'checked' : '' }}>
                            <label class="form-check-label" for="terms_accept">
                                Aceito os termos e condições para renovação da inscrição *
                            </label>
                            @error('terms_accept')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('member.registrations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-save me-2"></i>Salvar Rascunho
                                </button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-sync-alt me-2"></i>Submeter Renovação
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Renewal Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Renovação de Inscrição</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Prazo</h6>
                        <p class="text-muted small">A renovação deve ser feita até 30 dias antes do vencimento.</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Taxa</h6>
                        <p class="text-muted small">{{ number_format($renewalType->fee, 2) }} MT (pagamento após aprovação)</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Validade</h6>
                        <p class="text-muted small">Nova inscrição válida por {{ $renewalType->validity_period_days / 365 }} anos.</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Processo de Renovação</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Submissão</h6>
                                <small class="text-muted">Envio da renovação</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Análise</h6>
                                <small class="text-muted">3-7 dias úteis</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pagamento</h6>
                                <small class="text-muted">Após aprovação</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Renovação</h6>
                                <small class="text-muted">Nova validade</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Notas Importantes</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-exclamation-circle text-warning me-2"></i>
                            <small>Renovação após vencimento incorre em multa</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-info me-2"></i>
                            <small>Processo mais rápido que nova inscrição</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-certificate text-success me-2"></i>
                            <small>Formação continuada é valorizada</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add/Remove Education functionality
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addEducation').addEventListener('click', function() {
                const container = document.getElementById('educationContainer');
                const newItem = container.querySelector('.education-item').cloneNode(true);

                // Clear all inputs in the cloned item
                newItem.querySelectorAll('input').forEach(input => {
                    input.value = '';
                });

                container.appendChild(newItem);
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-education') || e.target.closest('.remove-education')) {
                    const educationItems = document.querySelectorAll('.education-item');
                    if (educationItems.length > 1) {
                        e.target.closest('.education-item').remove();
                    } else {
                        alert('Deve manter pelo menos um item de formação.');
                    }
                }
            });
        });

        // File upload validation
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const maxSize = this.accept.includes('image') ? 2 * 1024 * 1024 : 5 * 1024 * 1024;

                    if (file.size > maxSize) {
                        alert('Arquivo muito grande. Tamanho máximo: ' + (maxSize / 1024 / 1024) + 'MB');
                        this.value = '';
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
