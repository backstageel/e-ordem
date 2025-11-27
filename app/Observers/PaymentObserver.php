<?php

namespace App\Observers;

use App\Models\MemberQuota;
use App\Models\Payment;
use App\Services\Member\MemberQuotaService;

class PaymentObserver
{
    public function __construct(
        private MemberQuotaService $quotaService
    ) {}

    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        $this->linkPaymentToQuota($payment);
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        // If payment status changed to completed/paid, link to quota
        if ($payment->wasChanged('status') && in_array($payment->status, ['completed', 'paid'])) {
            $this->linkPaymentToQuota($payment);
        }

        // If payment status changed from paid to something else, unlink quota
        if ($payment->wasChanged('status') && ! in_array($payment->status, ['completed', 'paid'])) {
            $this->unlinkQuota($payment);
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        $this->unlinkQuota($payment);
    }

    /**
     * Link payment to member quota if it's a quota payment.
     */
    private function linkPaymentToQuota(Payment $payment): void
    {
        // Only process if payment is completed/paid and has a member
        if (! in_array($payment->status, ['completed', 'paid']) || ! $payment->member_id) {
            return;
        }

        // Check if this is a quota payment
        $quotaPaymentTypeCodes = ['annual_quota', 'monthly_quota', 'quotas_mensais'];
        $paymentTypeCode = strtolower($payment->paymentType->code ?? '');
        $paymentTypeName = strtolower($payment->paymentType->name ?? '');

        $isQuotaPayment = $payment->paymentType && (
            in_array($paymentTypeCode, $quotaPaymentTypeCodes) ||
            str_contains($paymentTypeName, 'quota')
        );

        if (! $isQuotaPayment) {
            return;
        }

        // Try to find matching quota by payment date or reference number
        $paymentDate = $payment->payment_date ?? now();
        $year = $paymentDate->year;
        $month = $paymentDate->month;

        // First, try to find quota by year/month
        $quota = MemberQuota::where('member_id', $payment->member_id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('status', '!=', MemberQuota::STATUS_PAID)
            ->whereNull('payment_id') // Not already linked to another payment
            ->first();

        // If not found, try to find oldest unpaid quota for this member
        if (! $quota) {
            $quota = MemberQuota::where('member_id', $payment->member_id)
                ->where('status', '!=', MemberQuota::STATUS_PAID)
                ->whereNull('payment_id')
                ->orderBy('year')
                ->orderBy('month')
                ->first();
        }

        if ($quota) {
            $this->quotaService->markQuotaAsPaid($quota, $payment->id);
        }
    }

    /**
     * Unlink quota from payment.
     */
    private function unlinkQuota(Payment $payment): void
    {
        if (! $payment->id) {
            return;
        }

        MemberQuota::where('payment_id', $payment->id)
            ->update([
                'payment_id' => null,
                'status' => MemberQuota::STATUS_PENDING,
                'payment_date' => null,
            ]);
    }
}
