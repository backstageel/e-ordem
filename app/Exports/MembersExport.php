<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /** @var \Illuminate\Support\Collection<int, \App\Models\Member> */
    protected Collection $members;

    public function __construct(Collection $members)
    {
        $this->members = $members;
    }

    public function collection(): Collection
    {
        return $this->members;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Número de Membro',
            'Número de Inscrição',
            'Nome Completo',
            'Email',
            'Telefone',
            'Telemóvel',
            'Data de Nascimento',
            'Nacionalidade',
            'Província (Residência)',
            'Especialidade Médica',
            'Status',
            'Data de Registro',
            'Última Atualização',
        ];
    }

    public function map($member): array
    {
        return [
            $member->id,
            $member->member_number ?? 'N/A',
            $member->registration_number ?? 'N/A',
            $member->full_name ?? 'N/A',
            $member->person->email ?? 'N/A',
            $member->person->phone ?? 'N/A',
            $member->person->mobile ?? 'N/A',
            $member->person->birth_date ? $member->person->birth_date->format('d/m/Y') : 'N/A',
            $member->person->nationality->name ?? 'N/A',
            $member->person->livingProvince->name ?? 'N/A',
            $member->medicalSpecialities->isNotEmpty()
                ? $member->medicalSpecialities->where('pivot.is_primary', true)->first()?->name
                    ?? $member->medicalSpecialities->first()->name
                    ?? 'N/A'
                : ($member->specialty ?? 'N/A'),
            ucfirst($member->status),
            $member->created_at->format('d/m/Y H:i'),
            $member->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
