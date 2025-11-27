<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalSpeciality;
use Illuminate\View\View;

class MedicalSpecialityController extends Controller
{
    /**
     * Display a listing of medical specialities.
     */
    public function index(): View
    {
        // Contar membros únicos para cada especialidade
        // Usar selectRaw para contar membros únicos diretamente na query
        $specialities = MedicalSpeciality::selectRaw('medical_specialities.*, COUNT(DISTINCT medical_speciality_member.member_id) as members_count')
            ->leftJoin('medical_speciality_member', 'medical_specialities.id', '=', 'medical_speciality_member.medical_speciality_id')
            ->groupBy('medical_specialities.id')
            ->orderBy('medical_specialities.name')
            ->paginate(20);

        return view('admin.medical-specialities.index', compact('specialities'));
    }
}
