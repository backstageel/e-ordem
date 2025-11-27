<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles
{
    protected $data;

    protected $reportType;

    public function __construct($data, $reportType)
    {
        $this->data = $data;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'members':
                return [
                    'ID',
                    'Nome',
                    'Email',
                    'Telefone',
                    'Status',
                    'Data de Criação',
                ];
            case 'registrations':
                return [
                    'ID',
                    'Membro',
                    'Exame',
                    'Status',
                    'Data de Inscrição',
                    'Data de Aprovação',
                ];
            case 'payments':
                return [
                    'ID',
                    'Membro',
                    'Valor',
                    'Status',
                    'Método de Pagamento',
                    'Data de Pagamento',
                    'Data de Criação',
                ];
            case 'exams':
                return [
                    'ID',
                    'Nome',
                    'Tipo',
                    'Status',
                    'Data de Início',
                    'Data de Fim',
                    'Local',
                ];
            case 'programs':
                return [
                    'ID',
                    'Nome',
                    'Especialidade',
                    'Status',
                    'Duração (meses)',
                    'Data de Início',
                    'Data de Fim',
                ];
            case 'applications':
                return [
                    'ID',
                    'Membro',
                    'Programa',
                    'Status',
                    'Data de Candidatura',
                    'Data de Aprovação',
                ];
            case 'evaluations':
                return [
                    'ID',
                    'Candidatura',
                    'Avaliador',
                    'Nota',
                    'Comentários',
                    'Data de Avaliação',
                ];
            case 'residents':
                return [
                    'ID',
                    'Membro',
                    'Programa',
                    'Status',
                    'Data de Início',
                    'Data de Fim',
                ];
            case 'completions':
                return [
                    'ID',
                    'Residente',
                    'Programa',
                    'Data de Conclusão',
                    'Nota Final',
                    'Certificado',
                ];
            default:
                return ['ID', 'Nome', 'Data de Criação'];
        }
    }

    public function map($row): array
    {
        switch ($this->reportType) {
            case 'members':
                return [
                    $row->id,
                    $row->person->full_name ?? 'N/A',
                    $row->person->email ?? 'N/A',
                    $row->person->phone ?? 'N/A',
                    $row->status,
                    $row->created_at->format('d/m/Y H:i:s'),
                ];
            case 'registrations':
                return [
                    $row->id,
                    $row->member->person->full_name ?? 'N/A',
                    $row->exam->name ?? 'N/A',
                    $row->status,
                    $row->created_at->format('d/m/Y H:i:s'),
                    $row->approved_at ? $row->approved_at->format('d/m/Y H:i:s') : 'N/A',
                ];
            case 'payments':
                return [
                    $row->id,
                    $row->member->person->full_name ?? 'N/A',
                    number_format($row->amount, 2, ',', '.').' MT',
                    $row->status instanceof \App\Enums\PaymentStatus ? $row->status->value : $row->status,
                    $row->payment_method,
                    $row->paid_at ? $row->paid_at->format('d/m/Y H:i:s') : 'N/A',
                    $row->created_at->format('d/m/Y H:i:s'),
                ];
            case 'exams':
                return [
                    $row->id,
                    $row->name,
                    $row->type,
                    $row->status,
                    $row->start_date ? $row->start_date->format('d/m/Y H:i:s') : 'N/A',
                    $row->end_date ? $row->end_date->format('d/m/Y H:i:s') : 'N/A',
                    $row->location,
                ];
            case 'programs':
                return [
                    $row->id,
                    $row->name,
                    $row->specialty,
                    $row->status,
                    $row->duration_months,
                    $row->start_date ? $row->start_date->format('d/m/Y') : 'N/A',
                    $row->end_date ? $row->end_date->format('d/m/Y') : 'N/A',
                ];
            case 'applications':
                return [
                    $row->id,
                    $row->member->person->full_name ?? 'N/A',
                    $row->program->name ?? 'N/A',
                    $row->status,
                    $row->created_at->format('d/m/Y H:i:s'),
                    $row->approved_at ? $row->approved_at->format('d/m/Y H:i:s') : 'N/A',
                ];
            case 'evaluations':
                return [
                    $row->id,
                    $row->application->id ?? 'N/A',
                    $row->evaluator_name ?? 'N/A',
                    $row->score ?? 'N/A',
                    $row->comments ?? 'N/A',
                    $row->evaluation_date ? $row->evaluation_date->format('d/m/Y H:i:s') : 'N/A',
                ];
            case 'residents':
                return [
                    $row->id,
                    $row->member->person->full_name ?? 'N/A',
                    $row->program->name ?? 'N/A',
                    $row->status,
                    $row->start_date ? $row->start_date->format('d/m/Y') : 'N/A',
                    $row->end_date ? $row->end_date->format('d/m/Y') : 'N/A',
                ];
            case 'completions':
                return [
                    $row->id,
                    $row->resident->member->person->full_name ?? 'N/A',
                    $row->program->name ?? 'N/A',
                    $row->completion_date ? $row->completion_date->format('d/m/Y') : 'N/A',
                    $row->final_score ?? 'N/A',
                    $row->certificate_generated ? 'Sim' : 'Não',
                ];
            default:
                return [
                    $row->id ?? 'N/A',
                    $row->name ?? $row->title ?? 'N/A',
                    $row->created_at ? $row->created_at->format('d/m/Y H:i:s') : 'N/A',
                ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 25,
            'C' => 20,
            'D' => 15,
            'E' => 20,
            'F' => 20,
            'G' => 20,
        ];
    }
}
