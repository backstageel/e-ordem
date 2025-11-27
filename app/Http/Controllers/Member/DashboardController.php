<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the member dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Get person and member
        $person = $user->person;
        $member = $person ? $person->member : null;

        // Get member's registrations
        $registrations = $member ? $member->registrations()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get() : collect();

        // Get member's payments
        $payments = $member ? $member->payments()
            ->orderBy('payment_date', 'desc')
            ->take(5)
            ->get() : collect();

        // Get pending payments
        $pendingPayments = $member ? $member->payments()
            ->where('status', 'pending')
            ->count() : 0;

        // Get total paid
        $totalPaid = $member ? $member->payments()
            ->where('status', 'completed')
            ->sum('amount') : 0;

        // Get quota statistics
        $quotaStats = $this->getQuotaStatistics($member);

        // Get recent quotas
        $recentQuotas = $member ? $member->quotaHistory()
            ->with('payment')
            ->orderBy('due_date', 'desc')
            ->take(6)
            ->get() : collect();

        // Get overdue quotas
        $overdueQuotas = $member ? $member->overdueQuotas()
            ->orderBy('due_date', 'asc')
            ->get() : collect();

        // Get quota status
        $quotaStatus = $member ? $member->getQuotaStatus() : 'regular';
        $totalQuotaDue = $member ? $member->totalQuotaDue() : 0;

        // Get document statistics (moved from view)
        $documentStats = $this->getDocumentStatistics($person);

        // Get exam statistics (moved from view)
        $examStats = $this->getExamStatistics($member);

        // Get active registration
        $activeRegistration = $registrations->where('status', 'approved')->first();
        $registrationType = $activeRegistration ? ($activeRegistration->registrationType->name ?? 'Ativa') : 'Nenhuma';

        // Get last payment date for display
        $lastPayment = $payments->isNotEmpty() ? $payments->first() : null;
        $lastPaymentDate = $lastPayment && $lastPayment->payment_date ? $lastPayment->payment_date->format('d/m/Y') : 'N/A';

        // Get pending documents count for notifications (from documentStats)
        $pendingDocuments = $documentStats['pending'] ?? 0;

        // Get recent notifications (moved from view)
        $notifications = $user->notifications()->take(3)->get();

        return view('member.dashboard', compact(
            'member',
            'person',
            'registrations',
            'activeRegistration',
            'registrationType',
            'payments',
            'lastPaymentDate',
            'pendingPayments',
            'totalPaid',
            'quotaStats',
            'recentQuotas',
            'overdueQuotas',
            'quotaStatus',
            'totalQuotaDue',
            'documentStats',
            'examStats',
            'pendingDocuments',
            'notifications'
        ));
    }

    /**
     * Get quota statistics for the member.
     */
    private function getQuotaStatistics($member): array
    {
        if (! $member) {
            return [
                'total' => 0,
                'paid' => 0,
                'pending' => 0,
                'overdue' => 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'pending_amount' => 0,
                'overdue_amount' => 0,
                'total_penalties' => 0,
                'current_year_total' => 0,
                'current_year_paid' => 0,
            ];
        }

        $currentYear = now()->year;
        $allQuotas = $member->quotaHistory();
        $yearQuotas = (clone $allQuotas)->where('year', $currentYear);

        return [
            'total' => $allQuotas->count(),
            'paid' => (clone $allQuotas)->where('status', \App\Models\MemberQuota::STATUS_PAID)->count(),
            'pending' => (clone $allQuotas)->where('status', \App\Models\MemberQuota::STATUS_PENDING)->count(),
            'overdue' => (clone $allQuotas)->where('status', \App\Models\MemberQuota::STATUS_OVERDUE)->count(),
            'total_amount' => (clone $allQuotas)->sum('amount'),
            'paid_amount' => (clone $allQuotas)->where('status', \App\Models\MemberQuota::STATUS_PAID)->sum('amount'),
            'pending_amount' => (clone $allQuotas)->where('status', \App\Models\MemberQuota::STATUS_PENDING)->sum('amount'),
            'overdue_amount' => (clone $allQuotas)->where('status', \App\Models\MemberQuota::STATUS_OVERDUE)->sum('amount'),
            'total_penalties' => (clone $allQuotas)->sum('penalty_amount'),
            'current_year_total' => $yearQuotas->count(),
            'current_year_paid' => (clone $yearQuotas)->where('status', \App\Models\MemberQuota::STATUS_PAID)->count(),
            'current_year_pending' => (clone $yearQuotas)->where('status', \App\Models\MemberQuota::STATUS_PENDING)->count(),
            'current_year_overdue' => (clone $yearQuotas)->where('status', \App\Models\MemberQuota::STATUS_OVERDUE)->count(),
        ];
    }

    /**
     * Get document statistics for the person.
     */
    private function getDocumentStatistics($person): array
    {
        if (! $person) {
            return [
                'total' => 0,
                'approved' => 0,
                'pending' => 0,
            ];
        }

        return [
            'total' => $person->documents()->count(),
            'approved' => $person->documents()->where('status', 'approved')->count(),
            'pending' => $person->documents()->where('status', 'pending')->count(),
        ];
    }

    /**
     * Get exam statistics for the member.
     */
    private function getExamStatistics($member): array
    {
        if (! $member || ! $member->person || ! $member->person->user) {
            return [
                'total' => 0,
                'approved' => 0,
                'scheduled' => 0,
            ];
        }

        $user = $member->person->user;
        $examsQuery = \App\Models\ExamApplication::where('user_id', $user->id);

        return [
            'total' => $examsQuery->count(),
            'approved' => (clone $examsQuery)->where('status', 'approved')->count(),
            'scheduled' => (clone $examsQuery)->where('status', 'scheduled')->count(),
        ];
    }
}
