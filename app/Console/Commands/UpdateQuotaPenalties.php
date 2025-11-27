<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Services\Member\MemberQuotaService;
use Illuminate\Console\Command;

class UpdateQuotaPenalties extends Command
{
    protected $signature = 'members:update-quota-penalties 
                            {--dry-run : Show what would be updated without making changes}';

    protected $description = 'Update overdue quotas and calculate penalties automatically';

    public function handle(MemberQuotaService $quotaService): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        $this->info('Updating overdue quotas and calculating penalties...');

        $members = Member::where('status', Member::STATUS_ACTIVE)
            ->with('quotaHistory')
            ->get();

        if ($members->isEmpty()) {
            $this->warn('No active members found.');

            return Command::SUCCESS;
        }

        $this->info("Processing {$members->count()} active member(s).");

        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        $updated = 0;
        $totalPenalties = 0.0;

        foreach ($members as $member) {
            $beforeCount = $member->overdueQuotas()->count();

            if (! $dryRun) {
                $quotaService->updateOverdueQuotas($member);
            }

            $member->refresh();
            $afterCount = $member->overdueQuotas()->count();

            if ($afterCount > $beforeCount) {
                $updated++;
                $penalties = $member->overdueQuotas()->sum('penalty_amount');
                $totalPenalties += $penalties;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info("Would update penalties for {$updated} member(s).");
            $this->info('Total penalties: '.number_format($totalPenalties, 2, ',', '.').' MZN');
        } else {
            $this->info("✅ Updated penalties for {$updated} member(s).");
            $this->info('Total penalties calculated: '.number_format($totalPenalties, 2, ',', '.').' MZN');
        }

        return Command::SUCCESS;
    }
}
