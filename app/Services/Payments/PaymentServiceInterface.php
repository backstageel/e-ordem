<?php

namespace App\Services\Payments;

use App\Models\Payment;

interface PaymentServiceInterface
{
    /**
     * Process a payment.
     */
    public function processPayment(Payment $payment): array;

    /**
     * Verify a payment status.
     */
    public function verifyPayment(string $transactionId): array;

    /**
     * Get the name of the payment service.
     */
    public function getName(): string;
}
