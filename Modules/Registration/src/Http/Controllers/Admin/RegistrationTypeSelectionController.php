<?php

namespace Modules\Registration\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RegistrationTypeSelectionController extends Controller
{
    /**
     * Show the registration type selection page for admin/secretariat.
     * Displays 3 main options: Certification, Provisional, Effective
     */
    public function index()
    {
        return view('registration::admin.registrations.type-selection');
    }
}
