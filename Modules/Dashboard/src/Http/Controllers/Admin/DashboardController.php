<?php

namespace Modules\Dashboard\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\MedicalSpeciality;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentType;
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
        $sparklineChartData = $this->getSparklineChartData();
        $systemAlerts = $this->getSystemAlerts();


        return view('dashboard::admin.index', array_merge(
            $statistics,
            $paymentStatistics,
            $recentActivities,
            $registrationChartData,
            $sparklineChartData,
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

        // Total de inscrições por status
        $totalAllRegistrations = Registration::count();
        $totalRejectedRegistrations = Registration::where('status', RegistrationStatus::REJECTED)->count();
        $totalUnderReviewRegistrations = Registration::where('status', RegistrationStatus::UNDER_REVIEW)->count();
        $totalApprovedRegistrations = Registration::where('status', RegistrationStatus::APPROVED)->count();

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
            'total_all_registrations' => $totalAllRegistrations,
            'total_rejected_registrations' => $totalRejectedRegistrations,
            'total_under_review_registrations' => $totalUnderReviewRegistrations,
            'total_approved_registrations' => $totalApprovedRegistrations,
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
        $paymentsReceived = Payment::where('status', PaymentStatus::COMPLETED->value)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $paymentsPending = Payment::where('status', PaymentStatus::PENDING->value)
            ->sum('amount');

        $paymentsOverdue = Payment::where('status', PaymentStatus::PENDING->value)
            ->where('due_date', '<', now())
            ->sum('amount');

        // Payment counts
        $paymentsCompletedCount = Payment::where('status', PaymentStatus::COMPLETED->value)->count();
        $paymentsPendingCount = Payment::where('status', PaymentStatus::PENDING->value)->count();
        $paymentsOverdueCount = Payment::where('status', PaymentStatus::PENDING->value)
            ->where('due_date', '<', now())
            ->count();

        // Payments by type - using payment_type.code instead of type column
        // Map payment type codes to our categories
        $paymentTypeMapping = [
            'registration' => ['enrollment_fee', 'processing_fee_provisional_foreign', 'provisional_authorization_3m', 'provisional_authorization_6m', 'provisional_authorization_other'],
            'quota' => ['annual_quota', 'quota_late_penalty'],
            'exam' => ['exam_application'],
            'card' => ['card_issue_initial', 'card_renewal', 'professional_id_card'],
        ];

        $paymentsByType = [];
        foreach ($paymentTypeMapping as $category => $codes) {
            $paymentTypeIds = PaymentType::whereIn('code', $codes)->pluck('id');

            $paymentsByType[$category] = [
                'amount' => Payment::whereIn('payment_type_id', $paymentTypeIds)
                    ->where('status', PaymentStatus::COMPLETED->value)
                    ->sum('amount'),
                'count' => Payment::whereIn('payment_type_id', $paymentTypeIds)
                    ->where('status', PaymentStatus::COMPLETED->value)
                    ->count(),
            ];
        }

        // Recent payments
        $recentPayments = Payment::with('paymentType')
            ->latest()
            ->limit(5)
            ->get();

        $paymentsGrowth = $this->calculatePaymentGrowth();

        return [
            'payments_received' => $paymentsReceived,
            'payments_pending' => $paymentsPending,
            'payments_overdue' => $paymentsOverdue,
            'payments_completed_count' => $paymentsCompletedCount,
            'payments_pending_count' => $paymentsPendingCount,
            'payments_overdue_count' => $paymentsOverdueCount,
            'payments_by_type' => $paymentsByType,
            'recent_payments' => $recentPayments,
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
        
        // Initialize data arrays with zeros
        $provisionalData = array_fill(0, 12, 0);
        $effectiveData = array_fill(0, 12, 0);
        $approvedData = array_fill(0, 12, 0);
        $pendingData = array_fill(0, 12, 0);
        $rejectedData = array_fill(0, 12, 0);
        $underReviewData = array_fill(0, 12, 0);

        // Get provisional registrations grouped by month
        $provisionalStats = Registration::provisional()
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, count(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get();

        foreach ($provisionalStats as $stat) {
            $provisionalData[(int)$stat->month - 1] = $stat->count;
        }

        // Get effective registrations grouped by month
        $effectiveStats = Registration::effective()
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, count(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get();

        foreach ($effectiveStats as $stat) {
            $effectiveData[(int)$stat->month - 1] = $stat->count;
        }

        // Get status statistics grouped by month and status
        $statusStats = Registration::selectRaw('EXTRACT(MONTH FROM created_at) as month, status, count(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month', 'status')
            ->get();

        $pendingStatuses = RegistrationStatus::getPendingStatuses();
        $pendingStatusValues = array_map(fn ($status) => $status->value, $pendingStatuses);

        foreach ($statusStats as $stat) {
            $monthIndex = (int)$stat->month - 1;
            $statusValue = $stat->status instanceof RegistrationStatus ? $stat->status->value : $stat->status;

            if ($statusValue === RegistrationStatus::APPROVED->value) {
                $approvedData[$monthIndex] += $stat->count;
            } elseif (in_array($statusValue, $pendingStatusValues)) {
                $pendingData[$monthIndex] += $stat->count;
            } elseif ($statusValue === RegistrationStatus::REJECTED->value) {
                $rejectedData[$monthIndex] += $stat->count;
            } elseif ($statusValue === RegistrationStatus::UNDER_REVIEW->value) {
                $underReviewData[$monthIndex] += $stat->count;
            }
        }

        return [
            'months' => $months,
            'provisional_data' => $provisionalData,
            'effective_data' => $effectiveData,
            'approved_data' => $approvedData,
            'pending_data' => $pendingData,
            'rejected_data' => $rejectedData,
            'under_review_data' => $underReviewData,
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
        $overduePayments = Payment::where('status', PaymentStatus::PENDING->value)
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
     * Calculate growth percentage for a model (last 7 days vs previous 7 days).
     */
    private function calculateGrowth(string $model, string $dateColumn): float
    {
        $last7Days = $model::where($dateColumn, '>=', now()->subDays(7))
            ->where($dateColumn, '<', now())
            ->count();

        $previous7Days = $model::where($dateColumn, '>=', now()->subDays(14))
            ->where($dateColumn, '<', now()->subDays(7))
            ->count();

        if ($previous7Days === 0) {
            return $last7Days > 0 ? 100.0 : 0.0;
        }

        return round((($last7Days - $previous7Days) / $previous7Days) * 100, 1);
    }

    /**
     * Calculate payment growth.
     */
    private function calculatePaymentGrowth(): float
    {
        $currentMonth = Payment::where('status', PaymentStatus::COMPLETED->value)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $lastMonth = Payment::where('status', PaymentStatus::COMPLETED->value)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100.0 : 0.0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Get sparkline chart data for stat cards.
     */
    private function getSparklineChartData(): array
    {
        // Members chart data (last 7 days vs current day)
        $membersLast7Days = Member::where('created_at', '>=', now()->subDays(7))
            ->where('created_at', '<', now())
            ->count();
        $membersCurrentDay = Member::where('created_at', '>=', now()->startOfDay())
            ->count();

        // Registrations chart data
        $registrationsLast7Days = Registration::where('created_at', '>=', now()->subDays(7))
            ->where('created_at', '<', now())
            ->count();
        $registrationsCurrentDay = Registration::where('created_at', '>=', now()->startOfDay())
            ->count();

        // Exams chart data
        $examsLast7Days = Exam::where('created_at', '>=', now()->subDays(7))
            ->where('created_at', '<', now())
            ->count();
        $examsCurrentDay = Exam::where('created_at', '>=', now()->startOfDay())
            ->count();

        // Payments chart data (amount in thousands)
        $paymentsLast7Days = Payment::where('status', PaymentStatus::COMPLETED->value)
            ->where('created_at', '>=', now()->subDays(7))
            ->where('created_at', '<', now())
            ->sum('amount') / 1000; // Convert to thousands
        $paymentsCurrentDay = Payment::where('status', PaymentStatus::COMPLETED->value)
            ->where('created_at', '>=', now()->startOfDay())
            ->sum('amount') / 1000; // Convert to thousands

        return [
            'members_chart_data' => [
                'last_7_days' => $membersLast7Days,
                'current_day' => $membersCurrentDay,
            ],
            'registrations_chart_data' => [
                'last_7_days' => $registrationsLast7Days,
                'current_day' => $registrationsCurrentDay,
            ],
            'exams_chart_data' => [
                'last_7_days' => $examsLast7Days,
                'current_day' => $examsCurrentDay,
            ],
            'payments_chart_data' => [
                'last_7_days' => round($paymentsLast7Days, 2),
                'current_day' => round($paymentsCurrentDay, 2),
            ],
        ];
    }
}
