<?php

namespace App\Exports\Exam;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExamApplicationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        public Exam $exam
    ) {}

    public function collection()
    {
        return $this->exam->applications()
            ->with(['user.person', 'result', 'schedule'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nome Completo',
            'Email',
            'Telefone',
            'Tipo de Exame',
            'Especialidade',
            'Status',
            'Data de Submissão',
            'Data Agendada',
            'Local',
            'Nota',
            'Decisão',
        ];
    }

    public function map($application): array
    {
        $person = $application->user->person ?? null;
        $schedule = $application->schedule;

        return [
            $application->id,
            $person ? $person->full_name : 'N/A',
            $person?->email ?? 'N/A',
            $person?->phone ?? 'N/A',
            ucfirst($application->exam_type),
            $application->specialty,
            ucfirst($application->status),
            $application->created_at->format('d/m/Y H:i'),
            $schedule?->date->format('d/m/Y') ?? 'N/A',
            $schedule?->location ?? 'N/A',
            $application->result?->grade ?? 'N/A',
            ucfirst($application->result?->decision ?? 'Pendente'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
