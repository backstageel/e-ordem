<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\ExamSchedule;
use App\Services\Exam\ExamSchedulingService;
use Illuminate\Support\Facades\DB;

class ScheduleExamAction
{
    public function __construct(
        private ExamSchedulingService $schedulingService
    ) {}

    public function execute(ExamApplication $application, int $scheduleId): ExamApplication
    {
        return DB::transaction(function () use ($application, $scheduleId) {
            $schedule = ExamSchedule::findOrFail($scheduleId);

            // Validate that schedule belongs to the same exam
            if ($schedule->exam_id !== $application->exam_id) {
                throw new \Exception('Agendamento não pertence ao mesmo exame da candidatura.');
            }

            // Assign slot
            $assigned = $this->schedulingService->assignSlot($application, $schedule);

            if (! $assigned) {
                throw new \Exception('Não há vagas disponíveis neste horário.');
            }

            // Send confirmation notification
            try {
                $application->user->notify(new \App\Notifications\Exam\ExamScheduledNotification($application));
            } catch (\Throwable $e) {
                // Swallow notification errors
            }

            return $application->fresh();
        });
    }

    public function reschedule(ExamApplication $application, int $newScheduleId): ExamApplication
    {
        return DB::transaction(function () use ($application, $newScheduleId) {
            $newSchedule = ExamSchedule::findOrFail($newScheduleId);

            $rescheduled = $this->schedulingService->reschedule($application, $newSchedule);

            if (! $rescheduled) {
                throw new \Exception('Não foi possível reagendar a candidatura.');
            }

            // TODO: Send confirmation email/SMS
            // TODO: Log rescheduling activity

            return $application->fresh();
        });
    }
}
