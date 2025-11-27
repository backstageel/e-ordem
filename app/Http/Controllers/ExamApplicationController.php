<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExamApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = ExamApplication::with(['exam', 'user', 'result'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Total applications
        $totalApplications = ExamApplication::count();

        // Applications created this month
        $monthlyGrowth = ExamApplication::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Pending applications
        $pendingApplications = ExamApplication::where('status', 'in_review')->count();

        // Approved applications
        $approvedApplications = ExamApplication::where('status', 'approved')->count();

        // Rejected applications
        $rejectedApplications = ExamApplication::where('status', 'rejected')->count();

        // Approval and rejection rates
        $approvalRate = $totalApplications > 0 ? round(($approvedApplications / $totalApplications) * 100, 2) : 0;
        $rejectionRate = $totalApplications > 0 ? round(($rejectedApplications / $totalApplications) * 100, 2) : 0;

        // Monthly growth as a percentage of total
        $monthlyGrowthPercent = $totalApplications > 0 ? round(($monthlyGrowth / $totalApplications) * 100, 2) : 0;

        return view('exam-applications.index', compact(
            'applications',
            'totalApplications',
            'monthlyGrowthPercent',
            'pendingApplications',
            'approvedApplications',
            'approvalRate',
            'rejectedApplications',
            'rejectionRate',
            'monthlyGrowth'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $exams = Exam::where('status', 'scheduled')
            ->where('exam_date', '>', now())
            ->get();

        return view('exam-applications.create', compact('exams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'exam_type' => 'required|in:certificacao,especialidade,revalidacao,recertificacao',
            'specialty' => 'required|string|max:255',
            'other_specialty' => 'nullable|required_if:specialty,outra|string|max:255',
            'preferred_date' => 'nullable|date',
            'preferred_location' => 'nullable|string|max:255',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'payment_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'recommendation_letter' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'additional_documents' => 'nullable|file|mimes:pdf,zip|max:20480',
            'experience_summary' => 'required|string',
            'experience_years' => 'required|in:menos_1,1_3,3_5,5_10,mais_10',
            'current_institution' => 'required|string|max:255',
            'special_needs' => 'nullable|string',
            'observations' => 'nullable|string',
            'terms_accepted' => 'required|accepted',
        ]);

        // Handle file uploads
        if ($request->hasFile('cv')) {
            $validated['cv_path'] = $request->file('cv')->store('exam-applications/cv', 'public');
        }

        if ($request->hasFile('payment_proof')) {
            $validated['payment_proof_path'] = $request->file('payment_proof')->store('exam-applications/payment', 'public');
        }

        if ($request->hasFile('recommendation_letter')) {
            $validated['recommendation_letter_path'] = $request->file('recommendation_letter')->store('exam-applications/recommendation', 'public');
        }

        if ($request->hasFile('additional_documents')) {
            $validated['additional_documents_path'] = $request->file('additional_documents')->store('exam-applications/additional', 'public');
        }

        // Set user ID and initial status
        $validated['user_id'] = Auth::id();
        $validated['status'] = $request->has('save_draft') ? 'draft' : 'submitted';

        $application = ExamApplication::create($validated);

        if ($request->has('save_draft')) {
            return redirect()->route('admin.exam-applications.edit', $application)
                ->with('success', 'Rascunho salvo com sucesso!');
        }

        return redirect()->route('admin.exam-applications.show', $application)
            ->with('success', 'Candidatura submetida com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $application = ExamApplication::with(['exam', 'user', 'result'])->findOrFail($id);

        // Check if the user is authorized to view this application
        if (Auth::id() !== $application->user_id && ! Auth::user()->hasRole(['admin', 'evaluator'])) {
            abort(403, 'Não autorizado.');
        }

        return view('exam-applications.show', compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
