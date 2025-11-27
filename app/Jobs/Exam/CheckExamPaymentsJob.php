<?php

namespace App\Jobs\Exam;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckExamPaymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Find exams happening in the next payment deadline days
        $paymentDaysBefore = config('exams.payment.required_days_before', 15);
        $deadlineDate = Carbon::today()->addDays($paymentDaysBefore);

        $exams = Exam::where('status', 'scheduled')
            ->where('payment_required', true)
            ->whereDate('exam_date', '<=', $deadlineDate)
            ->whereDate('exam_date', '>=', Carbon::today())
            ->get();

        foreach ($exams as $exam) {
            // Find applications without confirmed payment
            $applications = ExamApplication::where('exam_id', $exam->id)
                ->where('status', '!=', 'rejected')
                ->get();

            foreach ($applications as $application) {
                // Check if payment exists and is paid
                $payment = Payment::where('payable_type', Exam::class)
                    ->where('payable_id', $exam->id)
                    ->where('person_id', $application->user->person_id ?? null)
                    ->where('status', 'completed')
                    ->latest()
                    ->first();

                if (! $payment && $application->status !== 'documents_pending') {
                    // Update application status to require payment
                    $application->status = 'documents_pending';
                    $application->save();

                    Log::info('Exam application requires payment', [
                        'application_id' => $application->id,
                        'exam_id' => $exam->id,
                    ]);

                    // TODO: Send notification about payment requirement
                }
            }
        }
    }
}
