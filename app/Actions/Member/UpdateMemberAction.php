<?php

namespace App\Actions\Member;

use App\Models\Member;
use App\Models\MemberStatusHistory;
use Illuminate\Support\Facades\DB;

class UpdateMemberAction
{
    public function execute(Member $member, array $data, ?string $reason = null): Member
    {
        return DB::transaction(function () use ($member, $data, $reason) {
            $previousStatus = $member->status;

            // Update person if person data is provided
            if (isset($data['person']) && is_array($data['person'])) {
                $personData = array_filter($data['person']);
                if (! empty($personData)) {
                    $member->person->update($personData);
                }
            }

            // Remove person from member data
            $memberData = $data;
            unset($memberData['person']);

            // Update member
            $member->update(array_filter($memberData, fn ($value) => $value !== null));

            // Create status history if status changed
            if (isset($data['status']) && $data['status'] !== $previousStatus) {
                MemberStatusHistory::create([
                    'member_id' => $member->id,
                    'previous_status' => $previousStatus,
                    'new_status' => $data['status'],
                    'changed_by' => auth()->id(),
                    'reason' => $reason ?? 'Atualização de dados',
                    'effective_date' => now(),
                ]);
            }

            return $member->fresh(['person', 'medicalSpeciality']);
        });
    }
}
