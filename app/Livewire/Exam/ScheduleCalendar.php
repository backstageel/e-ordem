<?php

namespace App\Livewire\Exam;

use App\Models\Exam;
use App\Services\Exam\ExamSchedulingService;
use Livewire\Component;

class ScheduleCalendar extends Component
{
    public Exam $exam;

    public $selectedDate;

    public $selectedSchedule;

    public $availableSlots = [];

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;
        $this->loadAvailableSlots();
    }

    public function loadAvailableSlots(): void
    {
        $schedulingService = app(ExamSchedulingService::class);
        $this->availableSlots = $schedulingService->getAvailableSlots($this->exam)->toArray();
    }

    public function selectSchedule($scheduleId): void
    {
        $this->selectedSchedule = $scheduleId;
        $this->dispatch('schedule-selected', scheduleId: $scheduleId);
    }

    public function render()
    {
        return view('livewire.exam.schedule-calendar');
    }
}
