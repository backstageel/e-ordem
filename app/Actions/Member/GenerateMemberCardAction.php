<?php

namespace App\Actions\Member;

use App\Models\Member;
use App\Models\MemberCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateMemberCardAction
{
    public function execute(Member $member, ?\DateTime $expiryDate = null): MemberCard
    {
        return DB::transaction(function () use ($member, $expiryDate) {
            // Validate member can generate card
            if (! $this->canGenerateCard($member)) {
                throw new \Exception('Membro não pode gerar cartão: status ou quotas irregulares.');
            }

            // Generate card number
            $cardNumber = $this->generateCardNumber($member);

            // Generate QR code
            $qrCodePath = $this->generateQRCode($member);

            // Determine expiry date
            $cardExpiryDate = $expiryDate
                ? \Carbon\Carbon::parse($expiryDate)
                : ($member->expiry_date ?? now()->addMonths(config('members.card.validity_months', 12)));

            // Create card
            $card = MemberCard::create([
                'member_id' => $member->id,
                'card_number' => $cardNumber,
                'qr_code' => $qrCodePath,
                'issue_date' => now(),
                'expiry_date' => $cardExpiryDate,
                'status' => 'active',
                'card_type_id' => $this->determineCardType($member),
            ]);

            return $card;
        });
    }

    private function canGenerateCard(Member $member): bool
    {
        $requiresActive = config('members.card.require_active_status', true);
        $requiresRegularQuotas = config('members.card.require_regular_quotas', true);
        $requiresNoPendingDocs = config('members.card.require_no_pending_documents', true);

        if ($requiresActive && $member->status !== Member::STATUS_ACTIVE) {
            return false;
        }

        if ($requiresRegularQuotas && ! $member->isQuotaRegular()) {
            return false;
        }

        if ($requiresNoPendingDocs && $member->hasPendingDocuments()) {
            return false;
        }

        return true;
    }

    private function generateCardNumber(Member $member): string
    {
        return 'ORMM-'.str_pad((string) $member->id, 6, '0', STR_PAD_LEFT).'-'.date('Ymd');
    }

    private function generateQRCode(Member $member): string
    {
        $profileUrl = url('/guest/members/'.$member->id);
        $qrCodePath = 'member-cards/qr-'.Str::random(10).'.png';
        $qrCodeFullPath = storage_path('app/public/'.$qrCodePath);

        // Ensure directory exists
        if (! file_exists(dirname($qrCodeFullPath))) {
            mkdir(dirname($qrCodeFullPath), 0755, true);
        }

        // Generate QR code image
        QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->generate($profileUrl, $qrCodeFullPath);

        return $qrCodePath;
    }

    private function determineCardType(Member $member): int
    {
        // Logic to determine card type based on registration type
        // Get first active card type or create a default one
        $cardType = \App\Models\CardType::where('is_active', true)->first();

        if (! $cardType) {
            // Create a default card type if none exists
            $cardType = \App\Models\CardType::create([
                'name' => 'Full Member Card',
                'description' => 'Card for full members',
                'color_code' => '#4CAF50',
                'validity_period_days' => 730,
                'fee' => 500.00,
                'is_active' => true,
            ]);
        }

        return $cardType->id;
    }
}
