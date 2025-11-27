<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotaController extends Controller
{
    /**
     * Display a listing of the member's quotas.
     */
    public function index(Request $request)
    {
        $member = Auth::user()->person->member;

        $query = $member->quotaHistory()->with('payment')->orderBy('year', 'desc')->orderBy('month', 'desc');

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotas = $query->paginate(20)->withQueryString();

        // Get statistics
        $stats = $this->getStatistics($member);

        // Get available years for filter
        $years = $member->quotaHistory()
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('member.quotas.index', compact('quotas', 'stats', 'years'));
    }

    /**
     * Display the specified quota.
     */
    public function show(MemberQuota $quota)
    {
        $member = Auth::user()->person->member;

        // Ensure quota belongs to member
        if ($quota->member_id !== $member->id) {
            abort(403);
        }

        $quota->load('payment', 'member.person');

        return view('member.quotas.show', compact('quota'));
    }

    /**
     * Get quota statistics for the member.
     */
    private function getStatistics($member): array
    {
        $currentYear = now()->year;
        $allQuotas = $member->quotaHistory();
        $yearQuotas = (clone $allQuotas)->where('year', $currentYear);

        return [
            'total' => $allQuotas->count(),
            'paid' => (clone $allQuotas)->where('status', MemberQuota::STATUS_PAID)->count(),
            'pending' => (clone $allQuotas)->where('status', MemberQuota::STATUS_PENDING)->count(),
            'overdue' => (clone $allQuotas)->where('status', MemberQuota::STATUS_OVERDUE)->count(),
            'total_amount' => (clone $allQuotas)->sum('amount'),
            'paid_amount' => (clone $allQuotas)->where('status', MemberQuota::STATUS_PAID)->sum('amount'),
            'pending_amount' => (clone $allQuotas)->where('status', MemberQuota::STATUS_PENDING)->sum('amount'),
            'overdue_amount' => (clone $allQuotas)->where('status', MemberQuota::STATUS_OVERDUE)->sum('amount'),
            'total_penalties' => (clone $allQuotas)->sum('penalty_amount'),
            'current_year_total' => $yearQuotas->count(),
            'current_year_paid' => (clone $yearQuotas)->where('status', MemberQuota::STATUS_PAID)->count(),
            'current_year_pending' => (clone $yearQuotas)->where('status', MemberQuota::STATUS_PENDING)->count(),
            'current_year_overdue' => (clone $yearQuotas)->where('status', MemberQuota::STATUS_OVERDUE)->count(),
        ];
    }
}
