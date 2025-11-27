<?php

namespace App\Jobs\Exam;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Notifications\Exam\ExamScheduledNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendExamRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $reminderDays = config('exams.notifications.reminder_days_before', [7, 1]);

        foreach ($reminderDays as $daysBefore) {
            $targetDate = Carbon::today()->addDays($daysBefore);

            // Find exams scheduled for this date
            $exams = Exam::where('status', 'scheduled')
                ->whereDate('exam_date', $targetDate)
                ->get();

            foreach ($exams as $exam) {
                // Get all confirmed applications
                $applications = ExamApplication::where('exam_id', $exam->id)
                    ->where('status', 'approved')
                    ->where('is_confirmed', true)
                    ->with(['user', 'schedule'])
                    ->get();

                foreach ($applications as $application) {
                    try {
                        // Send reminder notification
                        $application->user->notify(new ExamScheduledNotification($application));
                    } catch (\Throwable $e) {
                        // Log error but continue with other notifications
                        \Illuminate\Support\Facades\Log::error('Failed to send exam reminder', [
                            'application_id' => $application->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
        }
    }
}
