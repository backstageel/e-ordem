<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatePaymentAction
{
    public function execute(Exam $exam, User $user, ?string $paymentMethodCode = null): Payment
    {
        return DB::transaction(function () use ($exam, $user) {
            $person = $user->person;

            // Calculate fee based on candidate type (simplified - would need nationality/formation check)
            $fee = $this->calculateFee($exam, $user);

            // Get or create payment type for exams
            $paymentType = PaymentType::firstOrCreate(
                ['code' => 'exam_fee'],
                [
                    'name' => 'Taxa de Exame',
                    'description' => 'Taxa para realização de exame',
                    'default_amount' => $fee,
                    'is_active' => true,
                ]
            );

            // Calculate due date (15 days before exam)
            $examDate = Carbon::parse($exam->exam_date);
            $dueDate = $examDate->subDays(config('exams.payment.required_days_before', 15));

            $payment = Payment::create([
                'person_id' => $person->id,
                'payment_type_id' => $paymentType->id,
                'reference_number' => 'EXAM-'.now()->format('Ymd').'-'.Str::upper(Str::random(8)),
                'unique_reference' => Str::uuid()->toString(),
                'amount' => $fee,
                'due_date' => $dueDate,
                'status' => 'pending',
                'payable_type' => Exam::class,
                'payable_id' => $exam->id,
                'notes' => "Taxa para exame: {$exam->name}",
            ]);

            return $payment;
        });
    }

    private function calculateFee(Exam $exam, User $user): float
    {
        // Simplified fee calculation
        // In a real scenario, this would check nationality and training country
        $person = $user->person;

        // Default to Mozambican trained fee
        $defaultFee = config('exams.fees.mozambican_trained_mozambican', 500);

        // Check if foreign trained foreign institution (highest fee)
        // This is simplified - would need proper logic based on academic qualifications
        return $defaultFee;
    }

    public function validatePayment(Exam $exam, User $user): ?Payment
    {
        return Payment::where('payable_type', Exam::class)
            ->where('payable_id', $exam->id)
            ->where('person_id', $user->person_id)
            ->where('status', 'completed')
            ->latest()
            ->first();
    }
}
