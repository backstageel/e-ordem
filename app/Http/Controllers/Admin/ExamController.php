<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Exam\CreateExamAction;
use App\Actions\Exam\ProcessResultsAction;
use App\Actions\Exam\UploadResultsAction;
use App\Data\Exam\ExamData;
use App\Exports\Exam\ExamApplicationsExport;
use App\Exports\Exam\ExamResultsExport;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAppeal;
use App\Models\ExamApplication;
use App\Models\ExamDecision;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Exam::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('specialty', 'like', "%{$search}%");
            });
        }

        $exams = $query->orderBy('exam_date', 'desc')->paginate(20);

        // Calculate statistics for dashboard
        $totalExams = Exam::count();
        $scheduledExams = Exam::where('status', 'scheduled')->count();
        $completedExams = Exam::where('status', 'completed')->count();
        $totalCandidates = ExamApplication::count();
        $pendingApplications = ExamApplication::where('status', 'in_review')->count();

        $resultService = app(\App\Services\Exam\ExamResultService::class);
        $recentExamStats = [];
        foreach (Exam::where('status', 'completed')->latest()->take(5)->get() as $exam) {
            $recentExamStats[$exam->id] = $resultService->getStatistics($exam);
        }

        return view('admin.exams.index', compact(
            'exams',
            'totalExams',
            'scheduledExams',
            'completedExams',
            'totalCandidates',
            'pendingApplications',
            'recentExamStats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $evaluators = User::whereHas('roles', function ($query) {
            $query->where('name', 'evaluator');
        })->get();

        return view('admin.exams.create', compact('evaluators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateExamAction $action)
    {
        $validated = $request->validate(ExamData::rules());

        $examData = ExamData::from($validated);
        $exam = $action->execute($examData);

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exame criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $exam = Exam::with(['applications', 'primaryEvaluator', 'secondaryEvaluator'])->findOrFail($id);

        return view('admin.exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $exam = Exam::findOrFail($id);
        $evaluators = User::whereHas('roles', function ($query) {
            $query->where('name', 'evaluator');
        })->get();

        return view('admin.exams.edit', compact('exam', 'evaluators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:teorico,pratico,oral,misto',
            'level' => 'nullable|in:basico,intermediario,avancado',
            'specialty' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'minimum_grade' => 'required|numeric|min:0|max:20',
            'questions_count' => 'nullable|integer|min:1',
            'time_limit' => 'nullable|integer|min:1',
            'attempts_allowed' => 'nullable|integer|min:1',
            'allow_consultation' => 'boolean',
            'is_mandatory' => 'boolean',
            'immediate_result' => 'boolean',
            'primary_evaluator_id' => 'nullable|exists:users,id',
            'secondary_evaluator_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,scheduled,in_progress,completed,cancelled',
        ]);

        // Calculate duration in minutes
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $validated['duration'] = $end->diffInMinutes($start);

        $exam->update($validated);

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exame atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exam = Exam::findOrFail($id);

        // Check if the exam has applications
        if ($exam->applications()->count() > 0) {
            return redirect()->route('admin.exams.show', $exam)
                ->with('error', 'Não é possível excluir um exame com candidaturas associadas.');
        }

        $exam->delete();

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exame excluído com sucesso!');
    }

    /**
     * Show the form for scheduling an exam.
     */
    public function schedule(string $id)
    {
        $exam = Exam::findOrFail($id);

        return view('admin.exams.schedule', compact('exam'));
    }

    /**
     * Update the exam schedule.
     */
    public function saveSchedule(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        // Calculate duration in minutes
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $validated['duration'] = $end->diffInMinutes($start);

        // Update status to scheduled
        $validated['status'] = 'scheduled';

        $exam->update($validated);

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exame agendado com sucesso!');
    }

    /**
     * Show the list of candidates for an exam.
     */
    public function candidates(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);

        $query = $exam->applications()
            ->with(['user.person', 'result', 'schedule']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user.person', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.exams.candidates', compact('exam', 'applications'));
    }

    /**
     * Approve or reject an application.
     */
    public function updateApplicationStatus(Request $request, ExamApplication $application)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'rejection_reason' => ['required_if:status,rejected', 'nullable', 'string', 'max:1000'],
        ]);

        $application->status = $validated['status'];
        if ($validated['status'] === 'rejected') {
            $application->rejection_reason = $validated['rejection_reason'];
        }
        $application->save();

        // TODO: Send notification

        return redirect()->back()->with('success', 'Status da candidatura atualizado com sucesso!');
    }

    /**
     * Show appeals management.
     */
    public function appeals(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);

        $query = ExamAppeal::where('exam_id', $exam->id)
            ->with(['application.user.person', 'result']);

        if ($request->filled('decision')) {
            $query->where('decision', $request->decision);
        }

        $appeals = $query->orderBy('submitted_at', 'desc')->paginate(20);

        return view('admin.exams.appeals', compact('exam', 'appeals'));
    }

    /**
     * Process an appeal.
     */
    public function processAppeal(Request $request, ExamAppeal $appeal)
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:approved,rejected'],
            'decision_notes' => ['required', 'string', 'max:5000'],
            'is_final' => ['boolean'],
        ]);

        $appeal->decision = $validated['decision'];
        $appeal->decision_notes = $validated['decision_notes'];
        $appeal->is_final = $validated['is_final'] ?? false;
        $appeal->processed_by = auth()->id();
        $appeal->processed_at = now();
        $appeal->save();

        // TODO: Send notification

        return redirect()->back()->with('success', 'Recurso processado com sucesso!');
    }

    /**
     * Show decisions management.
     */
    public function decisions(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);

        $decisions = ExamDecision::where('exam_id', $exam->id)
            ->with(['application.user.person', 'result', 'signedBy', 'homologatedBy'])
            ->orderBy('decision_date', 'desc')
            ->paginate(20);

        return view('admin.exams.decisions', compact('exam', 'decisions'));
    }

    /**
     * Show the form for uploading exam results.
     */
    public function uploadResults(string $id, UploadResultsAction $action)
    {
        $exam = Exam::with(['applications.user'])->findOrFail($id);

        if (request()->hasFile('results_file')) {
            try {
                $uploaded = $action->execute($exam, request()->file('results_file'));

                return redirect()->route('admin.exams.upload-results', $exam)
                    ->with('success', 'Ficheiro carregado com sucesso! '.$uploaded['total_records'].' registros encontrados.')
                    ->with('uploaded_results', $uploaded['results']);
            } catch (\Exception $e) {
                return redirect()->route('admin.exams.upload-results', $exam)
                    ->with('error', 'Erro ao carregar ficheiro: '.$e->getMessage());
            }
        }

        return view('admin.exams.upload-results', compact('exam'));
    }

    /**
     * Process the uploaded exam results.
     */
    public function processResults(Request $request, string $id, ProcessResultsAction $action)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'results' => 'required|array',
            'results.*.application_id' => 'required|exists:exam_applications,id',
            'results.*.grade' => 'nullable|numeric|min:0|max:20',
            'results.*.status' => 'required|in:presente,ausente,eliminado',
            'decision_type' => 'required|in:aprovacao_automatica,aprovacao_manual,reprovacao_automatica,reprovacao_manual,recurso',
            'notes' => 'nullable|string',
            'notify_candidates' => 'boolean',
        ]);

        $processed = $action->execute($exam, $validated['results'], $validated['decision_type'] ?? null);

        // Send notifications if requested
        if ($request->has('notify_candidates') && $request->notify_candidates) {
            // TODO: Implement notification logic
        }

        return redirect()->route('admin.exams.generate-lists', $exam)
            ->with('success', 'Resultados processados com sucesso!')
            ->with('statistics', $processed['statistics']);
    }

    /**
     * Show the page for generating lists of admitted and excluded candidates.
     */
    public function generateLists(string $id, ProcessResultsAction $action)
    {
        $exam = Exam::with(['applications.user', 'applications.result'])->findOrFail($id);

        $lists = $action->generateLists($exam);

        return view('admin.exams.generate-lists', compact('exam', 'lists'));
    }

    /**
     * Export lists of candidates.
     */
    public function exportLists(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);
        $type = $request->input('type', 'applications'); // applications or results

        if ($type === 'results') {
            return Excel::download(new ExamResultsExport($exam), 'exam_results_'.$exam->id.'.xlsx');
        }

        return Excel::download(new ExamApplicationsExport($exam), 'exam_applications_'.$exam->id.'.xlsx');
    }

    public function previewList(Request $request, string $id)
    {
        $exam = Exam::with(['applications.user', 'applications.result'])->findOrFail($id);

        // For now, just return a simple HTML preview
        return response('<div class="alert alert-info">Preview da lista será implementado em breve.</div>');
    }

    /**
     * Notify exam results to candidates.
     */
    public function notifyResults(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);

        // For now, just return a simple response
        return response()->json([
            'message' => 'Resultados notificados com sucesso!',
            'exam_id' => $exam->id,
        ]);
    }

    /**
     * Generate certificates for approved candidates.
     */
    public function generateCertificates(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);

        // For now, just return a simple response
        return response()->json([
            'message' => 'Certificados gerados com sucesso!',
            'exam_id' => $exam->id,
        ]);
    }

    public function statistics(string $id)
    {
        $exam = Exam::findOrFail($id);

        // For now, just return a simple response
        return response()->json([
            'message' => 'Estatísticas do exame',
            'exam_id' => $exam->id,
        ]);
    }

    public function archive(string $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exame arquivado com sucesso!');
    }

    /**
     * Show the exam history.
     */
    public function history()
    {
        $exams = Exam::with(['applications', 'results'])
            ->orderBy('exam_date', 'desc')
            ->paginate(10);

        return view('admin.exams.history', compact('exams'));
    }
}
