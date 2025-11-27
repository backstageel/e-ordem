<?php

namespace App\Console\Commands;

use App\Jobs\Exam\SendExamRemindersJob;
use Illuminate\Console\Command;

class SendExamReminders extends Command
{
    protected $signature = 'exams:send-reminders 
                            {--dry-run : Show what would be sent without actually sending}';

    protected $description = 'Send exam reminders to candidates';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No reminders will be sent');
            $this->newLine();
        }

        $this->info('Sending exam reminders...');

        if (! $dryRun) {
            SendExamRemindersJob::dispatch();
            $this->info('✅ Reminder job dispatched successfully');
        } else {
            $this->info('Would dispatch reminder job');
        }

        return Command::SUCCESS;
    }
}
