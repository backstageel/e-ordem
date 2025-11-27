<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /** @var \Illuminate\Support\Collection<int, \Modules\Registration\Models\Registration> */
    protected Collection $rows;

    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            // Registration core
            'Nº Inscrição', 'Tipo', 'Categoria', 'Status', 'Data Submissão', 'Data Aprovação',
            // Payment snapshot
            'Pago?', 'Valor Pagamento', 'Data Pagamento', 'Referência Pagamento',
            // Candidate (Person)
            'Primeiro Nome', 'Nomes do Meio', 'Apelido', 'Nome Completo', 'Email', 'Telefone',
            'Data Nascimento', 'Género ID', 'Nacionalidade ID',
            'Doc Identidade Nº', 'Doc Emissão Local', 'Doc Emissão Data', 'Doc Validade',
            // Address (residência)
            'Endereço', 'País Residência ID', 'Província Residência ID', 'Distrito Residência ID', 'Bairro Residência',
            // Registration details
            'Categoria Profissional', 'Especialidade', 'Sub‑Especialidade', 'Local de Trabalho', 'Grau Académico',
            'Entidade Convidante', 'Local da Atividade', 'Início', 'Fim', 'Descrição Atividade', 'Notas',
            // Documents and type info
            'Documentos Submetidos', 'Documentos em Falta', 'Taxa Tipo (fee)', 'Código Tipo Pagamento'
        ];
    }

    public function map($registration): array
    {
        $type = $registration->registrationType;
        $person = $registration->person;
        $latestPayment = $registration->payments->sortByDesc('id')->first();
        $docsCount = method_exists($registration, 'documents') && $registration->relationLoaded('documents') ? $registration->documents->count() : null;
        $missingDocs = $registration->additional_documents_required ?? [];

        return [
            // Registration core
            $registration->registration_number,
            $type->name ?? '-',
            ($type->category?->label() ?? (is_string($type->category ?? null) ? $type->category : '-')),
            (method_exists($registration, 'getStatusLabel') ? $registration->getStatusLabel() : (string) $registration->status),
            optional($registration->submission_date)->format('d/m/Y'),
            optional($registration->approval_date)->format('d/m/Y'),
            // Payment
            $registration->is_paid ? 'Sim' : 'Não',
            $registration->payment_amount ?? ($type->fee ?? null),
            optional($registration->payment_date)->format('d/m/Y'),
            $latestPayment->reference_number ?? null,
            // Candidate
            $person->first_name ?? '-',
            $person->middle_name ?? '-',
            $person->last_name ?? '-',
            $person->full_name ?? '-',
            $person->email ?? '-',
            $person->phone ?? '-',
            optional($person->birth_date)->format('d/m/Y'),
            $person->gender_id ?? null,
            $person->nationality_id ?? null,
            $person->identity_document_number ?? '-',
            $person->identity_document_issue_place ?? '-',
            optional($person->identity_document_issue_date)->format('d/m/Y'),
            optional($person->identity_document_expiry_date)->format('d/m/Y'),
            // Address
            $person->living_address ?? '-',
            $person->living_country_id ?? null,
            $person->living_province_id ?? null,
            $person->living_district_id ?? null,
            $person->living_neighborhood_id ?? null,
            // Registration details
            $registration->professional_category ?? '-',
            $registration->specialty ?? '-',
            $registration->sub_specialty ?? '-',
            $registration->workplace ?? '-',
            $registration->academic_degree ?? '-',
            $registration->inviting_entity ?? '-',
            $registration->activity_location ?? '-',
            optional($registration->start_date)->format('d/m/Y'),
            optional($registration->end_date)->format('d/m/Y'),
            $registration->activity_description ?? '-',
            $registration->notes ?? '-',
            // Documents and type info
            $docsCount,
            is_array($missingDocs) ? implode(', ', $missingDocs) : (string) $missingDocs,
            $type->fee ?? null,
            $type->payment_type_code ?? null,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}


