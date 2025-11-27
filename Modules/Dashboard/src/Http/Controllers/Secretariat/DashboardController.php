<?php

namespace Modules\Dashboard\Http\Controllers\Secretariat;

use App\Enums\PaymentStatus;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Payment;
use Modules\Registration\Models\Registration;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the secretariat dashboard.
     */
    public function index(): View
    {
        $statistics = $this->getStatistics();
        $recentActivities = $this->getRecentActivities();
        $chartData = $this->getRegistrationChartData();
        $documentStatistics = $this->getDocumentStatistics();

        return view('dashboard::secretariat.index', array_merge(
            $statistics,
            $recentActivities,
            $chartData,
            $documentStatistics
        ));
    }

    /**
     * Get dashboard statistics for secretariat.
     */
    private function getStatistics(): array
    {
        // Inscrições Pendentes
        $pendingStatuses = RegistrationStatus::getPendingStatuses();
        $pendingRegistrations = Registration::whereIn('status', array_map(fn ($status) => $status->value, $pendingStatuses))->count();

        // Documentos Pendentes
        $pendingDocuments = Document::where('status', \App\Enums\DocumentStatus::PENDING->value)->count();
        $underReviewDocuments = Document::where('status', \App\Enums\DocumentStatus::UNDER_REVIEW->value)->count();

        // Em Análise
        $underReviewRegistrations = Registration::where('status', RegistrationStatus::UNDER_REVIEW->value)->count();

        // Aprovadas (últimos 7 dias)
        $approvedLast7Days = Registration::where('status', RegistrationStatus::APPROVED->value)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // Calculate growth percentages
        $pendingRegistrationsGrowth = $this->calculateGrowth(Registration::class, 'created_at', function ($query) use ($pendingStatuses) {
            return $query->whereIn('status', array_map(fn ($status) => $status->value, $pendingStatuses));
        });
        $pendingDocumentsGrowth = $this->calculateGrowth(Document::class, 'created_at', function ($query) {
            return $query->where('status', \App\Enums\DocumentStatus::PENDING->value);
        });
        $underReviewGrowth = $this->calculateGrowth(Registration::class, 'created_at', function ($query) {
            return $query->where('status', RegistrationStatus::UNDER_REVIEW->value);
        });

        // Chart data for sparklines
        $pendingRegistrationsChartData = $this->getSparklineChartData(
            Registration::whereIn('status', array_map(fn ($status) => $status->value, $pendingStatuses)),
            'created_at'
        );
        $pendingDocumentsChartData = $this->getSparklineChartData(
            Document::where('status', \App\Enums\DocumentStatus::PENDING->value),
            'created_at'
        );
        $underReviewChartData = $this->getSparklineChartData(
            Registration::where('status', RegistrationStatus::UNDER_REVIEW->value),
            'created_at'
        );

        return [
            'total_pending_registrations' => $pendingRegistrations,
            'total_pending_documents' => $pendingDocuments,
            'total_under_review_documents' => $underReviewDocuments,
            'total_under_review_registrations' => $underReviewRegistrations,
            'total_approved_last_7_days' => $approvedLast7Days,
            'pending_registrations_growth' => $pendingRegistrationsGrowth,
            'pending_documents_growth' => $pendingDocumentsGrowth,
            'under_review_growth' => $underReviewGrowth,
            'pending_registrations_chart_data' => $pendingRegistrationsChartData,
            'pending_documents_chart_data' => $pendingDocumentsChartData,
            'under_review_chart_data' => $underReviewChartData,
        ];
    }

    /**
     * Get document statistics.
     */
    private function getDocumentStatistics(): array
    {
        $totalDocuments = Document::count();
        $validatedDocuments = Document::where('status', \App\Enums\DocumentStatus::VALIDATED->value)->count();
        $rejectedDocuments = Document::where('status', \App\Enums\DocumentStatus::REJECTED->value)->count();
        $requiresCorrection = Document::where('status', \App\Enums\DocumentStatus::REQUIRES_CORRECTION->value)->count();

        return [
            'total_documents' => $totalDocuments,
            'total_validated_documents' => $validatedDocuments,
            'total_rejected_documents' => $rejectedDocuments,
            'total_requires_correction' => $requiresCorrection,
        ];
    }

    /**
     * Get recent activities.
     */
    private function getRecentActivities(): array
    {
        $pendingStatuses = RegistrationStatus::getPendingStatuses();

        $recentRegistrations = Registration::with(['person', 'registrationType'])
            ->whereIn('status', array_map(fn ($status) => $status->value, $pendingStatuses))
            ->latest()
            ->limit(5)
            ->get();

        $recentDocuments = Document::with(['documentType', 'member'])
            ->whereIn('status', [
                \App\Enums\DocumentStatus::PENDING->value,
                \App\Enums\DocumentStatus::UNDER_REVIEW->value,
                \App\Enums\DocumentStatus::REQUIRES_CORRECTION->value,
            ])
            ->latest()
            ->limit(5)
            ->get();

        return [
            'recent_registrations' => $recentRegistrations,
            'recent_documents' => $recentDocuments,
        ];
    }

    /**
     * Get registration chart data.
     */
    private function getRegistrationChartData(): array
    {
        $months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $pendingStatuses = RegistrationStatus::getPendingStatuses();
        $pendingData = [];
        $underReviewData = [];
        $approvedData = [];
        $rejectedData = [];

        for ($i = 1; $i <= 12; $i++) {
            $pendingData[] = Registration::whereIn('status', array_map(fn ($status) => $status->value, $pendingStatuses))
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();

            $underReviewData[] = Registration::where('status', RegistrationStatus::UNDER_REVIEW->value)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();

            $approvedData[] = Registration::where('status', RegistrationStatus::APPROVED->value)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();

            $rejectedData[] = Registration::where('status', RegistrationStatus::REJECTED->value)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        return [
            'months' => $months,
            'pending_data' => $pendingData,
            'under_review_data' => $underReviewData,
            'approved_data' => $approvedData,
            'rejected_data' => $rejectedData,
        ];
    }

    /**
     * Calculate growth percentage for a model (last 7 days vs previous 7 days).
     */
    private function calculateGrowth(string $model, string $dateColumn, ?callable $queryModifier = null): float
    {
        $query = $model::query();
        if ($queryModifier) {
            $query = $queryModifier($query);
        }

        $last7Days = (clone $query)
            ->where($dateColumn, '>=', now()->subDays(7))
            ->where($dateColumn, '<', now())
            ->count();

        $previous7Days = (clone $query)
            ->where($dateColumn, '>=', now()->subDays(14))
            ->where($dateColumn, '<', now()->subDays(7))
            ->count();

        if ($previous7Days == 0) {
            return $last7Days > 0 ? 100 : 0;
        }

        return (($last7Days - $previous7Days) / $previous7Days) * 100;
    }

    /**
     * Get sparkline chart data.
     */
    private function getSparklineChartData($query, string $dateColumn): array
    {
        $last7Days = (clone $query)->where($dateColumn, '>=', now()->subDays(7))->count();
        $currentDay = (clone $query)->whereDate($dateColumn, now())->count();

        return [
            'last_7_days' => $last7Days ?? 0,
            'current_day' => $currentDay ?? 0,
        ];
    }
}
