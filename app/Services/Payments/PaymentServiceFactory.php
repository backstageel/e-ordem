<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Models\PaymentMethod;

class PaymentServiceFactory
{
    /**
     * Create a payment service based on the payment method.
     *
     * @param  Payment|PaymentMethod|string  $paymentMethod
     *
     * @throws \InvalidArgumentException
     */
    public static function create($paymentMethod): PaymentServiceInterface
    {
        // If $paymentMethod is a Payment object, get its payment method
        if ($paymentMethod instanceof Payment) {
            $paymentMethod = $paymentMethod->paymentMethod;
        }

        // If $paymentMethod is a PaymentMethod object, get its name
        if ($paymentMethod instanceof PaymentMethod) {
            $paymentMethod = $paymentMethod->name;
        }

        // Convert to lowercase and remove spaces for comparison
        $method = strtolower(str_replace(' ', '', $paymentMethod));

        // Create the appropriate service based on the payment method
        switch ($method) {
            case 'm-pesa':
            case 'mpesa':
                return new MPesaService;

            case 'e-mola':
            case 'emola':
                return new EMolaService;

            case 'transferênciabancária':
            case 'transferenciabancaria':
            case 'banktransfer':
                return new BankTransferService;

            default:
                throw new \InvalidArgumentException("Unsupported payment method: {$paymentMethod}");
        }
    }
}
