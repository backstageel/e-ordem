<?php

namespace Modules\Registration\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the member's registrations.
     */
    public function index()
    {
        $member = Auth::user()->person->member;

        // Get member registrations
        $registrations = Registration::where('member_id', $member->id)
            ->with(['registrationType'])
            ->latest('submission_date')
            ->get();

        // Get current active registration
        $activeRegistration = $registrations->first(function ($registration) {
            return $registration->isActive();
        });

        // Calculate statistics
        $stats = [
            'total_registrations' => $registrations->count(),
            'active_registration' => $activeRegistration,
            'days_remaining' => $activeRegistration && $activeRegistration->expiry_date
                ? now()->diffInDays($activeRegistration->expiry_date, false)
                : null,
        ];

        return view('registration::member.registrations.index', compact('registrations', 'stats'));
    }

    /**
     * Show the form for creating a new registration.
     */
    public function create()
    {
        $member = Auth::user()->person->member;
        $registrationTypes = RegistrationType::where('name', 'like', 'Provisional%')
            ->orWhere('name', 'like', 'Effective%')
            ->active()
            ->get();

        return view('registration::member.registrations.create', compact('registrationTypes'));
    }

    /**
     * Store a newly created registration in storage.
     */
    public function store(Request $request)
    {
        $member = Auth::user()->person->member;

        $validated = $request->validate([
            'registration_type_id' => 'required|exists:registration_types,id',
            'professional_category' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'sub_specialty' => 'nullable|string|max:255',
            'workplace' => 'required|string|max:255',
            'workplace_address' => 'required|string|max:255',
            'workplace_phone' => 'nullable|string|max:255',
            'workplace_email' => 'nullable|email|max:255',
            'academic_degree' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'graduation_date' => 'required|date',
            // Add document validation rules as needed
        ]);

        // Generate a unique registration number
        $registrationNumber = 'REG-'.date('Y').'-'.Str::padLeft(Registration::count() + 1, 4, '0');

        // Create the registration
        $registration = Registration::create([
            'member_id' => $member->id,
            'person_id' => $member->person_id,
            'registration_type_id' => $validated['registration_type_id'],
            'registration_number' => $registrationNumber,
            'status' => 'pending',
            'submission_date' => now(),
            'professional_category' => $validated['professional_category'],
            'specialty' => $validated['specialty'],
            'sub_specialty' => $validated['sub_specialty'],
            'workplace' => $validated['workplace'],
            'workplace_address' => $validated['workplace_address'],
            'workplace_phone' => $validated['workplace_phone'],
            'workplace_email' => $validated['workplace_email'],
            'academic_degree' => $validated['academic_degree'],
            'university' => $validated['university'],
            'graduation_date' => $validated['graduation_date'],
        ]);

        // Handle document uploads if needed

        return redirect()->route('member.registrations.show', $registration)
            ->with('success', 'Registration submitted successfully. You can track its status here.');
    }

    /**
     * Display the specified registration.
     */
    public function show(Registration $registration)
    {
        $member = Auth::user()->person->member;

        // Ensure the registration belongs to the authenticated member
        if ($registration->member_id !== $member->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('registration::member.registrations.show', compact('registration'));
    }

    /**
     * Show the form for renewing a registration.
     */
    public function renew(Registration $registration)
    {
        $member = Auth::user()->person->member;

        // Ensure the registration belongs to the authenticated member
        if ($registration->member_id !== $member->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the registration is renewable
        if (! $registration->isRenewable()) {
            return redirect()->route('member.registrations.index')
                ->with('error', 'This registration cannot be renewed at this time.');
        }

        // Get renewal registration type
        $renewalType = RegistrationType::where('name', 'Renewal')->active()->first();

        return view('registration::member.registrations.renew', compact('registration', 'renewalType'));
    }

    /**
     * Store a registration renewal.
     */
    public function storeRenewal(Request $request, Registration $registration)
    {
        $member = Auth::user()->person->member;

        // Ensure the registration belongs to the authenticated member
        if ($registration->member_id !== $member->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the registration is renewable
        if (! $registration->isRenewable()) {
            return redirect()->route('member.registrations.index')
                ->with('error', 'This registration cannot be renewed at this time.');
        }

        $validated = $request->validate([
            'workplace' => 'required|string|max:255',
            'workplace_address' => 'required|string|max:255',
            'workplace_phone' => 'nullable|string|max:255',
            'professional_activities' => 'required|string',
            // Add document validation rules as needed
        ]);

        // Get renewal registration type
        $renewalType = RegistrationType::where('name', 'Renewal')->active()->first();

        // Generate a unique registration number
        $registrationNumber = 'REG-'.date('Y').'-'.Str::padLeft(Registration::count() + 1, 4, '0');

        // Create the renewal registration
        $renewal = Registration::create([
            'member_id' => $member->id,
            'person_id' => $member->person_id,
            'registration_type_id' => $renewalType->id,
            'registration_number' => $registrationNumber,
            'status' => 'pending',
            'submission_date' => now(),
            'is_renewal' => true,
            'previous_registration_id' => $registration->id,
            'professional_category' => $registration->professional_category,
            'specialty' => $registration->specialty,
            'sub_specialty' => $registration->sub_specialty,
            'workplace' => $validated['workplace'],
            'workplace_address' => $validated['workplace_address'],
            'workplace_phone' => $validated['workplace_phone'],
            'notes' => $validated['professional_activities'],
            'academic_degree' => $registration->academic_degree,
            'university' => $registration->university,
            'graduation_date' => $registration->graduation_date,
        ]);

        // Handle document uploads if needed

        return redirect()->route('member.registrations.show', $renewal)
            ->with('success', 'Registration renewal submitted successfully. You can track its status here.');
    }
}
