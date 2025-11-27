<?php

namespace App\Exports\Exam;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExamResultsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        public Exam $exam
    ) {}

    public function collection()
    {
        return $this->exam->results()
            ->with(['application.user.person'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nome Completo',
            'Nota',
            'Status',
            'Decisão',
            'Avaliado Por',
            'Data de Avaliação',
            'Observações',
        ];
    }

    public function map($result): array
    {
        $person = $result->application->user->person ?? null;

        return [
            $result->id,
            $person ? $person->full_name : 'N/A',
            $result->grade ?? 'N/A',
            ucfirst($result->status),
            ucfirst($result->decision ?? 'Pendente'),
            $result->evaluator?->name ?? 'N/A',
            $result->evaluated_at?->format('d/m/Y H:i') ?? 'N/A',
            $result->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
