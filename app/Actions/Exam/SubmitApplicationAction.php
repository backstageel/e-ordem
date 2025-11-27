<?php

namespace App\Actions\Exam;

use App\Data\Exam\ExamApplicationData;
use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\Payment;
use App\Services\Exam\ExamEligibilityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubmitApplicationAction
{
    public function __construct(
        private ExamEligibilityService $eligibilityService,
        private CreatePaymentAction $createPaymentAction
    ) {}

    public function execute(ExamApplicationData $data): ExamApplication
    {
        return DB::transaction(function () use ($data) {
            $exam = Exam::findOrFail($data->exam_id);

            // Validate eligibility
            $eligibilityCheck = $this->eligibilityService->checkEligibility($exam, $data->user_id);

            if (! $eligibilityCheck['eligible']) {
                throw new \Exception('Candidato não é elegível: '.$eligibilityCheck['reason']);
            }

            // Create or validate payment if required
            $payment = null;
            if ($exam->payment_required) {
                $user = \App\Models\User::findOrFail($data->user_id);
                $payment = $this->createPaymentAction->validatePayment($exam, $user);

                if (! $payment || $payment->status !== 'completed') {
                    // Create payment if doesn't exist
                    if (! $payment) {
                        $payment = $this->createPaymentAction->execute($exam, $user);
                    }
                    throw new \Exception('Pagamento não confirmado. É necessário pagar antes de submeter a candidatura.');
                }
            }

            // Create application
            $application = ExamApplication::create([
                'exam_id' => $data->exam_id,
                'user_id' => $data->user_id,
                'exam_type' => $data->exam_type,
                'specialty' => $data->specialty,
                'other_specialty' => $data->other_specialty,
                'preferred_date' => $data->preferred_date,
                'preferred_location' => $data->preferred_location,
                'cv_path' => $this->storeDocument($data->cv_path, 'cv'),
                'payment_proof_path' => $this->storeDocument($data->payment_proof_path, 'payment_proof'),
                'recommendation_letter_path' => $this->storeDocument($data->recommendation_letter_path, 'recommendation'),
                'additional_documents_path' => $this->storeDocument($data->additional_documents_path, 'additional'),
                'experience_summary' => $data->experience_summary,
                'experience_years' => $data->experience_years,
                'current_institution' => $data->current_institution,
                'special_needs' => $data->special_needs,
                'observations' => $data->observations,
                'terms_accepted' => $data->terms_accepted,
                'status' => 'submitted',
            ]);

            // Send notification
            try {
                $application->user->notify(new \App\Notifications\Exam\ApplicationSubmittedNotification($application));
            } catch (\Throwable $e) {
                // Swallow notification errors
            }

            return $application;
        });
    }

    private function storeDocument(?string $tempPath, string $type): ?string
    {
        if (! $tempPath || ! Storage::exists($tempPath)) {
            return null;
        }

        $filename = basename($tempPath);
        $destination = "exam_documents/{$type}/{$filename}";

        // Move to private storage
        Storage::disk('local')->put($destination, Storage::get($tempPath));

        // Clean up temp file if on different disk
        if (Storage::disk('public')->exists($tempPath)) {
            Storage::disk('public')->delete($tempPath);
        } elseif (Storage::exists($tempPath)) {
            Storage::delete($tempPath);
        }

        return $destination;
    }
}
