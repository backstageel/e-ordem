<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DocumentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /** @var \Illuminate\Support\Collection<int, \App\Models\Document> */
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
            'ID',
            'Tipo de Documento',
            'Proprietário',
            'Email',
            'Número de Inscrição',
            'Nome do Arquivo',
            'Status',
            'Data de Submissão',
            'Data de Validação',
            'Data de Expiração',
            'Validado Por',
            'Tamanho (KB)',
            'Tem Tradução',
            'Notas',
            'Motivo de Rejeição',
            'Data de Criação',
        ];
    }

    public function map($document): array
    {
        $person = $document->person
            ?? $document->registration?->person
            ?? $document->member?->person;

        return [
            $document->id,
            $document->documentType?->name ?? '-',
            $person?->full_name ?? '-',
            $person?->email ?? '-',
            $document->registration?->registration_number ?? $document->member?->registration_number ?? '-',
            $document->original_filename ?? '-',
            $document->status->label(),
            optional($document->submission_date)->format('d/m/Y'),
            optional($document->validation_date)->format('d/m/Y'),
            optional($document->expiry_date)->format('d/m/Y'),
            $document->validatedBy?->name ?? '-',
            $document->file_size ? round($document->file_size / 1024, 2) : '-',
            $document->has_translation ? 'Sim' : 'Não',
            $document->notes ?? '-',
            $document->rejection_reason ?? '-',
            optional($document->created_at)->format('d/m/Y H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
