<?php

namespace App\Actions\Member;

use App\Models\Member;
use App\Models\MemberQuota;
use App\Models\MemberStatusHistory;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateMemberAction
{
    public function execute(array $data): Member
    {
        return DB::transaction(function () use ($data) {
            // Create or find user
            $email = $data['person']['email'] ?? $data['email'] ?? null;
            $name = ($data['person']['first_name'] ?? '').' '.($data['person']['last_name'] ?? '');

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => trim($name) ?: 'Membro',
                    'password' => Hash::make(Str::random(12)),
                ]
            );

            // Create or find person
            $person = Person::firstOrNew([
                'email' => $email,
                'phone' => $data['person']['phone'] ?? null,
            ]);

            $person->fill([
                'user_id' => $user->id,
                'first_name' => $data['person']['first_name'] ?? null,
                'middle_name' => $data['person']['middle_name'] ?? null,
                'last_name' => $data['person']['last_name'] ?? null,
                'father_name' => $data['person']['father_name'] ?? null,
                'mother_name' => $data['person']['mother_name'] ?? null,
                'gender_id' => $data['person']['gender_id'] ?? null,
                'birth_date' => $data['person']['birth_date'] ?? null,
                'birth_country_id' => $data['person']['birth_country_id'] ?? null,
                'birth_province_id' => $data['person']['birth_province_id'] ?? null,
                'nationality_id' => $data['person']['nationality_id'] ?? null,
                'identity_document_number' => $data['person']['identity_document_number'] ?? null,
                'living_address' => $data['person']['living_address'] ?? null,
                'living_province_id' => $data['person']['living_province_id'] ?? null,
            ]);
            $person->save();

            // Generate member number
            $memberNumber = $this->generateMemberNumber();

            // Create member
            $member = Member::create([
                'person_id' => $person->id,
                'member_number' => $memberNumber,
                'registration_number' => $data['registration_number'] ?? null,
                'status' => $data['status'] ?? Member::STATUS_ACTIVE,
                'registration_date' => $data['registration_date'] ?? now(),
                'expiry_date' => $data['expiry_date'] ?? null,
                'professional_category' => $data['professional_category'] ?? null,
                'specialty' => $data['specialty'] ?? null,
                'medical_speciality_id' => $data['medical_speciality_id'] ?? null,
                'workplace' => $data['workplace'] ?? null,
                'years_of_experience' => $data['years_of_experience'] ?? null,
            ]);

            // Generate initial quotas for current month if active
            if ($member->status === Member::STATUS_ACTIVE) {
                $this->generateInitialQuotas($member);
            }

            // Create status history
            MemberStatusHistory::create([
                'member_id' => $member->id,
                'previous_status' => null,
                'new_status' => $member->status,
                'changed_by' => auth()->id(),
                'reason' => 'Criação de novo membro',
                'effective_date' => now(),
            ]);

            return $member->fresh(['person', 'medicalSpeciality']);
        });
    }

    private function generateMemberNumber(): string
    {
        $year = now()->year;
        $lastMember = Member::where('member_number', 'like', 'MEM'.$year.'%')
            ->orderBy('member_number', 'desc')
            ->first();

        if ($lastMember) {
            $lastNumber = (int) substr($lastMember->member_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'MEM'.$year.str_pad((string) $newNumber, 4, '0', STR_PAD_LEFT);
    }

    private function generateInitialQuotas(Member $member): void
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $amount = config('members.quota.default_amount', 4000.00);

        // Generate quota for current month
        MemberQuota::firstOrCreate(
            [
                'member_id' => $member->id,
                'year' => $currentYear,
                'month' => $currentMonth,
            ],
            [
                'amount' => $amount,
                'due_date' => now()->day(15),
                'status' => MemberQuota::STATUS_PENDING,
            ]
        );
    }
}
