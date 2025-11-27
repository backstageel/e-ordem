<?php

namespace App\Actions\Exam;

use App\Data\Exam\ExamData;
use App\Models\Exam;
use App\Models\ExamSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateExamAction
{
    public function execute(ExamData $data): Exam
    {
        return DB::transaction(function () use ($data) {
            // Calculate duration in minutes
            $start = Carbon::parse($data->start_time);
            $end = Carbon::parse($data->end_time);
            $duration = $end->diffInMinutes($start);

            // Create the exam
            $exam = Exam::create([
                'name' => $data->name,
                'type' => $data->type,
                'level' => $data->level,
                'specialty' => $data->specialty,
                'description' => $data->description,
                'exam_date' => $data->exam_date,
                'start_time' => $data->start_time,
                'end_time' => $data->end_time,
                'duration' => $duration,
                'location' => $data->location,
                'address' => $data->address,
                'capacity' => $data->capacity,
                'minimum_grade' => $data->minimum_grade,
                'questions_count' => $data->questions_count,
                'time_limit' => $data->time_limit ?? $duration,
                'attempts_allowed' => $data->attempts_allowed ?? 1,
                'allow_consultation' => $data->allow_consultation,
                'is_mandatory' => $data->is_mandatory,
                'immediate_result' => $data->immediate_result,
                'primary_evaluator_id' => $data->primary_evaluator_id,
                'secondary_evaluator_id' => $data->secondary_evaluator_id,
                'notes' => $data->notes,
                'status' => $data->status ?? 'draft',
            ]);

            // Create initial schedule if exam is scheduled
            if ($exam->status === 'scheduled') {
                $this->createInitialSchedule($exam);
            }

            return $exam;
        });
    }

    private function createInitialSchedule(Exam $exam): ExamSchedule
    {
        // Determine period type based on date
        $month = Carbon::parse($exam->exam_date)->month;
        $isOrdinary = in_array($month, [3, 11]); // March or November

        return ExamSchedule::create([
            'exam_id' => $exam->id,
            'period_type' => $isOrdinary ? 'ordinary' : 'extraordinary',
            'period_name' => $this->getPeriodName($month, $isOrdinary),
            'date' => $exam->exam_date,
            'start_time' => $exam->start_time,
            'end_time' => $exam->end_time,
            'location' => $exam->location,
            'address' => $exam->address,
            'capacity' => $exam->capacity,
            'available_slots' => $exam->capacity,
            'minimum_candidates_required' => $isOrdinary ? 0 : 100,
            'status' => 'scheduled',
            'attendance_sheet_required' => true,
        ]);
    }

    private function getPeriodName(int $month, bool $isOrdinary): string
    {
        if ($isOrdinary) {
            return $month === 3 ? 'Março' : 'Novembro';
        }

        return 'Extraordinária';
    }
}
