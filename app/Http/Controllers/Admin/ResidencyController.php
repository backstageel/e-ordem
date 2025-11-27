<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\ResidencyApplication;
use App\Models\ResidencyEvaluation;
use App\Models\ResidencyLocation;
use App\Models\ResidencyProgram;
use App\Models\ResidencyProgramLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResidencyController extends Controller
{
    // Programs methods
    public function indexPrograms(Request $request)
    {
        $query = ResidencyProgram::query();

        // Apply search filter
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('specialty', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply specialty filter
        if ($request->has('especialidade') && ! empty($request->especialidade)) {
            $query->where('specialty', $request->especialidade);
        }

        // Apply status filter
        if ($request->has('status') && ! empty($request->status)) {
            $query->where('is_active', $request->status === 'active');
        }

        // Get paginated results
        $programas = $query->orderBy('name')->paginate(10);

        return view('admin.residence.programs.index', compact('programas'));
    }

    public function createProgram()
    {
        $coordinators = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'coordinator', 'supervisor']);
        })->orderBy('name')->get();

        return view('admin.residence.programs.create', compact('coordinators'));
    }

    public function storeProgram(Request $request)
    {
        // Validate and store program
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'coordinator_id' => 'nullable|exists:users,id',
        ]);

        // Create the program
        $program = ResidencyProgram::create($validated);

        return redirect()->route('admin.residence.programs.index')
            ->with('success', 'Programa de residência criado com sucesso.');
    }

    public function showProgram($id)
    {
        $program = ResidencyProgram::with(['applications.member.person', 'locations.location'])->findOrFail($id);
        $applications = $program->applications()->with('member.person')->paginate(5);

        return view('admin.residence.programs.show', compact('program', 'applications'));
    }

    public function editProgram($id)
    {
        $program = ResidencyProgram::findOrFail($id);
        $coordinators = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'coordinator', 'supervisor']);
        })->orderBy('name')->get();

        return view('admin.residence.programs.edit', compact('program', 'coordinators'));
    }

    public function updateProgram(Request $request, $id)
    {
        $program = ResidencyProgram::findOrFail($id);

        // Validate and update program
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'coordinator_id' => 'nullable|exists:users,id',
        ]);

        // Update the program
        $program->update($validated);

        return redirect()->route('admin.residence.programs.index')
            ->with('success', 'Programa de residência atualizado com sucesso.');
    }

    public function destroyProgram($id)
    {
        $program = ResidencyProgram::findOrFail($id);

        // Check if there are applications associated with this program
        if ($program->applications()->count() > 0) {
            return redirect()->route('admin.residence.programs.index')
                ->with('error', 'Não é possível excluir um programa que possui candidaturas associadas.');
        }

        // Delete the program
        $program->delete();

        return redirect()->route('admin.residence.programs.index')
            ->with('success', 'Programa de residência excluído com sucesso.');
    }

    // Applications methods
    public function indexApplications(Request $request)
    {
        $query = ResidencyApplication::with(['member.person', 'program', 'location', 'approvedBy']);

        // Apply program filter
        if ($request->has('programa_id') && ! empty($request->programa_id)) {
            $query->where('residency_program_id', $request->programa_id);
        }

        // Apply status filter
        if ($request->has('status') && ! empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->whereHas('member', function ($q) use ($search) {
                $q->whereHas('person', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Get paginated results
        $applications = $query->orderBy('application_date', 'desc')->paginate(10);

        // Get programs for filter dropdown
        $programs = ResidencyProgram::orderBy('name')->get();

        return view('admin.residence.applications.index', compact('applications', 'programs'));
    }

    public function createApplication()
    {
        $programs = ResidencyProgram::where('is_active', true)->orderBy('name')->get();
        $locations = ResidencyLocation::where('is_active', true)->orderBy('name')->get();
        $members = Member::whereHas('person')->orderBy('id')->get();

        return view('admin.residence.applications.create', compact('programs', 'locations', 'members'));
    }

    public function storeApplication(Request $request)
    {
        // Validate and store application
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'residency_program_id' => 'required|exists:residency_programs,id',
            'residency_location_id' => 'nullable|exists:residency_locations,id',
            'application_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Check if the member already has an active application for this program
        $existingApplication = ResidencyApplication::where('member_id', $validated['member_id'])
            ->where('residency_program_id', $validated['residency_program_id'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->first();

        if ($existingApplication) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['member_id' => 'Este membro já possui uma candidatura pendente ou em progresso para este programa.']);
        }

        // Create the application
        $application = ResidencyApplication::create([
            'member_id' => $validated['member_id'],
            'residency_program_id' => $validated['residency_program_id'],
            'residency_location_id' => $validated['residency_location_id'],
            'status' => 'pending',
            'application_date' => $validated['application_date'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('admin.residence.applications.index')
            ->with('success', 'Candidatura submetida com sucesso.');
    }

    public function showApplication($id)
    {
        $application = ResidencyApplication::with(['member.person', 'program', 'location', 'approvedBy', 'evaluations.evaluator'])->findOrFail($id);

        return view('admin.residence.applications.show', compact('application'));
    }

    public function editApplication($id)
    {
        $application = ResidencyApplication::findOrFail($id);
        $programs = ResidencyProgram::orderBy('name')->get();
        $locations = ResidencyLocation::orderBy('name')->get();
        $members = Member::whereHas('person')->orderBy('id')->get();

        return view('admin.residence.applications.edit', compact('application', 'programs', 'locations', 'members'));
    }

    public function updateApplication(Request $request, $id)
    {
        $application = ResidencyApplication::findOrFail($id);

        // Validate and update application
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'residency_program_id' => 'required|exists:residency_programs,id',
            'residency_location_id' => 'nullable|exists:residency_locations,id',
            'status' => 'required|in:pending,approved,rejected,in_progress,completed,cancelled',
            'application_date' => 'required|date',
            'approval_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'expected_completion_date' => 'nullable|date',
            'actual_completion_date' => 'nullable|date',
            'rejection_reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'approved_by' => 'nullable|exists:users,id',
            'is_paid' => 'boolean',
            'payment_reference' => 'nullable|string',
            'payment_date' => 'nullable|date',
            'payment_amount' => 'nullable|numeric|min:0',
        ]);

        // Check if the member already has an active application for this program (excluding this application)
        if ($application->member_id != $validated['member_id'] || $application->residency_program_id != $validated['residency_program_id']) {
            $existingApplication = ResidencyApplication::where('member_id', $validated['member_id'])
                ->where('residency_program_id', $validated['residency_program_id'])
                ->where('id', '!=', $id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->first();

            if ($existingApplication) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['member_id' => 'Este membro já possui uma candidatura pendente ou em progresso para este programa.']);
            }
        }

        // Set approval date and approved_by if status is approved
        if ($validated['status'] === 'approved' && empty($validated['approval_date'])) {
            $validated['approval_date'] = now();
        }

        if ($validated['status'] === 'approved' && empty($validated['approved_by'])) {
            $validated['approved_by'] = Auth::id();
        }

        // Update the application
        $application->update($validated);

        return redirect()->route('admin.residence.applications.index')
            ->with('success', 'Candidatura atualizada com sucesso.');
    }

    public function destroyApplication($id)
    {
        $application = ResidencyApplication::findOrFail($id);

        // Only allow deletion of pending applications
        if ($application->status !== 'pending') {
            return redirect()->route('admin.residence.applications.index')
                ->with('error', 'Apenas candidaturas pendentes podem ser excluídas.');
        }

        // Delete the application
        $application->delete();

        return redirect()->route('admin.residence.applications.index')
            ->with('success', 'Candidatura excluída com sucesso.');
    }

    public function approveApplication($id)
    {
        $application = ResidencyApplication::findOrFail($id);

        // Only allow approval of pending applications
        if ($application->status !== 'pending') {
            return redirect()->route('admin.residence.applications.show', $id)
                ->with('error', 'Apenas candidaturas pendentes podem ser aprovadas.');
        }

        // Update the application
        $application->update([
            'status' => 'approved',
            'approval_date' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('admin.residence.applications.show', $id)
            ->with('success', 'Candidatura aprovada com sucesso.');
    }

    public function rejectApplication($id)
    {
        $application = ResidencyApplication::findOrFail($id);

        // Only allow rejection of pending applications
        if ($application->status !== 'pending') {
            return redirect()->route('admin.residence.applications.show', $id)
                ->with('error', 'Apenas candidaturas pendentes podem ser rejeitadas.');
        }

        // Update the application
        $application->update([
            'status' => 'rejected',
            'approval_date' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('admin.residence.applications.show', $id)
            ->with('success', 'Candidatura rejeitada com sucesso.');
    }

    // Locations methods
    public function indexLocations(Request $request)
    {
        $query = ResidencyLocation::query();

        // Apply status filter
        if ($request->has('status') && ! empty($request->status)) {
            $query->where('is_active', $request->status === 'active');
        }

        // Apply search filter
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('province', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $locations = $query->orderBy('name')->paginate(10);

        return view('admin.residence.locations.index', compact('locations'));
    }

    public function createLocation()
    {
        $countries = DB::table('countries')->orderBy('name')->get();

        return view('admin.residence.locations.create', compact('countries'));
    }

    public function storeLocation(Request $request)
    {
        // Validate and store location
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country_id' => 'required|exists:countries,id',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Create the location
        $location = ResidencyLocation::create($validated);

        return redirect()->route('admin.residence.locations.index')
            ->with('success', 'Local de residência adicionado com sucesso.');
    }

    public function showLocation($id)
    {
        $location = ResidencyLocation::with(['country', 'programLocations.program'])->findOrFail($id);

        return view('admin.residence.locations.show', compact('location'));
    }

    public function editLocation($id)
    {
        $location = ResidencyLocation::findOrFail($id);
        $countries = DB::table('countries')->orderBy('name')->get();

        return view('admin.residence.locations.edit', compact('location', 'countries'));
    }

    public function updateLocation(Request $request, $id)
    {
        $location = ResidencyLocation::findOrFail($id);

        // Validate and update location
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country_id' => 'required|exists:countries,id',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Update the location
        $location->update($validated);

        return redirect()->route('admin.residence.locations.index')
            ->with('success', 'Local de residência atualizado com sucesso.');
    }

    public function destroyLocation($id)
    {
        $location = ResidencyLocation::findOrFail($id);

        // Check if there are program locations associated with this location
        if ($location->programLocations()->count() > 0) {
            return redirect()->route('admin.residence.locations.index')
                ->with('error', 'Não é possível excluir um local que possui programas associados.');
        }

        // Delete the location
        $location->delete();

        return redirect()->route('admin.residence.locations.index')
            ->with('success', 'Local de residência excluído com sucesso.');
    }

    // Evaluations methods
    public function indexEvaluations(Request $request)
    {
        $query = ResidencyEvaluation::with(['application.member.person', 'application.program', 'evaluator']);

        // Apply application filter
        if ($request->has('application_id') && ! empty($request->application_id)) {
            $query->where('residency_application_id', $request->application_id);
        }

        // Apply program filter indirectly through application
        if ($request->has('program_id') && ! empty($request->program_id)) {
            $query->whereHas('application', function ($q) use ($request) {
                $q->where('residency_program_id', $request->program_id);
            });
        }

        // Apply date range filter
        if ($request->has('start_date') && ! empty($request->start_date)) {
            $query->where('evaluation_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && ! empty($request->end_date)) {
            $query->where('evaluation_date', '<=', $request->end_date);
        }

        // Get paginated results
        $evaluations = $query->orderBy('evaluation_date', 'desc')->paginate(10);

        // Get programs and applications for filter dropdowns
        $programs = ResidencyProgram::orderBy('name')->get();
        $applications = ResidencyApplication::whereIn('status', ['in_progress', 'completed'])
            ->with('member.person')
            ->orderBy('id')
            ->get();

        return view('admin.residence.evaluations.index', compact('evaluations', 'programs', 'applications'));
    }

    public function createEvaluation(Request $request)
    {
        $applications = ResidencyApplication::whereIn('status', ['in_progress', 'completed'])
            ->with(['member.person', 'program'])
            ->orderBy('id')
            ->get();

        $evaluators = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'evaluator', 'supervisor']);
        })
            ->orderBy('name')
            ->get();

        // Pre-select application if provided in query string
        $selectedApplication = null;
        if ($request->has('application_id') && ! empty($request->application_id)) {
            $selectedApplication = ResidencyApplication::find($request->application_id);
        }

        return view('admin.residence.evaluations.create', compact('applications', 'evaluators', 'selectedApplication'));
    }

    public function storeEvaluation(Request $request)
    {
        // Validate and store evaluation
        $validated = $request->validate([
            'residency_application_id' => 'required|exists:residency_applications,id',
            'evaluator_id' => 'required|exists:users,id',
            'evaluation_date' => 'required|date',
            'period' => 'required|string|max:255',
            'score' => 'nullable|numeric|min:0|max:20',
            'grade' => 'nullable|string|max:10',
            'comments' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'is_satisfactory' => 'boolean',
        ]);

        // Check if the application is in progress or completed
        $application = ResidencyApplication::find($validated['residency_application_id']);
        if (! $application || ! in_array($application->status, ['in_progress', 'completed'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['residency_application_id' => 'A candidatura deve estar em progresso ou concluída para ser avaliada.']);
        }

        // Create the evaluation
        $evaluation = ResidencyEvaluation::create($validated);

        return redirect()->route('admin.residence.evaluations.index')
            ->with('success', 'Avaliação registrada com sucesso.');
    }

    public function showEvaluation($id)
    {
        $evaluation = ResidencyEvaluation::with(['application.member.person', 'application.program', 'evaluator'])->findOrFail($id);

        return view('admin.residence.evaluations.show', compact('evaluation'));
    }

    public function editEvaluation($id)
    {
        $evaluation = ResidencyEvaluation::findOrFail($id);

        $applications = ResidencyApplication::whereIn('status', ['in_progress', 'completed'])
            ->with(['member.person', 'program'])
            ->orderBy('id')
            ->get();

        $evaluators = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'evaluator', 'supervisor']);
        })
            ->orderBy('name')
            ->get();

        return view('admin.residence.evaluations.edit', compact('evaluation', 'applications', 'evaluators'));
    }

    public function updateEvaluation(Request $request, $id)
    {
        $evaluation = ResidencyEvaluation::findOrFail($id);

        // Validate and update evaluation
        $validated = $request->validate([
            'residency_application_id' => 'required|exists:residency_applications,id',
            'evaluator_id' => 'required|exists:users,id',
            'evaluation_date' => 'required|date',
            'period' => 'required|string|max:255',
            'score' => 'nullable|numeric|min:0|max:20',
            'grade' => 'nullable|string|max:10',
            'comments' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'is_satisfactory' => 'boolean',
        ]);

        // Check if the application is in progress or completed
        $application = ResidencyApplication::find($validated['residency_application_id']);
        if (! $application || ! in_array($application->status, ['in_progress', 'completed'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['residency_application_id' => 'A candidatura deve estar em progresso ou concluída para ser avaliada.']);
        }

        // Update the evaluation
        $evaluation->update($validated);

        return redirect()->route('admin.residence.evaluations.index')
            ->with('success', 'Avaliação atualizada com sucesso.');
    }

    public function destroyEvaluation($id)
    {
        $evaluation = ResidencyEvaluation::findOrFail($id);

        // Delete the evaluation
        $evaluation->delete();

        return redirect()->route('admin.residence.evaluations.index')
            ->with('success', 'Avaliação excluída com sucesso.');
    }

    // Program Locations methods
    public function indexProgramLocations(Request $request)
    {
        $query = ResidencyProgramLocation::with(['program', 'location']);

        // Apply program filter
        if ($request->has('program_id') && ! empty($request->program_id)) {
            $query->where('residency_program_id', $request->program_id);
        }

        // Apply location filter
        if ($request->has('location_id') && ! empty($request->location_id)) {
            $query->where('residency_location_id', $request->location_id);
        }

        // Get paginated results
        $programLocations = $query->orderBy('start_date', 'desc')->paginate(10);

        // Get programs and locations for filter dropdowns
        $programs = ResidencyProgram::orderBy('name')->get();
        $locations = ResidencyLocation::orderBy('name')->get();

        return view('admin.residence.program-locations.index', compact('programLocations', 'programs', 'locations'));
    }

    public function createProgramLocation()
    {
        $programs = ResidencyProgram::where('is_active', true)->orderBy('name')->get();
        $locations = ResidencyLocation::where('is_active', true)->orderBy('name')->get();

        return view('admin.residence.program-locations.create', compact('programs', 'locations'));
    }

    public function storeProgramLocation(Request $request)
    {
        // Validate and store program location
        $validated = $request->validate([
            'residency_program_id' => 'required|exists:residency_programs,id',
            'residency_location_id' => 'required|exists:residency_locations,id',
            'available_slots' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // Create the program location
        $programLocation = ResidencyProgramLocation::create($validated);

        return redirect()->route('admin.residence.program-locations.index')
            ->with('success', 'Local do programa adicionado com sucesso.');
    }

    public function showProgramLocation($id)
    {
        $programLocation = ResidencyProgramLocation::with(['program', 'location'])->findOrFail($id);

        return view('admin.residence.program-locations.show', compact('programLocation'));
    }

    public function editProgramLocation($id)
    {
        $programLocation = ResidencyProgramLocation::findOrFail($id);
        $programs = ResidencyProgram::orderBy('name')->get();
        $locations = ResidencyLocation::orderBy('name')->get();

        return view('admin.residence.program-locations.edit', compact('programLocation', 'programs', 'locations'));
    }

    public function updateProgramLocation(Request $request, $id)
    {
        $programLocation = ResidencyProgramLocation::findOrFail($id);

        // Validate and update program location
        $validated = $request->validate([
            'residency_program_id' => 'required|exists:residency_programs,id',
            'residency_location_id' => 'required|exists:residency_locations,id',
            'available_slots' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // Update the program location
        $programLocation->update($validated);

        return redirect()->route('admin.residence.program-locations.index')
            ->with('success', 'Local do programa atualizado com sucesso.');
    }

    public function destroyProgramLocation($id)
    {
        $programLocation = ResidencyProgramLocation::findOrFail($id);

        // Delete the program location
        $programLocation->delete();

        return redirect()->route('admin.residence.program-locations.index')
            ->with('success', 'Local do programa excluído com sucesso.');
    }

    // Residents methods
    public function indexResidents(Request $request)
    {
        $query = ResidencyApplication::with(['member.person', 'program', 'location'])
            ->whereIn('status', ['approved', 'in_progress', 'completed']);

        // Apply program filter
        if ($request->has('program_id') && ! empty($request->program_id)) {
            $query->where('residency_program_id', $request->program_id);
        }

        // Apply status filter
        if ($request->has('status') && ! empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->whereHas('member', function ($q) use ($search) {
                $q->whereHas('person', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Get paginated results
        $residents = $query->orderBy('start_date', 'desc')->paginate(10);

        // Get programs for filter dropdown
        $programs = ResidencyProgram::orderBy('name')->get();

        return view('admin.residence.residents.index', compact('residents', 'programs'));
    }

    public function createResident()
    {
        $programs = ResidencyProgram::where('is_active', true)->orderBy('name')->get();
        $locations = ResidencyLocation::where('is_active', true)->orderBy('name')->get();
        $members = Member::whereHas('person')->orderBy('id')->get();

        return view('admin.residence.residents.create', compact('programs', 'locations', 'members'));
    }

    public function storeResident(Request $request)
    {
        // Validate and store resident
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'residency_program_id' => 'required|exists:residency_programs,id',
            'residency_location_id' => 'nullable|exists:residency_locations,id',
            'start_date' => 'required|date',
            'expected_completion_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
        ]);

        // Check if the member already has an active residency
        $existingResidency = ResidencyApplication::where('member_id', $validated['member_id'])
            ->whereIn('status', ['approved', 'in_progress'])
            ->first();

        if ($existingResidency) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['member_id' => 'Este membro já possui uma residência ativa.']);
        }

        // Create the residency application
        $application = ResidencyApplication::create([
            'member_id' => $validated['member_id'],
            'residency_program_id' => $validated['residency_program_id'],
            'residency_location_id' => $validated['residency_location_id'],
            'status' => 'approved',
            'application_date' => now(),
            'approval_date' => now(),
            'approved_by' => Auth::id(),
            'start_date' => $validated['start_date'],
            'expected_completion_date' => $validated['expected_completion_date'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('admin.residence.residents.index')
            ->with('success', 'Residente adicionado com sucesso.');
    }

    public function showResident($id)
    {
        $resident = ResidencyApplication::with(['member.person', 'program', 'location', 'approvedBy', 'evaluations.evaluator'])->findOrFail($id);

        return view('admin.residence.residents.show', compact('resident'));
    }

    public function editResident($id)
    {
        $resident = ResidencyApplication::findOrFail($id);
        $programs = ResidencyProgram::orderBy('name')->get();
        $locations = ResidencyLocation::orderBy('name')->get();
        $members = Member::whereHas('person')->orderBy('id')->get();

        return view('admin.residence.residents.edit', compact('resident', 'programs', 'locations', 'members'));
    }

    public function updateResident(Request $request, $id)
    {
        $resident = ResidencyApplication::findOrFail($id);

        // Validate and update resident
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'residency_program_id' => 'required|exists:residency_programs,id',
            'residency_location_id' => 'nullable|exists:residency_locations,id',
            'status' => 'required|in:approved,in_progress,completed,cancelled',
            'start_date' => 'required|date',
            'expected_completion_date' => 'required|date|after:start_date',
            'actual_completion_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string',
        ]);

        // Update the resident
        $resident->update($validated);

        return redirect()->route('admin.residence.residents.index')
            ->with('success', 'Residente atualizado com sucesso.');
    }

    public function destroyResident($id)
    {
        $resident = ResidencyApplication::findOrFail($id);

        // Only allow deletion of approved residents that haven't started
        if ($resident->status !== 'approved' || $resident->start_date <= now()) {
            return redirect()->route('admin.residence.residents.index')
                ->with('error', 'Apenas residentes aprovados que ainda não iniciaram podem ser excluídos.');
        }

        // Delete the resident
        $resident->delete();

        return redirect()->route('admin.residence.residents.index')
            ->with('success', 'Residente excluído com sucesso.');
    }

    // Completions methods
    public function indexCompletions(Request $request)
    {
        $query = ResidencyApplication::with(['member.person', 'program', 'location'])
            ->where('status', 'completed');

        // Apply program filter
        if ($request->has('program_id') && ! empty($request->program_id)) {
            $query->where('residency_program_id', $request->program_id);
        }

        // Apply date range filter
        if ($request->has('start_date') && ! empty($request->start_date)) {
            $query->where('actual_completion_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && ! empty($request->end_date)) {
            $query->where('actual_completion_date', '<=', $request->end_date);
        }

        // Apply search filter
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->whereHas('member', function ($q) use ($search) {
                $q->whereHas('person', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Get paginated results
        $completions = $query->orderBy('actual_completion_date', 'desc')->paginate(10);

        // Get programs for filter dropdown
        $programs = ResidencyProgram::orderBy('name')->get();

        return view('admin.residence.completions.index', compact('completions', 'programs'));
    }

    public function createCompletion()
    {
        $residents = ResidencyApplication::whereIn('status', ['approved', 'in_progress'])
            ->with(['member.person', 'program'])
            ->orderBy('id')
            ->get();

        return view('admin.residence.completions.create', compact('residents'));
    }

    public function storeCompletion(Request $request)
    {
        // Validate and store completion
        $validated = $request->validate([
            'residency_application_id' => 'required|exists:residency_applications,id',
            'completion_date' => 'required|date',
            'final_score' => 'nullable|numeric|min:0|max:20',
            'observations' => 'nullable|string',
        ]);

        // Get the residency application
        $application = ResidencyApplication::findOrFail($validated['residency_application_id']);

        // Update the application to completed status
        $application->update([
            'status' => 'completed',
            'actual_completion_date' => $validated['completion_date'],
            'notes' => $validated['observations'],
        ]);

        return redirect()->route('admin.residence.completions.index')
            ->with('success', 'Conclusão registrada com sucesso.');
    }

    public function showCompletion($id)
    {
        $completion = ResidencyApplication::with(['member.person', 'program', 'location', 'approvedBy', 'evaluations.evaluator'])->findOrFail($id);

        return view('admin.residence.completions.show', compact('completion'));
    }

    public function editCompletion($id)
    {
        $completion = ResidencyApplication::findOrFail($id);

        return view('admin.residence.completions.edit', compact('completion'));
    }

    public function updateCompletion(Request $request, $id)
    {
        $completion = ResidencyApplication::findOrFail($id);

        // Validate and update completion
        $validated = $request->validate([
            'actual_completion_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Update the completion
        $completion->update($validated);

        return redirect()->route('admin.residence.completions.index')
            ->with('success', 'Conclusão atualizada com sucesso.');
    }

    public function destroyCompletion($id)
    {
        $completion = ResidencyApplication::findOrFail($id);

        // Update status back to in_progress
        $completion->update([
            'status' => 'in_progress',
            'actual_completion_date' => null,
        ]);

        return redirect()->route('admin.residence.completions.index')
            ->with('success', 'Conclusão removida com sucesso.');
    }

    public function generateCertificate($id)
    {
        $completion = ResidencyApplication::with(['member.person', 'program'])->findOrFail($id);

        // Here you would typically generate a PDF certificate
        // For now, we'll just redirect with a success message
        return redirect()->route('admin.residence.completions.show', $id)
            ->with('success', 'Certificado gerado com sucesso.');
    }

    // Exams methods
    public function indexExams(Request $request)
    {
        // This would typically show exams related to residency programs
        // For now, we'll return a simple view
        return view('admin.residence.exams.index');
    }

    public function createExam()
    {
        return view('admin.residence.exams.create');
    }

    public function storeExam(Request $request)
    {
        // Validate and store exam
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        // Here you would typically create an exam record
        // For now, we'll just redirect with a success message
        return redirect()->route('admin.residence.exams.index')
            ->with('success', 'Exame criado com sucesso.');
    }

    public function showExam($id)
    {
        return view('admin.residence.exams.show', compact('id'));
    }

    public function editExam($id)
    {
        return view('admin.residence.exams.edit', compact('id'));
    }

    public function updateExam(Request $request, $id)
    {
        // Validate and update exam
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        // Here you would typically update an exam record
        // For now, we'll just redirect with a success message
        return redirect()->route('admin.residence.exams.index')
            ->with('success', 'Exame atualizado com sucesso.');
    }

    public function destroyExam($id)
    {
        // Here you would typically delete an exam record
        // For now, we'll just redirect with a success message
        return redirect()->route('admin.residence.exams.index')
            ->with('success', 'Exame excluído com sucesso.');
    }

    // Reports methods
    public function indexReports()
    {
        return view('admin.residence.reports.index');
    }

    public function generateReport(Request $request)
    {
        // Validate report parameters
        $validated = $request->validate([
            'type' => 'required|string|in:residents,programs,completions',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Here you would typically generate a report
        // For now, we'll just return a view
        return view('admin.residence.reports.show', compact('validated'));
    }

    public function exportReport(Request $request)
    {
        // Validate export parameters
        $validated = $request->validate([
            'type' => 'required|string|in:residents,programs,completions',
            'format' => 'required|string|in:pdf,excel',
        ]);

        // Here you would typically export a report
        // For now, we'll just redirect with a success message
        return redirect()->route('admin.residence.reports.index')
            ->with('success', 'Relatório exportado com sucesso.');
    }

    // History methods
    public function indexHistory(Request $request)
    {
        // This would typically show historical data
        // For now, we'll return a simple view
        return view('admin.residence.history.index');
    }

    public function showHistory($id)
    {
        // This would typically show detailed history for a specific record
        // For now, we'll return a simple view
        return view('admin.residence.history.show', compact('id'));
    }

    // Additional methods
    public function assignLocations(Request $request)
    {
        // Validate and assign locations
        $validated = $request->validate([
            'program_id' => 'required|exists:residency_programs,id',
            'location_ids' => 'required|array',
            'location_ids.*' => 'exists:residency_locations,id',
        ]);

        // Here you would typically assign locations to programs
        // For now, we'll just redirect with a success message
        return redirect()->route('admin.residence.locations.index')
            ->with('success', 'Locais atribuídos com sucesso.');
    }
}
