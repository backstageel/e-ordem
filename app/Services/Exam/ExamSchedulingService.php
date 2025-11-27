<?php

namespace App\Services\Exam;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\ExamSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ExamSchedulingService
{
    public function getAvailableSlots(Exam $exam, ?Carbon $date = null): Collection
    {
        $schedules = ExamSchedule::where('exam_id', $exam->id)
            ->where('status', 'scheduled')
            ->when($date, function ($query) use ($date) {
                return $query->where('date', $date->format('Y-m-d'));
            })
            ->where('available_slots', '>', 0)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'date' => $schedule->date->format('Y-m-d'),
                'start_time' => Carbon::parse($schedule->start_time)->format('H:i'),
                'end_time' => Carbon::parse($schedule->end_time)->format('H:i'),
                'location' => $schedule->location,
                'address' => $schedule->address,
                'available_slots' => $schedule->available_slots,
                'capacity' => $schedule->capacity,
            ];
        });
    }

    public function assignSlot(ExamApplication $application, ExamSchedule $schedule): bool
    {
        if ($schedule->available_slots <= 0) {
            return false;
        }

        // Check if exam is the same
        if ($schedule->exam_id !== $application->exam_id) {
            return false;
        }

        // Update application
        $application->exam_schedule_id = $schedule->id;
        $application->is_confirmed = true;
        $application->save();

        // Decrease available slots
        $schedule->available_slots--;
        $schedule->save();

        return true;
    }

    public function releaseSlot(ExamApplication $application): void
    {
        if (! $application->exam_schedule_id) {
            return;
        }

        $schedule = ExamSchedule::find($application->exam_schedule_id);
        if ($schedule) {
            $schedule->available_slots++;
            $schedule->save();
        }

        $application->exam_schedule_id = null;
        $application->is_confirmed = false;
        $application->save();
    }

    public function canReschedule(ExamApplication $application, ExamSchedule $newSchedule): bool
    {
        // Check if exam date is in the future
        $examDate = Carbon::parse($newSchedule->date);
        if ($examDate->isPast()) {
            return false;
        }

        // Check if there are available slots
        if ($newSchedule->available_slots <= 0) {
            return false;
        }

        // Check if rescheduling is allowed (e.g., minimum days before exam)
        $daysBefore = now()->diffInDays($examDate);
        $minimumDays = config('exams.rescheduling.minimum_days_before', 7);

        return $daysBefore >= $minimumDays;
    }

    public function reschedule(ExamApplication $application, ExamSchedule $newSchedule): bool
    {
        if (! $this->canReschedule($application, $newSchedule)) {
            return false;
        }

        // Release old slot
        $this->releaseSlot($application);

        // Assign new slot
        return $this->assignSlot($application, $newSchedule);
    }

    public function checkCapacity(ExamSchedule $schedule): array
    {
        $totalCapacity = $schedule->capacity;
        $assigned = $schedule->applications()->count();
        $available = $schedule->available_slots;

        return [
            'total' => $totalCapacity,
            'assigned' => $assigned,
            'available' => $available,
            'utilization_percentage' => $totalCapacity > 0 ? ($assigned / $totalCapacity) * 100 : 0,
        ];
    }
}
