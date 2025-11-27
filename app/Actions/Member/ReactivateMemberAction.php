<?php

namespace App\Actions\Member;

use App\Models\Member;
use App\Models\MemberStatusHistory;
use Illuminate\Support\Facades\DB;

class ReactivateMemberAction
{
    public function execute(Member $member, string $reason, ?int $userId = null): void
    {
        DB::transaction(function () use ($member, $reason, $userId) {
            $previousStatus = $member->status;

            // Update member status
            $member->update([
                'status' => Member::STATUS_ACTIVE,
            ]);

            // Create status history
            MemberStatusHistory::create([
                'member_id' => $member->id,
                'previous_status' => $previousStatus,
                'new_status' => Member::STATUS_ACTIVE,
                'changed_by' => $userId ?? auth()->id(),
                'reason' => $reason,
                'effective_date' => now(),
            ]);

            // Send notification if user exists
            if ($member->person && $member->person->user) {
                try {
                    $member->person->user->notify(
                        new \App\Notifications\Member\ReactivationNotification($member, $reason)
                    );
                } catch (\Throwable $e) {
                    // Log but don't fail the reactivation
                    \Log::warning('Failed to send reactivation notification', [
                        'member_id' => $member->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });
    }
}
