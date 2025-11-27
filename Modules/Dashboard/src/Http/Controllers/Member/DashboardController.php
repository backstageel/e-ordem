<?php

namespace Modules\Dashboard\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the member dashboard.
     */
    public function index(): View
    {
        // TODO: Add member-specific information (profile, quotas, registrations, cards)
        // For now, return basic view

        return view('dashboard::member.index');
    }
}
