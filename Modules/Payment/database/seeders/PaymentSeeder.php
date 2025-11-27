<?php

namespace Modules\Payment\Database\Seeders;

use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Person;
use Modules\Registration\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all members
        $members = Member::all();

        // Get all registrations
        $registrations = Registration::all();

        // Get all payment types
        $paymentTypes = PaymentType::all();

        // Get all payment methods
        $paymentMethods = PaymentMethod::all();

        // Get admin users for recording payments
        $users = User::all();

        // If no users, create one
        if ($users->isEmpty()) {
            $users = collect([
                User::create([
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]),
            ]);
        }

        // Find the member whose person has the email medico@hostmoz.net
        $targetPerson = Person::where('email', 'medico@hostmoz.net')->first();
        $targetMember = $targetPerson ? $targetPerson->member : null;

        // Possible statuses for payments
        $statuses = ['pending', 'completed', 'failed', 'refunded'];

        // Create 3 payments for the target member if found
        if ($targetMember) {
            for ($i = 0; $i < 3; $i++) {
                // Get random payment type and method
                $paymentType = $paymentTypes->random();
                $paymentMethod = $paymentMethods->random();

                // Always set status to completed for these payments
                $status = 'completed';

                // Generate random date within the last year
                $paymentDate = now()->subDays(rand(1, 365));

                // Generate random due date (if applicable)
                $dueDate = rand(0, 1) == 1 ? (clone $paymentDate)->subDays(rand(1, 30)) : null;

                // Generate random amount
                $amount = $paymentType->default_amount * (rand(80, 120) / 100);

                // Generate unique reference number
                $referenceNumber = 'PAY-'.date('Ymd', strtotime($paymentDate)).'-'.strtoupper(Str::random(6));

                // Generate transaction ID
                $transactionId = 'TXN-'.strtoupper(Str::random(10));

                // Create the payment
                Payment::create([
                    'person_id' => $targetPerson->id,
                    'member_id' => $targetMember->id,
                    'payment_type_id' => $paymentType->id,
                    'payment_method_id' => $paymentMethod->id,
                    'reference_number' => $referenceNumber,
                    'amount' => $amount,
                    'payment_date' => $paymentDate,
                    'due_date' => $dueDate,
                    'status' => $status,
                    'transaction_id' => $transactionId,
                    'notes' => 'Payment for member with email medico@hostmoz.net',
                    'receipt_path' => null,
                    'payable_type' => Member::class,
                    'payable_id' => $targetMember->id,
                    'recorded_by' => $users->random()->id,
                    'created_at' => $paymentDate,
                    'updated_at' => $paymentDate,
                ]);
            }
        }

        // Create remaining payments
        for ($i = 0; $i < 300; $i++) {
            // Determine if payment is for a member or a registration
            $isForMember = rand(0, 1) == 1; // 50% chance

            // Get a random member or registration
            $member = null;
            $person = null;
            $payable = null;
            $payableType = null;
            $payableId = null;

            if ($isForMember && $members->isNotEmpty()) {
                $member = $members->random();
                $person = $member->person;
                $payable = $member;
                $payableType = Member::class;
                $payableId = $member->id;
            } elseif ($registrations->isNotEmpty()) {
                $registration = $registrations->random();
                $person = $registration->person;
                $member = $person->member ?? null;
                $payable = $registration;
                $payableType = \Modules\Registration\Models\Registration::class;
                $payableId = $registration->id;
            } else {
                // Skip if no members or registrations
                continue;
            }

            // Skip if no person (should never happen, but just in case)
            if (! $person) {
                continue;
            }

            // Get random payment type and method
            $paymentType = $paymentTypes->random();
            $paymentMethod = $paymentMethods->random();

            // Generate random status with weighted probabilities
            $statusWeights = [
                'pending' => 20,
                'completed' => 60,
                'failed' => 10,
                'refunded' => 10,
            ];
            $status = $this->getRandomWeightedElement($statusWeights);

            // Generate random date within the last 3 years
            $paymentDate = now()->subDays(rand(1, 365 * 3));

            // Generate random due date (if applicable)
            $dueDate = rand(0, 1) == 1 ? (clone $paymentDate)->subDays(rand(1, 30)) : null;

            // Generate random amount (use default amount from payment type with some variation)
            $amount = $paymentType->default_amount * (rand(80, 120) / 100); // 80% to 120% of default amount

            // Generate unique reference number
            $referenceNumber = 'PAY-'.date('Ymd', strtotime($paymentDate)).'-'.strtoupper(Str::random(6));

            // Generate random transaction ID for completed payments
            $transactionId = $status == 'completed' ? 'TXN-'.strtoupper(Str::random(10)) : null;

            // Generate random notes
            $notes = rand(0, 3) == 0 ? $this->getRandomNote() : null; // 25% chance of having notes

            // Create the payment
            Payment::create([
                'person_id' => $person->id,
                'member_id' => $member ? $member->id : null,
                'payment_type_id' => $paymentType->id,
                'payment_method_id' => $paymentMethod->id,
                'reference_number' => $referenceNumber,
                'amount' => $amount,
                'payment_date' => $paymentDate,
                'due_date' => $dueDate,
                'status' => $status,
                'transaction_id' => $transactionId,
                'notes' => $notes,
                'receipt_path' => null, // No receipt path for now
                'payable_type' => $payableType,
                'payable_id' => $payableId,
                'recorded_by' => $users->random()->id,
                'created_at' => $paymentDate,
                'updated_at' => $paymentDate,
            ]);
        }
    }

    /**
     * Get a random element based on weights.
     */
    private function getRandomWeightedElement(array $weightedValues)
    {
        $rand = rand(1, array_sum($weightedValues));

        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }

        return array_key_first($weightedValues);
    }

    /**
     * Get a random note.
     */
    private function getRandomNote(): string
    {
        $notes = [
            'Pagamento processado com sucesso.',
            'Pagamento recebido com atraso.',
            'Pagamento parcial, saldo pendente.',
            'Pagamento com desconto aplicado.',
            'Pagamento para renovação de inscrição.',
            'Pagamento para emissão de carteira profissional.',
            'Pagamento de taxa de exame.',
            'Pagamento de multa por atraso.',
            'Pagamento de anuidade.',
            'Pagamento processado manualmente.',
        ];

        return $notes[array_rand($notes)];
    }
}
