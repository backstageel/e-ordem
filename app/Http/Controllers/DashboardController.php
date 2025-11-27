<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\Member;
use App\Models\Payment;
use Modules\Registration\Models\Registration;
use App\Models\ResidenceResident;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get total counts
        $totalDoctors = Member::count();
        $totalRegistrations = Registration::count();
        $totalExams = Exam::count();
        $totalResidents = ResidenceResident::count();

        // Get growth percentages (comparing current month to previous month)
        $currentMonth = now()->month;
        $previousMonth = now()->subMonth()->month;
        $currentYear = now()->year;
        $previousYear = $currentMonth > $previousMonth ? $currentYear : $currentYear - 1;

        $doctorsThisMonth = Member::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $doctorsLastMonth = Member::whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousYear)
            ->count();
        $doctorsGrowth = $doctorsLastMonth > 0
            ? round(($doctorsThisMonth - $doctorsLastMonth) / $doctorsLastMonth * 100, 1)
            : 0;

        $registrationsThisMonth = Registration::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $registrationsLastMonth = Registration::whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousYear)
            ->count();
        $registrationsGrowth = $registrationsLastMonth > 0
            ? round(($registrationsThisMonth - $registrationsLastMonth) / $registrationsLastMonth * 100, 1)
            : 0;

        $examsThisMonth = Exam::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $examsLastMonth = Exam::whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousYear)
            ->count();
        $examsGrowth = $examsLastMonth > 0
            ? round(($examsThisMonth - $examsLastMonth) / $examsLastMonth * 100, 1)
            : 0;

        $residentsThisMonth = ResidenceResident::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $residentsLastMonth = ResidenceResident::whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousYear)
            ->count();
        $residentsGrowth = $residentsLastMonth > 0
            ? round(($residentsThisMonth - $residentsLastMonth) / $residentsLastMonth * 100, 1)
            : 0;

        // Get payment statistics
        $paymentsReceived = Payment::where('status', 'completed')
            ->whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->sum('amount');

        $paymentsPending = Payment::where('status', 'pending')
            ->sum('amount');

        $pendingPaymentsCount = Payment::where('status', 'pending')
            ->count();

        $paymentsOverdue = Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->sum('amount');

        $overduePaymentsCount = Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();

        $paymentsGrowth = 0;
        $paymentsLastMonth = Payment::where('status', 'completed')
            ->whereMonth('payment_date', $previousMonth)
            ->whereYear('payment_date', $previousYear)
            ->sum('amount');

        if ($paymentsLastMonth > 0) {
            $paymentsGrowth = round(($paymentsReceived - $paymentsLastMonth) / $paymentsLastMonth * 100, 1);
        }

        // Get recent registrations
        $recentRegistrations = Registration::with(['member.person', 'registrationType'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get popular specialties
        $popularSpecialties = Member::select('specialty', DB::raw('count(*) as total'))
            ->whereNotNull('specialty')
            ->groupBy('specialty')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Get registration chart data
        $registrationChartData = [];
        $months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        $provisionalData = [];
        $effectiveData = [];

        for ($i = 1; $i <= 12; $i++) {
            $provisionalData[] = Registration::whereMonth('created_at', $i)
                ->whereYear('created_at', $currentYear)
                ->provisional()
                ->count();

            $effectiveData[] = Registration::whereMonth('created_at', $i)
                ->whereYear('created_at', $currentYear)
                ->effective()
                ->count();
        }

        // Get exam chart data
        $examCategories = [
            'Certificação' => ExamApplication::where('exam_type', 'certificacao')->count(),
            'Especialidade' => ExamApplication::where('exam_type', 'especialidade')->count(),
            'Recertificação' => ExamApplication::where('exam_type', 'recertificacao')->count(),
            'Revalidação' => ExamApplication::where('exam_type', 'revalidacao')->count(),
        ];

        return view('dashboard', compact(
            'totalDoctors',
            'totalRegistrations',
            'totalExams',
            'totalResidents',
            'doctorsGrowth',
            'registrationsGrowth',
            'examsGrowth',
            'residentsGrowth',
            'paymentsReceived',
            'paymentsPending',
            'pendingPaymentsCount',
            'paymentsOverdue',
            'overduePaymentsCount',
            'paymentsGrowth',
            'recentRegistrations',
            'popularSpecialties',
            'months',
            'provisionalData',
            'effectiveData',
            'examCategories'
        ));
    }
}
