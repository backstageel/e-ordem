<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamApplication;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the teacher dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Get teacher-specific data
        // This is a simplified dashboard for teachers

        // Get upcoming exams
        $upcomingExams = Exam::where('exam_date', '>=', now())
            ->orderBy('exam_date', 'asc')
            ->take(5)
            ->get();

        // Get recent exam applications
        $recentApplications = ExamApplication::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get total exams
        $totalExams = Exam::count();

        // Get total applications
        $totalApplications = ExamApplication::count();

        return view('teacher.dashboard', compact(
            'upcomingExams',
            'recentApplications',
            'totalExams',
            'totalApplications'
        ));
    }
}
