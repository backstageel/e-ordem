<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the member's profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $person = $user->person;

        // Get member-specific data
        $member = $person ? $person->member : null;
        $registrations = $member ? $member->registrations()
            ->orderBy('created_at', 'desc')
            ->get() : collect();

        $activeRegistration = $registrations->where('status', 'approved')->first();

        // Get documents count
        $documents = $person ? $person->documents()->count() : 0;
        $approvedDocuments = $person ? $person->documents()->where('status', 'approved')->count() : 0;
        $pendingDocuments = $person ? $person->documents()->where('status', \App\Enums\DocumentStatus::PENDING)->count() : 0;

        // Get exams count
        $exams = $member ? $member->examApplications()->count() : 0;
        $approvedExams = $member ? $member->examApplications()->where('status', 'approved')->count() : 0;
        $scheduledExams = $member ? $member->examApplications()->where('status', 'scheduled')->count() : 0;

        // Get payments count
        $payments = $member ? $member->payments()->count() : 0;

        // Get MFA status
        $isMfaEnabled = $user->isMfaEnabled();

        return view('member.profile', compact(
            'user',
            'person',
            'registrations',
            'activeRegistration',
            'documents',
            'approvedDocuments',
            'pendingDocuments',
            'exams',
            'approvedExams',
            'scheduledExams',
            'payments',
            'isMfaEnabled'
        ));
    }

    /**
     * Update the member's profile information.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $person = $user->person;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'workplace' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Update user information
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // Update person information if it exists
        if ($person) {
            $person->phone = $validated['phone'] ?? $person->phone;
            $person->workplace = $validated['workplace'] ?? $person->workplace;
            $person->address = $validated['address'] ?? $person->address;
            $person->save();
        }

        return Redirect::route('member.profile')->with('status', 'profile-updated');
    }
}
