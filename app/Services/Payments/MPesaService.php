<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Models\PaymentIntegration;
use App\Models\PaymentIntegrationLog;
use Illuminate\Support\Facades\Log;

class MPesaService implements PaymentServiceInterface
{
    protected $integration;

    /**
     * Create a new MPesaService instance.
     */
    public function __construct(?PaymentIntegration $integration = null)
    {
        $this->integration = $integration ?? PaymentIntegration::where('provider', 'mpesa')
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
                'message' => 'M-Pesa integration not configured or not active',
                'transaction_id' => null,
            ];
        }

        try {
            // Log the request
            $requestData = [
                'amount' => $payment->amount,
                'phone' => $payment->member->phone ?? '',
                'reference' => $payment->reference_number,
                'description' => $payment->paymentType->name,
            ];

            // Create a log entry
            $log = PaymentIntegrationLog::create([
                'payment_integration_id' => $this->integration->id,
                'payment_id' => $payment->id,
                'status' => 'processing',
                'request_data' => $requestData,
                'message' => 'Processing M-Pesa payment',
            ]);

            // In a real implementation, we would make an API call to M-Pesa
            // For now, we'll simulate a successful response
            $responseData = [
                'success' => true,
                'transaction_id' => 'MPESA'.time(),
                'status' => 'pending',
                'message' => 'Payment request sent to M-Pesa',
            ];

            // Update the log with the response
            $log->update([
                'response_data' => $responseData,
                'transaction_id' => $responseData['transaction_id'],
                'status' => 'success',
                'message' => 'Payment request sent to M-Pesa',
            ]);

            // Update the payment
            $payment->update([
                'transaction_id' => $responseData['transaction_id'],
                'status' => 'processing',
            ]);

            return [
                'success' => true,
                'message' => 'Payment request sent to M-Pesa',
                'transaction_id' => $responseData['transaction_id'],
            ];
        } catch (\Exception $e) {
            Log::error('M-Pesa payment processing error: '.$e->getMessage());

            // Update the log with the error
            if (isset($log)) {
                $log->update([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }

            return [
                'success' => false,
                'message' => 'Error processing M-Pesa payment: '.$e->getMessage(),
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
                'message' => 'M-Pesa integration not configured or not active',
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
                'message' => 'Verifying M-Pesa payment',
            ]);

            // In a real implementation, we would make an API call to M-Pesa
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
            Log::error('M-Pesa payment verification error: '.$e->getMessage());

            // Update the log with the error
            if (isset($log)) {
                $log->update([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }

            return [
                'success' => false,
                'message' => 'Error verifying M-Pesa payment: '.$e->getMessage(),
                'status' => 'error',
            ];
        }
    }

    /**
     * Get the name of the payment service.
     */
    public function getName(): string
    {
        return 'M-Pesa';
    }
}
