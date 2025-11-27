<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberQuota;
use Illuminate\Database\Seeder;

class MemberQuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::where('status', Member::STATUS_ACTIVE)->get();

        if ($members->isEmpty()) {
            $this->command->warn('No active members found. Creating quotas for all members.');

            $members = Member::all();
        }

        $currentYear = now()->year;
        $currentMonth = now()->month;

        foreach ($members as $member) {
            // Generate quotas for the last 12 months
            for ($i = 0; $i < 12; $i++) {
                $date = now()->subMonths($i);
                $year = $date->year;
                $month = $date->month;

                // Check if quota already exists
                $existingQuota = MemberQuota::where('member_id', $member->id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();

                if ($existingQuota) {
                    continue;
                }

                $dueDate = \Carbon\Carbon::create($year, $month, 15);
                $isOverdue = $dueDate->isPast() && $month < $currentMonth && $year <= $currentYear;

                MemberQuota::create([
                    'member_id' => $member->id,
                    'year' => $year,
                    'month' => $month,
                    'amount' => config('members.quota.default_amount', 4000.00),
                    'due_date' => $dueDate,
                    'payment_date' => $isOverdue ? null : ($dueDate->isPast() ? $dueDate->copy()->addDays(rand(0, 15)) : null),
                    'status' => $isOverdue ? MemberQuota::STATUS_OVERDUE : ($dueDate->isPast() ? MemberQuota::STATUS_PAID : MemberQuota::STATUS_PENDING),
                    'penalty_amount' => $isOverdue ? config('members.quota.default_amount', 4000.00) * config('members.quota.penalty_percentage', 0.5) : 0,
                ]);
            }
        }

        $this->command->info('Member quotas generated successfully.');
    }
}
