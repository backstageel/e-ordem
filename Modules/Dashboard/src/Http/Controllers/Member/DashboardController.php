<?php

namespace Modules\Dashboard\Http\Controllers\Member;

use App\Enums\PaymentStatus;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\Member;
use App\Models\Payment;
use Modules\Registration\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the member dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $person = $user->person;
        $member = $person ? $person->member : null;

        if (!$member) {
            return view('dashboard::member.index', [
                'member' => null,
                'statistics' => [],
                'recentActivities' => [],
                'chartData' => [],
            ]);
        }

        $statistics = $this->getStatistics($member);
        $recentActivities = $this->getRecentActivities($member);
        $chartData = $this->getRegistrationChartData($member);
        $paymentStatistics = $this->getPaymentStatistics($member);

        return view('dashboard::member.index', array_merge(
            $statistics,
            $recentActivities,
            $chartData,
            $paymentStatistics
        ));
    }

    /**
     * Get dashboard statistics for the member.
     */
    private function getStatistics($member): array
    {
        // Minhas Inscrições
        $myRegistrations = $member->registrations()->count();
        $pendingRegistrations = $member->registrations()
            ->whereIn('status', array_map(fn ($status) => $status->value, RegistrationStatus::getPendingStatuses()))
            ->count();
        $approvedRegistrations = $member->registrations()
            ->where('status', RegistrationStatus::APPROVED->value)
            ->count();

        // Meus Documentos
        $myDocuments = $member->documents()->count();
        $pendingDocuments = $member->documents()
            ->where('status', \App\Enums\DocumentStatus::PENDING->value)
            ->count();

        // Meus Exames
        $user = Auth::user();
        $myExams = ExamApplication::where('user_id', $user->id)->count();
        $scheduledExams = ExamApplication::where('user_id', $user->id)
            ->whereIn('status', ['scheduled', 'in_progress', 'approved'])
            ->count();

        // Minhas Quotas
        $myQuotas = $member->quotaHistory()->count();
        $overdueQuotas = $member->overdueQuotas()->count();

        // Calculate growth percentages for the last 7 days
        $registrationsGrowth = $this->calculateGrowth($member->registrations(), 'created_at');
        $documentsGrowth = $this->calculateGrowth($member->documents(), 'created_at');
        $user = Auth::user();
        $examsQuery = ExamApplication::where('user_id', $user->id);
        $examsGrowth = $this->calculateGrowth($examsQuery, 'created_at');

        // Chart data for sparklines
        $registrationsChartData = $this->getSparklineChartData($member->registrations(), 'created_at');
        $documentsChartData = $this->getSparklineChartData($member->documents(), 'created_at');
        $examsChartData = $this->getSparklineChartData($examsQuery, 'created_at');

        return [
            'total_my_registrations' => $myRegistrations,
            'total_pending_registrations' => $pendingRegistrations,
            'total_approved_registrations' => $approvedRegistrations,
            'total_my_documents' => $myDocuments,
            'total_pending_documents' => $pendingDocuments,
            'total_my_exams' => $myExams,
            'total_scheduled_exams' => $scheduledExams,
            'total_my_quotas' => $myQuotas,
            'total_overdue_quotas' => $overdueQuotas,
            'registrations_growth' => $registrationsGrowth,
            'documents_growth' => $documentsGrowth,
            'exams_growth' => $examsGrowth,
            'registrations_chart_data' => $registrationsChartData,
            'documents_chart_data' => $documentsChartData,
            'exams_chart_data' => $examsChartData,
        ];
    }

    /**
     * Get payment statistics for the member.
     */
    private function getPaymentStatistics($member): array
    {
        // Pagamentos Recebidos nos últimos 7 dias
        $paymentsReceivedLast7Days = $member->payments()
            ->where('status', PaymentStatus::COMPLETED->value)
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('amount');

        $paymentsPending = $member->payments()
            ->where('status', PaymentStatus::PENDING->value)
            ->sum('amount');

        $paymentsOverdue = $member->payments()
            ->where('status', PaymentStatus::PENDING->value)
            ->where('due_date', '<', now())
            ->sum('amount');

        $paymentsGrowth = $this->calculatePaymentGrowth($member);

        // Counts
        $totalPaymentsCompleted = $member->payments()
            ->where('status', PaymentStatus::COMPLETED->value)
            ->count();
        $totalPaymentsPendingCount = $member->payments()
            ->where('status', PaymentStatus::PENDING->value)
            ->count();
        $totalPaymentsOverdueCount = $member->payments()
            ->where('status', PaymentStatus::PENDING->value)
            ->where('due_date', '<', now())
            ->count();

        // Chart data for sparkline
        $paymentsChartData = $this->getSparklineChartData(
            $member->payments()->where('status', PaymentStatus::COMPLETED->value),
            'created_at',
            'amount'
        );

        return [
            'payments_received_last_7_days' => $paymentsReceivedLast7Days,
            'payments_pending' => $paymentsPending,
            'payments_overdue' => $paymentsOverdue,
            'payments_growth' => $paymentsGrowth,
            'total_payments_completed' => $totalPaymentsCompleted,
            'total_payments_pending_count' => $totalPaymentsPendingCount,
            'total_payments_overdue_count' => $totalPaymentsOverdueCount,
            'payments_chart_data' => $paymentsChartData,
        ];
    }

    /**
     * Get recent activities for the member.
     */
    private function getRecentActivities($member): array
    {
        $recentRegistrations = $member->registrations()
            ->with(['registrationType'])
            ->latest()
            ->limit(5)
            ->get();

        $recentPayments = $member->payments()
            ->with('paymentType')
            ->latest()
            ->limit(5)
            ->get();

        $recentDocuments = $member->documents()
            ->latest()
            ->limit(5)
            ->get();

        return [
            'recent_registrations' => $recentRegistrations,
            'recent_payments' => $recentPayments,
            'recent_documents' => $recentDocuments,
        ];
    }

    /**
     * Get registration chart data for the member.
     */
    private function getRegistrationChartData($member): array
    {
        $months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $approvedData = [];
        $pendingData = [];
        $rejectedData = [];

        for ($i = 1; $i <= 12; $i++) {
            $approvedData[] = $member->registrations()
                ->where('status', RegistrationStatus::APPROVED->value)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();

            $pendingStatuses = RegistrationStatus::getPendingStatuses();
            $pendingData[] = $member->registrations()
                ->whereIn('status', array_map(fn ($status) => $status->value, $pendingStatuses))
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();

            $rejectedData[] = $member->registrations()
                ->where('status', RegistrationStatus::REJECTED->value)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        return [
            'months' => $months,
            'approved_data' => $approvedData,
            'pending_data' => $pendingData,
            'rejected_data' => $rejectedData,
        ];
    }

    /**
     * Calculate growth percentage for a query (last 7 days vs previous 7 days).
     */
    private function calculateGrowth($query, string $dateColumn): float
    {
        // Handle both query builder and relationship
        if (is_object($query) && method_exists($query, 'getQuery')) {
            // It's a relationship or query builder
            $last7Days = (clone $query)->where($dateColumn, '>=', now()->subDays(7))->count();
            $previous7Days = (clone $query)
                ->where($dateColumn, '>=', now()->subDays(14))
                ->where($dateColumn, '<', now()->subDays(7))
                ->count();
        } else {
            // It's already a query builder instance
            $last7Days = $query->where($dateColumn, '>=', now()->subDays(7))->count();
            $previous7Days = $query
                ->where($dateColumn, '>=', now()->subDays(14))
                ->where($dateColumn, '<', now()->subDays(7))
                ->count();
        }

        if ($previous7Days == 0) {
            return $last7Days > 0 ? 100 : 0;
        }

        return (($last7Days - $previous7Days) / $previous7Days) * 100;
    }

    /**
     * Calculate payment growth percentage.
     */
    private function calculatePaymentGrowth($member): float
    {
        $last7Days = $member->payments()
            ->where('status', PaymentStatus::COMPLETED->value)
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('amount');

        $previous7Days = $member->payments()
            ->where('status', PaymentStatus::COMPLETED->value)
            ->where('created_at', '>=', now()->subDays(14))
            ->where('created_at', '<', now()->subDays(7))
            ->sum('amount');

        if ($previous7Days == 0) {
            return $last7Days > 0 ? 100 : 0;
        }

        return (($last7Days - $previous7Days) / $previous7Days) * 100;
    }

    /**
     * Get sparkline chart data.
     */
    private function getSparklineChartData($query, string $dateColumn, ?string $sumColumn = null): array
    {
        // Handle both query builder and relationship
        if (is_object($query) && method_exists($query, 'getQuery')) {
            // It's a relationship or query builder
            $last7Days = $sumColumn
                ? (clone $query)->where($dateColumn, '>=', now()->subDays(7))->sum($sumColumn)
                : (clone $query)->where($dateColumn, '>=', now()->subDays(7))->count();

            $currentDay = $sumColumn
                ? (clone $query)->whereDate($dateColumn, now())->sum($sumColumn)
                : (clone $query)->whereDate($dateColumn, now())->count();
        } else {
            // It's already a query builder instance
            $last7Days = $sumColumn
                ? $query->where($dateColumn, '>=', now()->subDays(7))->sum($sumColumn)
                : $query->where($dateColumn, '>=', now()->subDays(7))->count();

            $currentDay = $sumColumn
                ? $query->whereDate($dateColumn, now())->sum($sumColumn)
                : $query->whereDate($dateColumn, now())->count();
        }

        return [
            'last_7_days' => $last7Days ?? 0,
            'current_day' => $currentDay ?? 0,
        ];
    }
}
