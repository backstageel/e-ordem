<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = Exam::orderBy('exam_date', 'desc')->paginate(10);

        // Calculate statistics for dashboard
        $totalExams = Exam::count();
        $scheduledExams = Exam::where('status', 'scheduled')->count();
        $completedExams = Exam::where('status', 'completed')->count();
        $totalCandidates = ExamApplication::count();

        return view('exams.index', compact('exams', 'totalExams', 'scheduledExams', 'completedExams', 'totalCandidates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $evaluators = User::whereHas('roles', function ($query) {
            $query->where('name', 'evaluator');
        })->get();

        return view('exams.create', compact('evaluators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        ]);

        // Calculate duration in minutes
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $validated['duration'] = $end->diffInMinutes($start);

        // Set initial status
        $validated['status'] = 'draft';

        $exam = Exam::create($validated);

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exame criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $exam = Exam::with(['applications', 'primaryEvaluator', 'secondaryEvaluator'])->findOrFail($id);

        return view('exams.show', compact('exam'));
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

        return view('exams.edit', compact('exam', 'evaluators'));
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

        return view('exams.schedule', compact('exam'));
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
    public function candidates(string $id)
    {
        $exam = Exam::with(['applications.user', 'applications.result'])->findOrFail($id);

        return view('exams.candidates', compact('exam'));
    }

    /**
     * Show the form for uploading exam results.
     */
    public function uploadResults(string $id)
    {
        $exam = Exam::with(['applications.user'])->findOrFail($id);

        return view('exams.upload-results', compact('exam'));
    }

    /**
     * Process the uploaded exam results.
     */
    public function processResults(Request $request, string $id)
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

        foreach ($validated['results'] as $result) {
            $application = ExamApplication::find($result['application_id']);

            // Update application presence status
            $application->is_present = ($result['status'] === 'presente');
            $application->save();

            // Create or update result
            $examResult = ExamResult::updateOrCreate(
                ['exam_application_id' => $result['application_id']],
                [
                    'grade' => $result['grade'] ?? null,
                    'status' => $result['status'],
                    'decision_type' => $validated['decision_type'],
                    'notes' => $validated['notes'],
                    'evaluated_by' => Auth::id(),
                    'evaluated_at' => now(),
                ]
            );

            // Set decision based on grade and minimum grade
            if ($result['status'] === 'presente' && isset($result['grade'])) {
                if ($result['grade'] >= $exam->minimum_grade) {
                    $examResult->decision = 'aprovado';
                } else {
                    $examResult->decision = 'reprovado';
                }
                $examResult->save();
            }
        }

        // Update exam status to completed
        $exam->status = 'completed';
        $exam->save();

        // Send notifications if requested
        if ($request->has('notify_candidates') && $request->notify_candidates) {
            // TODO: Implement notification logic
        }

        return redirect()->route('admin.exams.generate-lists', $exam)
            ->with('success', 'Resultados processados com sucesso!');
    }

    /**
     * Show the page for generating lists of admitted and excluded candidates.
     */
    public function generateLists(string $id)
    {
        $exam = Exam::with(['applications.user', 'applications.result'])->findOrFail($id);

        return view('exams.generate-lists', compact('exam'));
    }

    /**
     * Export lists of candidates.
     */
    public function exportLists(Request $request, string $id)
    {
        $exam = Exam::with(['applications.user', 'applications.result'])->findOrFail($id);

        // For now, just return a simple response
        return response()->json([
            'message' => 'Lista exportada com sucesso!',
            'exam_id' => $exam->id,
        ]);
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

        return view('exams.history', compact('exams'));
    }
}
