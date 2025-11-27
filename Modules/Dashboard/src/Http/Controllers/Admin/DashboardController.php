<?php

namespace Modules\Dashboard\Http\Controllers\Admin;

use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\MedicalSpeciality;
use App\Models\Member;
use App\Models\Payment;
use Modules\Registration\Models\Registration;
use App\Models\ResidencyApplication;
use App\Models\SystemConfig;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $statistics = $this->getStatistics();
        $paymentStatistics = $this->getPaymentStatistics();
        $recentActivities = $this->getRecentActivities();
        $registrationChartData = $this->getRegistrationChartData();
        $membersBySpecialityChartData = $this->getMembersBySpecialityChartData();
        $systemAlerts = $this->getSystemAlerts();

        return view('dashboard::admin.index', array_merge(
            $statistics,
            $paymentStatistics,
            $recentActivities,
            $registrationChartData,
            ['members_by_speciality' => $membersBySpecialityChartData],
            ['system_alerts' => $systemAlerts]
        ));
    }

    /**
     * Get dashboard statistics.
     */
    private function getStatistics(): array
    {
        $totalMembers = Member::count();

        // Inscrições Pendentes: status diferente de approved e rejected
        $pendingStatuses = RegistrationStatus::getPendingStatuses();
        $totalPendingRegistrations = Registration::whereIn('status', array_map(fn ($status) => $status->value, $pendingStatuses))->count();

        // Residentes Activos: status approved ou in_progress
        $totalActiveResidents = ResidencyApplication::whereIn('status', ['approved', 'in_progress'])->count();

        // Exames Abertos: status scheduled ou in_progress
        $totalOpenExams = Exam::whereIn('status', ['scheduled', 'in_progress'])->count();

        $totalUsers = User::count();

        // Calculate growth percentages (simplified - you can enhance this)
        $membersGrowth = $this->calculateGrowth(Member::class, 'created_at');
        $registrationsGrowth = $this->calculateGrowth(Registration::class, 'created_at');
        $residentsGrowth = $this->calculateGrowth(ResidencyApplication::class, 'created_at');
        $examsGrowth = $this->calculateGrowth(Exam::class, 'created_at');

        return [
            'total_doctors' => $totalMembers, // Mantido para compatibilidade
            'total_members' => $totalMembers,
            'total_registrations' => $totalPendingRegistrations,
            'total_residents' => $totalActiveResidents,
            'total_users' => $totalUsers,
            'total_exams' => $totalOpenExams,
            'doctors_growth' => $membersGrowth, // Mantido para compatibilidade
            'members_growth' => $membersGrowth,
            'registrations_growth' => $registrationsGrowth,
            'residents_growth' => $residentsGrowth,
            'exams_growth' => $examsGrowth,
        ];
    }

    /**
     * Get payment statistics.
     */
    private function getPaymentStatistics(): array
    {
        // Pagamentos Recebidos Este Ano
        $paymentsReceived = Payment::where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $paymentsPending = Payment::where('status', 'pending')
            ->sum('amount');

        $paymentsOverdue = Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->sum('amount');

        $paymentsGrowth = $this->calculatePaymentGrowth();

        return [
            'payments_received' => $paymentsReceived,
            'payments_pending' => $paymentsPending,
            'payments_overdue' => $paymentsOverdue,
            'payments_growth' => $paymentsGrowth,
        ];
    }

    /**
     * Get recent activities.
     */
    private function getRecentActivities(): array
    {
        $recentRegistrations = Registration::with(['person', 'registrationType'])
            ->latest()
            ->limit(5)
            ->get();

        // Especialidades Populares: contando membros únicos para cada especialidade
        // Usar selectRaw para contar membros únicos diretamente na query
        $popularSpecialties = MedicalSpeciality::selectRaw('medical_specialities.*, COUNT(DISTINCT medical_speciality_member.member_id) as members_count')
            ->join('medical_speciality_member', 'medical_specialities.id', '=', 'medical_speciality_member.medical_speciality_id')
            ->groupBy('medical_specialities.id')
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();

        return [
            'recent_registrations' => $recentRegistrations,
            'popular_specialties' => $popularSpecialties,
        ];
    }

    /**
     * Get registration chart data.
     */
    private function getRegistrationChartData(): array
    {
        $months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $provisionalData = [];
        $effectiveData = [];

        for ($i = 1; $i <= 12; $i++) {
            $provisionalData[] = Registration::provisional()
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();

            $effectiveData[] = Registration::effective()
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        return [
            'months' => $months,
            'provisional_data' => $provisionalData,
            'effective_data' => $effectiveData,
        ];
    }

    /**
     * Get members by speciality chart data.
     */
    private function getMembersBySpecialityChartData(): array
    {
        // Contar membros únicos para cada especialidade
        // Usar selectRaw para contar membros únicos diretamente na query
        $specialities = MedicalSpeciality::selectRaw('medical_specialities.*, COUNT(DISTINCT medical_speciality_member.member_id) as members_count')
            ->join('medical_speciality_member', 'medical_specialities.id', '=', 'medical_speciality_member.medical_speciality_id')
            ->groupBy('medical_specialities.id')
            ->orderByDesc('members_count')
            ->limit(10)
            ->get();

        $data = [];
        foreach ($specialities as $speciality) {
            $data[$speciality->name] = $speciality->members_count;
        }

        return $data;
    }

    /**
     * Get system alerts.
     */
    private function getSystemAlerts(): array
    {
        $alerts = [];

        // Check for overdue payments
        $overduePayments = Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();

        if ($overduePayments > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Pagamentos Vencidos',
                'message' => "Existem {$overduePayments} pagamentos vencidos que precisam de atenção.",
                'icon' => 'fas fa-exclamation-triangle',
            ];
        }

        // Check for pending registrations
        $pendingRegistrations = Registration::where('status', 'pending')->count();
        if ($pendingRegistrations > 10) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Inscrições Pendentes',
                'message' => "Existem {$pendingRegistrations} inscrições pendentes de revisão.",
                'icon' => 'fas fa-clock',
            ];
        }

        // Check for system maintenance mode
        if (SystemConfig::where('key', 'maintenance_mode')->value('value') === 'true') {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Modo de Manutenção Ativo',
                'message' => 'O sistema está em modo de manutenção.',
                'icon' => 'fas fa-tools',
            ];
        }

        return $alerts;
    }

    /**
     * Calculate growth percentage for a model.
     */
    private function calculateGrowth(string $model, string $dateColumn): float
    {
        $currentMonth = $model::whereMonth($dateColumn, now()->month)
            ->whereYear($dateColumn, now()->year)
            ->count();

        $lastMonth = $model::whereMonth($dateColumn, now()->subMonth()->month)
            ->whereYear($dateColumn, now()->subMonth()->year)
            ->count();

        if ($lastMonth === 0) {
            return $currentMonth > 0 ? 100.0 : 0.0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Calculate payment growth.
     */
    private function calculatePaymentGrowth(): float
    {
        $currentMonth = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $lastMonth = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100.0 : 0.0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }
}
