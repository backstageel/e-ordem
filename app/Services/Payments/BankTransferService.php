<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Models\PaymentIntegration;
use App\Models\PaymentIntegrationLog;
use Illuminate\Support\Facades\Log;

class BankTransferService implements PaymentServiceInterface
{
    protected $integration;

    /**
     * Create a new BankTransferService instance.
     */
    public function __construct(?PaymentIntegration $integration = null)
    {
        $this->integration = $integration ?? PaymentIntegration::where('provider', 'bank')
            ->where('is_active', true)
            ->first();
    }

    /**
     * Process a payment.
     */
    public function processPayment(Payment $payment): array
    {
        if (! $this->integration) {
            return [
                'success' => false,
                'message' => 'Bank integration not configured or not active',
                'transaction_id' => null,
            ];
        }

        try {
            // Log the request
            $requestData = [
                'amount' => $payment->amount,
                'account_name' => $payment->member->name ?? '',
                'reference' => $payment->reference_number,
                'description' => $payment->paymentType->name,
            ];

            // Create a log entry
            $log = PaymentIntegrationLog::create([
                'payment_integration_id' => $this->integration->id,
                'payment_id' => $payment->id,
                'status' => 'processing',
                'request_data' => $requestData,
                'message' => 'Processing bank transfer payment',
            ]);

            // In a real implementation, we would make an API call to the bank
            // For now, we'll simulate a successful response
            $responseData = [
                'success' => true,
                'transaction_id' => 'BANK'.time(),
                'status' => 'pending',
                'message' => 'Payment request sent to bank',
            ];

            // Update the log with the response
            $log->update([
                'response_data' => $responseData,
                'transaction_id' => $responseData['transaction_id'],
                'status' => 'success',
                'message' => 'Payment request sent to bank',
            ]);

            // Update the payment
            $payment->update([
                'transaction_id' => $responseData['transaction_id'],
                'status' => 'processing',
            ]);

            return [
                'success' => true,
                'message' => 'Payment request sent to bank',
                'transaction_id' => $responseData['transaction_id'],
            ];
        } catch (\Exception $e) {
            Log::error('Bank transfer payment processing error: '.$e->getMessage());

            // Update the log with the error
            if (isset($log)) {
                $log->update([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }

            return [
                'success' => false,
                'message' => 'Error processing bank transfer payment: '.$e->getMessage(),
                'transaction_id' => null,
            ];
        }
    }

    /**
     * Verify a payment status.
     */
    public function verifyPayment(string $transactionId): array
    {
        if (! $this->integration) {
            return [
                'success' => false,
                'message' => 'Bank integration not configured or not active',
                'status' => 'error',
            ];
        }

        try {
            // Log the request
            $requestData = [
                'transaction_id' => $transactionId,
            ];

            // Create a log entry
            $log = PaymentIntegrationLog::create([
                'payment_integration_id' => $this->integration->id,
                'transaction_id' => $transactionId,
                'status' => 'processing',
                'request_data' => $requestData,
                'message' => 'Verifying bank transfer payment',
            ]);

            // In a real implementation, we would make an API call to the bank
            // For now, we'll simulate a successful response
            $responseData = [
                'success' => true,
                'status' => 'paid',
                'message' => 'Payment confirmed',
            ];

            // Update the log with the response
            $log->update([
                'response_data' => $responseData,
                'status' => 'success',
                'message' => 'Payment verification successful',
            ]);

            // Find and update the payment if it exists
            $payment = Payment::where('transaction_id', $transactionId)->first();
            if ($payment) {
                $payment->update([
                    'status' => $responseData['status'],
                    'payment_date' => now(),
                ]);

                // Update the log with the payment ID
                $log->update([
                    'payment_id' => $payment->id,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Payment verification successful',
                'status' => $responseData['status'],
            ];
        } catch (\Exception $e) {
            Log::error('Bank transfer payment verification error: '.$e->getMessage());

            // Update the log with the error
            if (isset($log)) {
                $log->update([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }

            return [
                'success' => false,
                'message' => 'Error verifying bank transfer payment: '.$e->getMessage(),
                'status' => 'error',
            ];
        }
    }

    /**
     * Get the name of the payment service.
     */
    public function getName(): string
    {
        return 'Bank Transfer';
    }
}
