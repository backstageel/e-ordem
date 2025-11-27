<x-layouts.guest-registration>
    <x-slot name="header">
        Verificar Status da Inscrição
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Verificar Status da Inscrição</h5>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <p class="mb-4">Preencha os campos abaixo para verificar o status da sua inscrição na Ordem dos Médicos.</p>

                        <form action="{{ route('guest.registrations.show-status') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="registration_number" class="form-label">Número de Inscrição</label>
                                <input type="text" class="form-control @error('registration_number') is-invalid @enderror"
                                    id="registration_number" name="registration_number" value="{{ old('registration_number') }}" required>
                                @error('registration_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Ex: PRO-2023-ABCD</div>
                            </div>

                            <div class="mb-3">
                                <label for="identity_document_number" class="form-label">Número do Documento de Identidade</label>
                                <input type="text" class="form-control @error('identity_document_number') is-invalid @enderror"
                                    id="identity_document_number" name="identity_document_number" value="{{ old('identity_document_number') }}" required>
                                @error('identity_document_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Insira o mesmo número de documento usado na inscrição</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i> Verificar Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('guest.registrations.type') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i> Nova Inscrição
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest-registration>
