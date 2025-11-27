<?php

namespace Modules\Registration\Http\Controllers\Guest;

use App\Enums\RegistrationCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;

class RegistrationController extends Controller
{
    /**
     * Show the registration type selection page.
     */
    public function showTypeSelection()
    {
        return view('registration::guest.registrations.type');
    }

    /**
     * Show specific registration types for a category.
     */
    public function selectType(string $category)
    {
        $categoryEnum = RegistrationCategory::tryFrom($category);
        abort_unless($categoryEnum, 404);

        $types = RegistrationType::query()
            ->active()
            ->byCategory($categoryEnum->value)
            ->orderBy('name')
            ->get();

        return view('registration::guest.registrations.select-type', [
            'category' => $categoryEnum,
            'types' => $types,
        ]);
    }

    /**
     * Wizard entry point (legacy - redirects to type selection).
     */
    public function wizard(Request $request)
    {
        return redirect()->route('guest.registrations.type-selection');
    }

    /**
     * Show the success page after registration.
     */
    public function success()
    {
        $registrationNumber = session('registration_number');

        if (! $registrationNumber) {
            return redirect()->route('guest.registrations.type-selection');
        }

        $registration = Registration::query()
            ->where('registration_number', $registrationNumber)
            ->first();

        $payment = null;
        if ($registration) {
            $payment = \App\Models\Payment::query()
                ->where('payable_type', Registration::class)
                ->where('payable_id', $registration->id)
                ->latest('id')
                ->first();
        }

        return view('registration::guest.registrations.success', compact('registrationNumber', 'payment'));
    }

    /**
     * Show the form for checking registration status.
     */
    public function checkStatus()
    {
        return view('registration::guest.registrations.check-status');
    }

    /**
     * Show the registration status.
     */
    public function showStatus(Request $request)
    {
        $messages = [
            'registration_number.required' => 'O campo número de inscrição é obrigatório.',
            'identity_document_number.required' => 'O campo número do documento é obrigatório.',
        ];

        $validated = $request->validate([
            'registration_number' => 'required|string',
            'identity_document_number' => 'required|string',
        ], $messages);

        $registration = Registration::where('registration_number', $validated['registration_number'])
            ->whereHas('person', function ($query) use ($validated) {
                $query->where('identity_document_number', $validated['identity_document_number']);
            })
            ->with(['person', 'registrationType'])
            ->first();

        if (! $registration) {
            return back()->withErrors(['error' => 'Inscrição não encontrada. Por favor, verifique suas informações e tente novamente.']);
        }

        return view('registration::guest.registrations.status', compact('registration'));
    }
}
