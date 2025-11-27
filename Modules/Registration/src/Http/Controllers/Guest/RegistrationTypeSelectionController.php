<?php

namespace Modules\Registration\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

class RegistrationTypeSelectionController extends Controller
{
    /**
     * Show the registration type selection page.
     * Displays 3 main options: Certification, Provisional, Effective
     */
    public function index()
    {
        return view('registration::guest.registrations.type-selection');
    }
}
