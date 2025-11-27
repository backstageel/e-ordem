<?php

namespace App\Actions\Member;

use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberStatusHistory;
use Illuminate\Support\Facades\DB;

class SuspendMemberAction
{
    public function execute(Member $member, string $reason, ?int $userId = null): void
    {
        DB::transaction(function () use ($member, $reason, $userId) {
            $previousStatus = $member->status;

            // Update member status
            $member->update([
                'status' => Member::STATUS_SUSPENDED,
            ]);

            // Create status history
            MemberStatusHistory::create([
                'member_id' => $member->id,
                'previous_status' => $previousStatus,
                'new_status' => Member::STATUS_SUSPENDED,
                'changed_by' => $userId ?? auth()->id(),
                'reason' => $reason,
                'effective_date' => now(),
            ]);

            // Revoke active cards
            $this->revokeActiveCards($member);

            // Send notification if user exists
            if ($member->person && $member->person->user) {
                try {
                    $member->person->user->notify(
                        new \App\Notifications\Member\SuspensionNotification($member, $reason)
                    );
                } catch (\Throwable $e) {
                    // Log but don't fail the suspension
                    \Log::warning('Failed to send suspension notification', [
                        'member_id' => $member->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });
    }

    private function revokeActiveCards(Member $member): void
    {
        MemberCard::where('member_id', $member->id)
            ->where('status', 'active')
            ->update([
                'status' => 'revoked',
            ]);
    }
}
