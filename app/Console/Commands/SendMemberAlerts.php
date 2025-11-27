<?php

namespace App\Console\Commands;

use App\Services\Member\MemberAlertService;
use Illuminate\Console\Command;

class SendMemberAlerts extends Command
{
    protected $signature = 'members:send-alerts 
                            {--type=all : Type of alerts to send (all, quota-reminders, overdue, suspension-warnings, documents, compliance)}
                            {--dry-run : Show what would be sent without actually sending}';

    protected $description = 'Send member alerts (quota reminders, overdue alerts, suspension warnings, document alerts)';

    public function handle(MemberAlertService $alertService): int
    {
        $type = $this->option('type');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No alerts will be sent');
            $this->newLine();
        }

        $this->info('Processing member alerts...');
        $this->newLine();

        $sent = 0;
        $skipped = 0;

        // Quota Reminders
        if ($type === 'all' || $type === 'quota-reminders') {
            $this->info('📧 Checking for quota reminders...');
            if (! $dryRun) {
                $alertService->checkAndSendQuotaReminders();
            }
            $this->info('✅ Quota reminders processed');
            $this->newLine();
        }

        // Overdue Alerts
        if ($type === 'all' || $type === 'overdue') {
            $this->info('⚠️ Checking for overdue quotas...');
            if (! $dryRun) {
                $alertService->checkAndSendOverdueAlerts();
            }
            $this->info('✅ Overdue alerts processed');
            $this->newLine();
        }

        // Suspension Warnings
        if ($type === 'all' || $type === 'suspension-warnings') {
            $this->info('🚨 Checking for suspension warnings...');
            if (! $dryRun) {
                $alertService->checkAndSendSuspensionWarnings();
            }
            $this->info('✅ Suspension warnings processed');
            $this->newLine();
        }

        // Document Alerts (handled by CheckDocumentExpiration job, but we can trigger it here too)
        if ($type === 'all' || $type === 'documents') {
            $this->info('📄 Checking document expiration alerts...');
            if (! $dryRun) {
                \App\Jobs\CheckDocumentExpiration::dispatch();
            }
            $this->info('✅ Document alerts processed');
            $this->newLine();
        }

        // Compliance Alerts
        if ($type === 'all' || $type === 'compliance') {
            $this->info('✅ Checking compliance alerts...');
            if (! $dryRun) {
                $alertService->checkAndSendComplianceAlerts();
            }
            $this->info('✅ Compliance alerts processed');
            $this->newLine();
        }

        if ($dryRun) {
            $this->info("Would process alerts for: {$type}");
        } else {
            $this->info('✅ Alerts processed successfully');
        }

        return Command::SUCCESS;
    }
}
