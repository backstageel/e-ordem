<?php

namespace App\Jobs;

use App\Actions\Member\SuspendMemberAction;
use App\Models\Member;
use App\Services\Member\MemberQuotaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAutoSuspension implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        MemberQuotaService $quotaService,
        SuspendMemberAction $suspendAction
    ): void {
        if (! config('members.suspension.auto_suspend_enabled', true)) {
            return;
        }

        $members = Member::where('status', Member::STATUS_ACTIVE)
            ->with('quotaHistory')
            ->get();

        foreach ($members as $member) {
            if ($quotaService->shouldSuspendForQuotas($member)) {
                $oldestOverdue = $member->overdueQuotas()
                    ->orderBy('due_date', 'asc')
                    ->first();

                $daysOverdue = now()->diffInDays($oldestOverdue->due_date);
                $reason = "Suspensão automática por inadimplência. Quotas em atraso há {$daysOverdue} dias.";

                try {
                    $suspendAction->execute($member, $reason, null);
                } catch (\Exception $e) {
                    \Log::error('Failed to auto-suspend member', [
                        'member_id' => $member->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
