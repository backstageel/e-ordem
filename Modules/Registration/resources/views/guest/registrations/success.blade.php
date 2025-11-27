<x-layouts.guest-registration>
    <x-slot name="header">
        Inscrição Enviada com Sucesso
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="mb-3">Inscrição Enviada com Sucesso!</h3>
                        <p class="lead mb-4">Sua solicitação de inscrição foi recebida e está sendo processada.</p>

                        <div class="alert alert-info mb-4">
                            <p class="mb-2"><strong>Número de Inscrição:</strong></p>
                            <h4 class="mb-0">{{ $registrationNumber }}</h4>
                            <p class="mt-2 mb-0 small">Guarde este número para consultas futuras.</p>
                        </div>

                        <div class="mb-4">
                            {!! QrCode::size(160)->generate($registrationNumber) !!}
                            <div class="small text-muted mt-2">Digitalize para verificar sua inscrição.</div>
                        </div>

                        @if(isset($payment) && $payment)
                        <div class="card text-start mb-4">
                            <div class="card-header fw-bold">Pagamento Inicial</div>
                            <div class="card-body">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-4">
                                        <div class="text-muted small">Referência</div>
                                        <div class="fs-5 fw-semibold">{{ $payment->reference_number }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-muted small">Valor</div>
                                        <div class="fs-5 fw-semibold">{{ number_format($payment->amount, 2, ',', '.') }} MT</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-muted small">Vencimento</div>
                                        <div class="fs-6">{{ optional($payment->due_date)->format('d/m/Y') }}</div>
                                    </div>
                                </div>

                                <hr class="my-3">

                                <div class="mb-2 fw-bold">Instruções de Pagamento</div>
                                <ul class="mb-0">
                                    <li>M-Pesa/e-Mola/MCel Money: Use a opção Pagamentos e introduza a referência <strong>{{ $payment->reference_number }}</strong> e o valor exacto.</li>
                                    <li>Transferência Bancária: Indique a referência <strong>{{ $payment->reference_number }}</strong> no descritivo da transferência.</li>
                                    <li>Presencial: Apresente a referência <strong>{{ $payment->reference_number }}</strong> no balcão da OrMM.</li>
                                </ul>
                            </div>
                        </div>
                        @endif

                        <p class="mb-4">
                            Você receberá uma notificação quando sua inscrição for analisada.
                            Você também pode verificar o status da sua inscrição a qualquer momento
                            usando o número de inscrição e seu documento de identidade.
                        </p>

                        <div class="d-grid gap-3">
                            <a href="{{ route('guest.registrations.check-status') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i> Verificar Status da Inscrição
                            </a>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i> Voltar para Página Inicial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest-registration>
