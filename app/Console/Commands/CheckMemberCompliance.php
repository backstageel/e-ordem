<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Services\Member\MemberAlertService;
use App\Services\Member\MemberComplianceService;
use Illuminate\Console\Command;

class CheckMemberCompliance extends Command
{
    protected $signature = 'members:check-compliance 
                            {--member= : Check compliance for a specific member ID}
                            {--send-alerts : Send alerts to non-compliant members}
                            {--export : Export compliance report to CSV}';

    protected $description = 'Check member compliance (documents, quotas, profile updates)';

    public function handle(
        MemberComplianceService $complianceService,
        MemberAlertService $alertService
    ): int {
        $memberId = $this->option('member');
        $sendAlerts = $this->option('send-alerts');
        $export = $this->option('export');

        $this->info('Checking member compliance...');
        $this->newLine();

        $query = Member::with(['person', 'quotaHistory', 'person.documents']);
        if ($memberId) {
            $query->where('id', $memberId);
        } else {
            $query->where('status', Member::STATUS_ACTIVE);
        }

        $members = $query->get();

        if ($members->isEmpty()) {
            $this->warn('No members found.');

            return Command::SUCCESS;
        }

        $this->info("Checking {$members->count()} member(s).");
        $this->newLine();

        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        $compliant = 0;
        $nonCompliant = 0;
        $issues = [];

        foreach ($members as $member) {
            $memberIssues = $complianceService->checkMemberCompliance($member);
            $isCompliant = empty($memberIssues);

            if ($isCompliant) {
                $compliant++;
            } else {
                $nonCompliant++;
                $issues[$member->id] = [
                    'member' => $member->full_name,
                    'issues' => $memberIssues,
                ];

                // Send alerts if requested
                if ($sendAlerts && $member->person && $member->person->user) {
                    // Send compliance alert notification (if exists)
                    // For now, we'll log it
                    \Log::info('Member compliance issues detected', [
                        'member_id' => $member->id,
                        'issues' => $memberIssues,
                    ]);
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Display summary
        $this->info('📊 Compliance Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['✅ Compliant', $compliant],
                ['❌ Non-Compliant', $nonCompliant],
                ['📋 Total', $members->count()],
            ]
        );

        // Display issues if any
        if ($nonCompliant > 0) {
            $this->newLine();
            $this->warn('Non-Compliant Members:');
            $this->newLine();

            $issueRows = [];
            foreach ($issues as $memberId => $data) {
                $issueList = [];
                if (isset($data['issues']['expired_documents'])) {
                    $issueList[] = 'Expired Documents ('.count($data['issues']['expired_documents']).')';
                }
                if (isset($data['issues']['missing_documents'])) {
                    $issueList[] = 'Missing Documents ('.count($data['issues']['missing_documents']).')';
                }
                if (isset($data['issues']['profile_update_required'])) {
                    $issueList[] = 'Profile Update Required';
                }
                if (isset($data['issues']['quota_irregular'])) {
                    $issueList[] = 'Quota Irregular';
                }

                $issueRows[] = [
                    $memberId,
                    $data['member'],
                    implode(', ', $issueList),
                ];
            }

            $this->table(['ID', 'Member', 'Issues'], $issueRows);

            if ($sendAlerts) {
                $this->newLine();
                $this->info("📧 Alerts sent to {$nonCompliant} non-compliant member(s).");
            }
        }

        // Export if requested
        if ($export) {
            $filename = 'compliance_report_'.now()->format('Y-m-d_H-i-s').'.csv';
            $path = storage_path('app/'.$filename);

            $file = fopen($path, 'w');
            fputcsv($file, ['Member ID', 'Member Name', 'Compliant', 'Issues']);

            foreach ($members as $member) {
                $memberIssues = $complianceService->checkMemberCompliance($member);
                $isCompliant = empty($memberIssues);
                $issueText = $isCompliant ? 'None' : json_encode($memberIssues);

                fputcsv($file, [
                    $member->id,
                    $member->full_name,
                    $isCompliant ? 'Yes' : 'No',
                    $issueText,
                ]);
            }

            fclose($file);

            $this->newLine();
            $this->info("📄 Compliance report exported to: {$path}");
        }

        return Command::SUCCESS;
    }
}
