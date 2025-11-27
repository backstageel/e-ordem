<?php

namespace App\Console\Commands;

use App\Actions\Member\SuspendMemberAction;
use App\Models\Member;
use App\Services\Member\MemberQuotaService;
use Illuminate\Console\Command;

class AutoSuspendMembers extends Command
{
    protected $signature = 'members:auto-suspend 
                            {--dry-run : Show what would be suspended without making changes}
                            {--force : Force suspension even if auto-suspend is disabled in config}';

    protected $description = 'Automatically suspend members with overdue quotas exceeding threshold';

    public function handle(
        MemberQuotaService $quotaService,
        SuspendMemberAction $suspendAction
    ): int {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        if (! config('members.suspension.auto_suspend_enabled', true) && ! $force) {
            $this->warn('Auto-suspension is disabled in configuration. Use --force to override.');

            return Command::SUCCESS;
        }

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No members will be suspended');
            $this->newLine();
        }

        $this->info('Checking members for automatic suspension...');

        $members = Member::where('status', Member::STATUS_ACTIVE)
            ->with('quotaHistory')
            ->get();

        if ($members->isEmpty()) {
            $this->warn('No active members found.');

            return Command::SUCCESS;
        }

        $this->info("Checking {$members->count()} active member(s).");

        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        $suspended = 0;
        $suspensionDays = config('members.suspension.days_before_suspension', 90);

        foreach ($members as $member) {
            if ($quotaService->shouldSuspendForQuotas($member)) {
                $oldestOverdue = $member->overdueQuotas()
                    ->orderBy('due_date', 'asc')
                    ->first();

                $daysOverdue = now()->diffInDays($oldestOverdue->due_date);
                $reason = "Suspensão automática por inadimplência. Quotas em atraso há {$daysOverdue} dias.";

                if ($dryRun) {
                    $this->newLine();
                    $this->warn("Would suspend: {$member->full_name} (ID: {$member->id}) - {$daysOverdue} days overdue");
                } else {
                    try {
                        $suspendAction->execute($member, $reason);
                        $this->newLine();
                        $this->info("✅ Suspended: {$member->full_name} (ID: {$member->id})");
                        $suspended++;
                    } catch (\Exception $e) {
                        $this->newLine();
                        $this->error("Failed to suspend member {$member->id}: ".$e->getMessage());
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info("Would suspend {$suspended} member(s) with quotas overdue for {$suspensionDays}+ days.");
        } else {
            $this->info("✅ Suspended {$suspended} member(s) automatically.");
        }

        return Command::SUCCESS;
    }
}
