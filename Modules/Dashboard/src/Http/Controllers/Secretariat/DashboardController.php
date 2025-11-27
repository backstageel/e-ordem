<?php

namespace Modules\Dashboard\Http\Controllers\Secretariat;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the secretariat dashboard.
     */
    public function index(): View
    {
        // TODO: Add KPIs specific to secretariat (registrations, documents, pending items)
        // For now, return basic view

        return view('dashboard::secretariat.index');
    }
}
