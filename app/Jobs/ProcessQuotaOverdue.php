<?php

namespace App\Jobs;

use App\Models\Member;
use App\Services\Member\MemberAlertService;
use App\Services\Member\MemberQuotaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessQuotaOverdue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        MemberQuotaService $quotaService,
        MemberAlertService $alertService
    ): void {
        // Update overdue quotas and calculate penalties
        $members = Member::where('status', Member::STATUS_ACTIVE)->get();

        foreach ($members as $member) {
            $quotaService->updateOverdueQuotas($member);
        }

        // Send overdue alerts
        $alertService->checkAndSendOverdueAlerts();

        // Send suspension warnings
        $alertService->checkAndSendSuspensionWarnings();
    }
}
