<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Exports\ReportsExport;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Member;
use App\Models\Payment;
use Modules\Registration\Models\Registration;
use App\Models\ResidencyApplication;
use App\Models\ResidencyEvaluation;
use App\Models\ResidencyProgram;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index()
    {
        // Get basic statistics for dashboard
        $stats = [
            'total_members' => Member::count(),
            'total_registrations' => Registration::count(),
            'total_payments' => Payment::count(),
            'total_exams' => Exam::count(),
            'total_programs' => ResidencyProgram::count(),
            'total_applications' => ResidencyApplication::count(),
        ];

        // Log the view action

        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Generate operational reports.
     */
    public function operational(Request $request)
    {
        $reportType = $request->input('type', 'members');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $format = $request->input('format', 'view');

        $data = $this->getOperationalData($reportType, $startDate, $endDate);

        if ($format === 'pdf') {
            return $this->generatePDF($data, $reportType, 'Operational Report');
        } elseif ($format === 'excel') {
            return $this->generateExcel($data, $reportType, 'Operational Report');
        }

        // Log the report generation

        return view('admin.reports.operational', compact('data', 'reportType', 'startDate', 'endDate'));
    }

    /**
     * Generate financial reports.
     */
    public function financial(Request $request)
    {
        $reportType = $request->input('type', 'payments');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $format = $request->input('format', 'view');

        $data = $this->getFinancialData($reportType, $startDate, $endDate);

        if ($format === 'pdf') {
            return $this->generatePDF($data, $reportType, 'Financial Report');
        } elseif ($format === 'excel') {
            return $this->generateExcel($data, $reportType, 'Financial Report');
        }

        // Log the report generation

        return view('admin.reports.financial', compact('data', 'reportType', 'startDate', 'endDate'));
    }

    /**
     * Generate custom reports.
     */
    public function custom(Request $request)
    {
        $reportType = $request->input('type');
        $filters = $request->except(['type', 'format']);
        $format = $request->input('format', 'view');

        $data = $this->getCustomData($reportType, $filters);

        if ($format === 'pdf') {
            return $this->generatePDF($data, $reportType, 'Custom Report');
        } elseif ($format === 'excel') {
            return $this->generateExcel($data, $reportType, 'Custom Report');
        }

        // Log the report generation

        return view('admin.reports.custom', compact('data', 'reportType', 'filters'));
    }

    /**
     * Get operational data based on report type.
     */
    private function getOperationalData($type, $startDate = null, $endDate = null)
    {
        $query = null;

        switch ($type) {
            case 'members':
                $query = Member::with(['person', 'registrations']);
                break;
            case 'registrations':
                $query = Registration::with(['member.person']);
                break;
            case 'exams':
                $query = Exam::with(['applications.member.person']);
                break;
            case 'programs':
                $query = ResidencyProgram::with(['applications.member.person', 'locations']);
                break;
            case 'applications':
                $query = ResidencyApplication::with(['member.person', 'program']);
                break;
            case 'evaluations':
                $query = ResidencyEvaluation::with(['application.member.person', 'application.program']);
                break;
            case 'residents':
                $query = Member::with(['person']); // Simplified for now
                break;
            case 'completions':
                $query = Member::with(['person']); // Simplified for now
                break;
            default:
                $query = Member::with(['person', 'registrations']);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query->get();
    }

    /**
     * Get financial data based on report type.
     */
    private function getFinancialData($type, $startDate = null, $endDate = null)
    {
        $query = null;

        switch ($type) {
            case 'payments':
                $query = Payment::with(['member.person']);
                break;
            case 'revenue':
                $query = Payment::where('status', PaymentStatus::COMPLETED->value)->with(['member.person']);
                break;
            case 'pending':
                $query = Payment::where('status', PaymentStatus::PENDING->value)->with(['member.person']);
                break;
            case 'overdue':
                // Overdue is not a PaymentStatus enum value, but we can filter by overdue payments
                $query = Payment::with(['member.person']); // Will need to be handled differently
                break;
            default:
                $query = Payment::with(['member.person']);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query->get();
    }

    /**
     * Get custom data based on report type and filters.
     */
    private function getCustomData($type, $filters = [])
    {
        // This method can be extended for more complex custom reports
        return collect();
    }

    /**
     * Generate PDF report.
     */
    private function generatePDF($data, $reportType, $title)
    {
        $pdf = Pdf::loadView('admin.reports.pdf', compact('data', 'reportType', 'title'));
        $filename = strtolower(str_replace(' ', '_', $title)).'_'.now()->format('Y-m-d_H-i-s').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate Excel report.
     */
    private function generateExcel($data, $reportType, $title)
    {
        $filename = strtolower(str_replace(' ', '_', $title)).'_'.now()->format('Y-m-d_H-i-s').'.xlsx';

        return Excel::download(new ReportsExport($data, $reportType), $filename);
    }

    /**
     * Get report statistics.
     */
    public function statistics(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth());
        $endDate = $request->input('end_date', now());

        $stats = [
            'members' => [
                'total' => Member::count(),
                'new' => Member::whereBetween('created_at', [$startDate, $endDate])->count(),
                'active' => Member::count(), // Simplified for now
            ],
            'registrations' => [
                'total' => Registration::count(),
                'new' => Registration::whereBetween('created_at', [$startDate, $endDate])->count(),
                'approved' => Registration::count(), // Simplified for now
            ],
            'payments' => [
                'total' => Payment::count(),
                'paid' => Payment::count(), // Simplified for now
                'pending' => Payment::count(), // Simplified for now
                'overdue' => Payment::count(), // Simplified for now
                'total_amount' => Payment::sum('amount'),
            ],
            'exams' => [
                'total' => Exam::count(),
                'scheduled' => Exam::count(), // Simplified for now
                'completed' => Exam::count(), // Simplified for now
            ],
            'programs' => [
                'total' => ResidencyProgram::count(),
                'active' => ResidencyProgram::count(), // Simplified for now
                'applications' => ResidencyApplication::count(),
            ],
        ];

        // Log the statistics view

        return response()->json($stats);
    }
}
