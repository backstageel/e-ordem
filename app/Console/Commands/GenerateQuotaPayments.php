<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Services\Member\MemberQuotaService;
use Illuminate\Console\Command;

class GenerateQuotaPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:generate-quotas 
                            {--year= : The year to generate quotas for (defaults to current year)} 
                            {--month= : The month to generate quotas for (1-12, defaults to all months)} 
                            {--member= : Generate quotas for a specific member ID only}
                            {--force : Force regeneration even if quotas already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate member quotas for all active members (uses MemberQuotaService)';

    /**
     * Execute the console command.
     */
    public function handle(MemberQuotaService $quotaService): int
    {
        $year = (int) ($this->option('year') ?? now()->year);
        $month = $this->option('month') ? (int) $this->option('month') : null;
        $memberId = $this->option('member') ? (int) $this->option('member') : null;
        $force = $this->option('force');

        $this->info("Generating quotas for year: {$year}".($month ? ", month: {$month}" : ', all months'));

        // Get members to process
        $query = Member::where('status', Member::STATUS_ACTIVE);
        if ($memberId) {
            $query->where('id', $memberId);
        }
        $members = $query->get();

        if ($members->isEmpty()) {
            $this->warn('No active members found.');

            return Command::SUCCESS;
        }

        $this->info("Found {$members->count()} active member(s).");

        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        $generated = 0;
        $skipped = 0;

        foreach ($members as $member) {
            try {
                $quotaService->generateQuotasForMember($member, $year, $month);
                $generated++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error generating quotas for member {$member->id}: ".$e->getMessage());
                $skipped++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Successfully generated quotas for {$generated} member(s).");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} member(s) due to errors.");
        }

        return Command::SUCCESS;
    }
}
