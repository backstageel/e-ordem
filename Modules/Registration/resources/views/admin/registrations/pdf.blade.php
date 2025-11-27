<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 20px; margin: 0 0 10px; }
        h2 { font-size: 16px; margin: 20px 0 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px; }
        .row { display: flex; }
        .col { flex: 1; padding-right: 10px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 6px; }
        .muted { color: #666; }
    </style>
    <title>Inscrição {{ $registration->registration_number }}</title>
    </head>
<body>
    <h1>Inscrição {{ $registration->registration_number }}</h1>
    <div class="muted">Tipo: {{ $registration->registrationType->name }} | Submetida em {{ optional($registration->submission_date)->format('d/m/Y') }}</div>

    <h2>Dados do Candidato</h2>
    <div class="row">
        <div class="col">
            <strong>Nome</strong><br>
            {{ $registration->person->full_name }}
        </div>
        <div class="col">
            <strong>Email</strong><br>
            {{ $registration->person->email }}
        </div>
        <div class="col">
            <strong>Telefone</strong><br>
            {{ $registration->person->phone }}
        </div>
    </div>

    <h2>Identificação & Morada</h2>
    <table class="table">
        <tr>
            <th>Nº Documento</th><td>{{ $registration->person->identity_document_number }}</td>
            <th>Morada</th><td>{{ $registration->person->living_address }}</td>
        </tr>
    </table>

    <h2>Dados Académicos & Profissionais</h2>
    <table class="table">
        <tr>
            <th>Universidade</th><td>{{ optional($registration->currentAcademicQualification)->university ?? '—' }}</td>
            <th>Grau</th><td>{{ optional($registration->currentAcademicQualification)->degree ?? '—' }}</td>
        </tr>
        <tr>
            <th>Instituição Atual</th><td>{{ optional($registration->currentWorkExperience)->institution ?? '—' }}</td>
            <th>Categoria</th><td>{{ optional($registration->currentWorkExperience)->position ?? '—' }}</td>
        </tr>
    </table>

    <h2>Documentos Entregues</h2>
    <table class="table">
        <thead><tr><th>Tipo</th><th>Estado</th><th>Data</th></tr></thead>
        <tbody>
        @foreach($registration->person->documents as $doc)
            <tr>
                <td>{{ $doc->documentType->name }}</td>
                <td>{{ ucfirst($doc->status) }}</td>
                <td>{{ optional($doc->submission_date ?? $doc->created_at)->format('d/m/Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>Pagamento</h2>
    <table class="table">
        <tr>
            <th>Valor</th><td>{{ number_format($registration->registrationType->fee, 2) }} MT</td>
            <th>Status</th><td>{{ $registration->is_paid ? 'Pago' : 'Pendente' }}</td>
        </tr>
        @if($registration->is_paid)
        <tr>
            <th>Data</th><td>{{ optional($registration->payment_date)->format('d/m/Y') }}</td>
            <th>Referência</th><td>{{ optional($registration->payments->first())->reference_number }}</td>
        </tr>
        @endif
    </table>
</body>
</html>

