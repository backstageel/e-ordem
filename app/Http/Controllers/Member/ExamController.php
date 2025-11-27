<?php

namespace App\Http\Controllers\Member;

use App\Actions\Exam\ScheduleExamAction;
use App\Actions\Exam\SubmitApplicationAction;
use App\Data\Exam\ExamApplicationData;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAppeal;
use App\Models\ExamApplication;
use App\Services\Exam\ExamSchedulingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function __construct(
        private ExamSchedulingService $schedulingService
    ) {}

    /**
     * Display a listing of the member's exams.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $person = $user->person;

        $examApplications = ExamApplication::where('user_id', $user->id)
            ->with(['exam', 'result', 'schedule', 'decision'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.exams.index', compact('examApplications'));
    }

    /**
     * Show available exams for application.
     */
    public function available()
    {
        $exams = Exam::where('status', 'scheduled')
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date')
            ->paginate(10);

        return view('member.exams.available', compact('exams'));
    }

    /**
     * Show the application form for an exam.
     */
    public function apply(Exam $exam)
    {
        $user = Auth::user();

        // Check if already applied
        $existingApplication = ExamApplication::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingApplication) {
            return redirect()->route('member.exams.show', $existingApplication)
                ->with('info', 'Você já se candidatou a este exame.');
        }

        return view('member.exams.apply', compact('exam'));
    }

    /**
     * Store the exam application.
     */
    public function store(Request $request, Exam $exam, SubmitApplicationAction $action)
    {
        $validated = $request->validate(ExamApplicationData::rules());

        try {
            $application = $action->execute(ExamApplicationData::from($validated));

            return redirect()->route('member.exams.show', $application)
                ->with('success', 'Candidatura submetida com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show the application details.
     */
    public function show(ExamApplication $application)
    {
        // Verify ownership
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        $application->load(['exam', 'result', 'schedule', 'decision', 'appeals']);

        return view('member.exams.show', compact('application'));
    }

    /**
     * Show the scheduling calendar for an application.
     */
    public function schedule(ExamApplication $application)
    {
        // Verify ownership
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if application is approved
        if ($application->status !== 'approved') {
            return redirect()->route('member.exams.show', $application)
                ->with('error', 'Candidatura precisa ser aprovada antes de agendar.');
        }

        $exam = $application->exam;
        $availableSlots = $this->schedulingService->getAvailableSlots($exam);

        return view('member.exams.schedule', compact('application', 'exam', 'availableSlots'));
    }

    /**
     * Store the schedule selection.
     */
    public function storeSchedule(Request $request, ExamApplication $application, ScheduleExamAction $action)
    {
        // Verify ownership
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'schedule_id' => ['required', 'exists:exam_schedules,id'],
        ]);

        try {
            $action->execute($application, $validated['schedule_id']);

            return redirect()->route('member.exams.show', $application)
                ->with('success', 'Agendamento confirmado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show exam results for the member.
     */
    public function results(ExamApplication $application)
    {
        // Verify ownership
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        $application->load(['exam', 'result', 'decision']);

        return view('member.exams.results', compact('application'));
    }

    /**
     * Show the appeal form.
     */
    public function appeal(ExamApplication $application)
    {
        // Verify ownership
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if result exists and deadline hasn't passed
        if (! $application->result) {
            return redirect()->route('member.exams.show', $application)
                ->with('error', 'Resultado ainda não disponível.');
        }

        // Check if appeal deadline has passed
        $resultPublishedAt = $application->result->evaluated_at ?? now();
        $deadline = $resultPublishedAt->addBusinessDays(config('exams.appeals.deadline_business_days', 10));

        if (now()->gt($deadline)) {
            return redirect()->route('member.exams.show', $application)
                ->with('error', 'Prazo para recurso expirou.');
        }

        // Check if already appealed
        $existingAppeal = ExamAppeal::where('application_id', $application->id)
            ->where('decision', 'pending')
            ->first();

        if ($existingAppeal) {
            return redirect()->route('member.exams.appeals.show', $existingAppeal)
                ->with('info', 'Já existe um recurso pendente para este resultado.');
        }

        return view('member.exams.appeal', compact('application'));
    }

    /**
     * Store the appeal.
     */
    public function storeAppeal(Request $request, ExamApplication $application)
    {
        // Verify ownership
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'appeal_type' => ['required', 'in:correction,other'],
            'reason' => ['required', 'string', 'max:5000'],
            'submitted_via' => ['required', 'in:email,physical,online'],
        ]);

        $resultPublishedAt = $application->result->evaluated_at ?? now();
        $deadline = $resultPublishedAt->addBusinessDays(config('exams.appeals.deadline_business_days', 10));

        $appeal = ExamAppeal::create([
            'exam_id' => $application->exam_id,
            'application_id' => $application->id,
            'result_id' => $application->result?->id,
            'appeal_type' => $validated['appeal_type'],
            'submitted_at' => now(),
            'submitted_via' => $validated['submitted_via'],
            'deadline_date' => $deadline,
            'decision_notes' => $validated['reason'],
            'created_by' => Auth::id(),
        ]);

        // TODO: Send notification

        return redirect()->route('member.exams.appeals.show', $appeal)
            ->with('success', 'Recurso submetido com sucesso!');
    }
}
